<?php

/**
 * Returns html tag combining attribute , text content 
 * 
 * @param string $tag         Eg 'a','p'
 * @param array $attrs        Eg ["name"=>"name1","class"=>"class1"] 
 * @param string $content     Text Content for the tag if needed 
 * @param bool $hasClosingTag Whether tag needs closing tag or not . set false if no need of closing tag Eg in  "img","a"
 * @return string .
 */
function render_tag($tag,$attrs=[],$content="",$hasClosingTag=true){
  $attrString = "";
  foreach($attrs as $key=>$value) {
    $attr = " {$key}='{$value}' ";
    $attrString .= $attr;
  }
  $slashInOpenTag = $hasClosingTag?"":"/";
  $openingTag = "<$tag $attrString $slashInOpenTag>";
  $closingTag = $hasClosingTag?"</$tag>":"";
  $tag = $openingTag.$content.$closingTag;
  return $tag;
}


/**
 * Creates a element before or after of  given identifier in html document.
 * 
 * @param string $position values after|before
 * @param string $identifier 
 * @param string $tag 
 * @param array $attrs associative array ["name"=>"title","href"="one.com"]
 * @param string $content
 * @param bool $hasClosingTag 
 * @return string
 */
function render_tag_offset($position,$identifier,$tag,$attrs=[],$content="",$hasClosingTag=true) {
  $tag       = render_tag($tag,$attrs,$content,$hasClosingTag);//get html of tag
  $position  = ucfirst($position);//Capitalize first letter of $position
  $identifier    = get_offset($identifier);//Array containing tag name, attribute name and its value
  $tagName       = $identifier["tag"];
  $attrName      = $identifier["attr"];
  $attrValue     = $identifier["value"];
  $selector  = "document.querySelector(\"{$tagName}[{$attrName}='{$attrValue}']\");";
  $positionScript = position_script($selector,$tag,$position);//javascript to render and element at given position.
  return $positionScript;
}


/**
 * Returns Javascript  to position an element in html document.
 * 
 * @param string $selector 
 * @param string $tag
 * @param string $position
 * @return string 
 */
function position_script($selector,$tag,$position){
  
 $positionScript = "
  <script>
     selector = $selector;
     tag = `$tag`;
     position = `$position`;
     parentNode = selector.parentNode;
    if(position=='After'){
      selector = selector.nextSibling;
    }
    newNode = document.createElement('div');
    newNode.style.padding='0px';
    newNode.style.margin='0px';
    newNode.style.marginTop='5px';
    newNode.innerHTML = tag;
    parentNode.insertBefore(newNode, selector);
  </script>";
  return $positionScript;
}
 
 
/** 
 * Takes a string of Pattern 'tag:name=value' and Return an array after extracting  Tag name , Attribute name and its value 
 * 
 * @param string $identifier 
 * @return array 
 */
function get_offset($identifier) {
  $tagRegex = "/^(?P<tag>\w+):/";
  $attrRegex = "/(?P<attr>\w+)=/";
  $valueRegex = "/=(?P<value>.+)/";

  $attrResult = preg_match($attrRegex,$identifier,$attr);
  $tagResult = preg_match($tagRegex,$identifier,$tag);
  $valueResult = preg_match($valueRegex,$identifier,$value);

  $tag = $tagResult?$tag["tag"]:"";
  $attr = $attrResult?$attr["attr"]:"";
  $value = $valueResult?$value["value"]:"";
 
  return ["tag"=>$tag,"attr"=>$attr,"value"=>$value];
}

/**
 * Renders multiple tags .
 * 
 * @param string|array $offsets
 * @param string|array $identifiers
 * @param array $tags
 * @param array $attrs
 * @param string|array $contents
 * @param bool|array $hasClosingTag
 * @return string 
 */
function render_tags(array $offsets,array $identifiers,array $tags,array $attrs=[],array $attrValues=[],array $contents=[""],array $hasClosingTags=[true]) {
  $noOfContents   = count($contents);
  $noOfAttrValues = max(array_map("count",$attrValues));
  $noOfTags = ($noOfAttrValues)>$noOfContents?$noOfAttrValues:$noOfContents;
  
  //If length of these arrays are less than $noOfTags , they are padded to length of $noOfTags by repating their last element.
  if(count($tags)<$noOfTags)  $tags = array_pad($tags,$noOfTags,$tags[array_key_last($tags)]);
  if(count($offsets)<$noOfTags) $offsets = array_pad($offsets,$noOfTags,$offsets[array_key_last($offsets)]);
  if(count($identifiers)<$noOfTags) $identifiers = array_pad($identifiers,$noOfTags,$identifiers[array_key_last($identifiers)]);
  if(count($hasClosingTags)<$noOfTags) $hasClosingTags = array_pad($hasClosingTags,$noOfTags,$hasClosingTags[array_key_last($hasClosingTags)]);
  
  $tagString  = "";
  
  $attributesBag = attr_combine($attrs,$attrValues);
  for($i=0;$i<count($tags);$i++){
    $attributes = $attributesBag[$i];
    $tagString .= render_tag_offset($offsets[$i],$identifiers[$i],$tags[$i],$attributes,$contents[$i],$hasClosingTags[$i]);
  }
 
  return $tagString;
}

/** 
 * Takes Array of Arrays in which  each array has  attribute name - value  pairs like "href:google.com" and returns an array that has these pairs as associative array .
 * 
 * Parameter is like [["name:name1","id:id1"],["name:name2","class:class2"]]
 * Returned array is like [["name"=>"name1","id"=>"id1"],["name"=>"name2","class"=>"class2"]]
 * 
 * @param array $attr_value_pairs 
 * @return array 
 */
function attr_map(array $attr_value_pairs){
  $parentArray = [];
  foreach($attr_value_pairs as $attrArray) {
    $attributes = [];
    foreach($attrArray as $attrnValue){
     $arrAttr = explode(":",$attrnValue);
     $attributes[$arrAttr[0]] = $arrAttr[1];
    }
    array_push($parentArray,$attributes);
  }
  return $parentArray;
}

/** 
 * Gets $attrs avalable and their values  . Combine them and return an array of array in which Each child array contains associative array of arrtibute name and values for the tag .
 * 
 * Attributes array is like ["name","id"] and values array is like [["name1","name2","name3"],["id1","id2","id3","id4"]] 
 * This return an array like [["name"=>"name1","id"=>"id1"],["name"=>"name2","id"=>"id2"],["name"=>"name3","id"=>"id3"],["id"=>"id4"]] 
 * 
 * @param array $attrs 
 * @param array $values 
 * @return array 
 */
function attr_combine(array $attrs,array $values):array{
  $attrBag = [];
  $i = 0;
  //if number of values arrays is greater than number of $attrs $values are padded to number of $attrs
  $values = array_pad($values,count($attrs),null);
  while(count(array_column($values,$i))!=0){
    $attrValues = array_column($values,$i);
    
    //Creates single attribute - value array
    $attrValueBagItem = [];
    for($j=0;$j<count($attrValues);$j++) {
      $attrValueBagItem[$attrs[$j]] = $attrValues[$j];
    }
    
    //push upper attribute - value array in $attr Bag
    array_push($attrBag,$attrValueBagItem);
    
    $i++;
  }
  return $attrBag;
}

/**
 * Renders File Links to view them .
 * 
 * @param Mpm\Forms $form 
 * @param array $formData This data is  fetched from Databases 
 * @return string 
 */
 function render_files($form,$formData){
    $fileFields = $form->fileFields();
    
    foreach($fileFields as &$fileField) {
      $fileInstance = $fileField;
      $filePaths = array_map(fn($file)=>MEDIA_URL."$file",$formData[$fileField]);
      if($form->$fileField->multiple=="multiple") $fileField.="[]";
      if(count($filePaths)==0) continue;
      //$fileRootPaths = array_map(fn($filePath)=>BASE_DIR.$filePath,$filePaths);
      //array_map("file_exists",$fileRootPaths);
      echo render_tags(["before"],["input:name=$fileField"],["a"],["href"],[$filePaths],$formData[$fileInstance]);//offset:["after"=>"input:name=image"]
    }
  }


