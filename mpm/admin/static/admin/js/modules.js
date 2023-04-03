/** Selector Function **/
function $(selector){
  let selection = document.querySelectorAll(selector);
  return selection;
}

function remove(arr,elem){
    let index = arr.indexOf(elem);
    arr.splice(index,1);
}

function on(selector,event,callback,capture=false){
  let selection = $(selector);
  selection.forEach((elem)=>{
    elem.addEventListener(event,callback,capture);
  });
}

function check(selector){
  let selection = $(selector);
  for(i=0;i<selection.length;i++) {
    selection[i].checked = true;
  }
}

function uncheck(selector){
  let selection = $(selector);
  for(i=0;i<selection.length;i++) {
    selection[i].checked = false;
  }
}

function attr(selector,attr,value){
  let selection = $(selector);
  selection.forEach((elem)=>{
   
  });
}
