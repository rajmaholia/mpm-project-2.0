<?php
namespace Mpm\Contrib\Admin\Controller;
use function Mpm\Urls\{redirect,reverse};
use function Mpm\View\render;
use Mpm\Contrib\Auth\Forms\{UserLoginForm,UserCreationForm,UserChangeForm, PasswordChangeForm};
use Mpm\Validation\Validator;
use Mpm\Database\DB;
use Mpm\Handlers\FileUploadHandler as FUH;
use Mpm\Core\Request;


class AdminController {
  public function admin_dashboard(Request $request){
    global $user;
    if($user->is_staff!=1)
    redirect(reverse('admin_login'));
    return render($request,'admin/dashboard.php', array('groups'=>MODEL_GROUPS,'user'=>$user));
  }
  
  public function object_list(Request $request, $arguments){
    global $user;
    if($user->is_staff!=1)
    redirect(reverse('admin_login'));
    
    $table = $arguments['table'];
    $table_data = MODEL_METADATA[$table];
    $rows = DB::read($table,order_array:array($table_data['order_array'][0]=>'desc'));
  
    if(in_array($table,SITE_MODELS)) return render($request,'admin/object_list.php',array('table'=>$table,'table_data'=>$table_data,'user'=>$user,'rows'=>$rows));
    else return render($request,'404.php');
  }
  
  public function object_create(Request $request,$arguments){
    global $user;
    if($user->is_staff!=1)
      redirect(reverse('admin_login'));
    $table = $arguments['table'];
    //$table_data = MODEL_METADATA[$table];
    if(!in_array($table,SITE_MODELS))  return render($request,'404.php');
    if($table == "User") $formClass = "UserCreationForm";
    else $formClass=$table."Form";
    $form = new $formClass();
        
    $formEnctype = FUH::formEnctype($form);
    
    if($request->getMethod()=="POST") {
      $hasFileField = FUH::hasFileField($form);
      if($hasFileField)
        $form->fill_form($request->POST,$_FILES);
      else 
        $form->fill_form($request->POST);
      
      if($form->is_valid){
        $data = Validator::clean($request->POST);
        if($hasFileField){
          $uploadDirs = [];
          foreach($form->fileFields() as $fileField) {
            $uploadDirs[$fileField] = $form->$fileField->upload_to;
          }
          $fileResponse = FUH::uploadFiles($_FILES,$uploadDirs);
          $FILESJSON = $fileResponse["files_json"];
          $data = array_merge($data,$FILESJSON);
        }
        $data['author'] = $user->id;
        DB::insert($table,data:$data);
        redirect(reverse('object_list', arguments:array($table)));
      }
    }
    return render($request,'admin/object_create.php', array('table'=>$table,'form'=>$form,'user'=>$user,'enctype'=>$formEnctype));
  }
  
  public function object_edit(Request $request,$arguments){
    global $user;
    if(!$user->is_staff) redirect(reverse('admin_login'));
      
    $table = $arguments['table'];//Current Table
  
    if(!in_array($table,SITE_MODELS))  return render($request,'404.php');
    $formClass=$table."Form";
      
    $form = new $formClass();
    //Set form enctype for file upload support 
    $formEnctype = FUH::formEnctype($form);
    
    $id = $arguments['id'];
    $data = DB::read($table,filter:array('id'=>$id))[0];
    $hasFileField = FUH::hasFileField($form);
    if($hasFileField){
  
    }
    $form->fill_form($data);
    if($request->getMethod()=="POST") {
      $hasFileField?
        $form->fill_form($request->POST,$_FILES)
       :$form->fill_form($request->POST);
  
      if($form->is_valid){
        $formdata = Validator::clean($request->POST);
        if($hasFileField){
          $uploadDirs = [];
          foreach($form->fileFields() as $fileField) {
            $uploadDirs[$fileField] = $form->$fileField->upload_to;
          }
          $fileResponse = FUH::uploadFiles($_FILES,$uploadDirs);
          $FILESJSON = $fileResponse["files_json"];
          $formdata = array_merge($formdata,$FILESJSON);
        }
        
        DB::update($table,data:$formdata,filter: array('id'=>$id));
        redirect(reverse('object_list', arguments:array($table)));
      }
    }
    return render($request,'admin/object_edit.php', array('table'=>$table,'id'=>$id,'form'=>$form,'user'=>$user,'enctype'=>$formEnctype,"filesData"=>$data));
  }
  
  public function object_delete(Request $request,$args){
    global $user;
    if($user->is_staff!=1) redirect(reverse('admin_login'));
    
    if($request->getMethod()=="POST"){
      $actionData = (object)$request->POST;
      var_dump($actionData);
      $action  = strtolower(trim($actionData->action));
      $targets = json_decode($actionData->actionTargets);
      
      if($action=='delete'){
       DB::delete("User",filter:["id"=>$targets]);
      } 
      redirect(reverse(('object_list'),[$args["table"]]));
      //return render($request,'admin/object_list.php',array('user'=>$user));
    } else {
       redirect(reverse(('object_list'),[$args["table"]]));
    }
  }
}