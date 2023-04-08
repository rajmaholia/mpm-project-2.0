<?php
use function Mpm\Urls\{redirect,reverse};
use function Mpm\View\render;
use Mpm\Contrib\Auth\{UserLoginForm,UserCreationForm,UserChangeForm, PasswordChangeForm};
use Mpm\Validation\Validator;
use Mpm\Database\DB;
use Mpm\Handlers\FileUploadHandler as FUH;
use Mpm\Core\Request;


foreach(APPS as $app) {
  if(count(glob($app."/forms.php"))>0) {
    require_once($app."/forms.php");
  };
}

function admin_dashboard(Request $request){
  global $user;
  if($user->is_staff!=1)
  redirect(reverse('admin_login'));
  return render($request,'admin/dashboard.php', array('groups'=>MODEL_GROUPS,'user'=>$user));
}


function admin_login(Request $request){
  global $user;
  $form = new UserLoginForm();
  if($request->getMethod()=="POST"){
    $form->fill_form($request->POST);
    if($form->is_valid){
      $form_data = Validator::clean($request->POST);
      //$staff = db_read('User',filter:array('is_staff'=>1));
      $staff = DB::read('User',filter:array('is_staff'=>1));
      $staff_usernames = array_column($staff,"username");
      if(in_array($form_data["username"],$staff_usernames)) {
        $row = DB::read("User",filter: array("username"=>$form_data["username"]))[0];
        if(password_verify($form_data["password"],$row["password"])){
          $_SESSION['user'] = array();
          foreach($row as $key=>$value) {
           $_SESSION['user'][$key] = $value;
          }//set user data in session
          redirect(reverse('admin_dashboard'));
        } else {
          $form->error_list["password"]=array("Password is not correct");
        }//check password verify 
      } else {
        $form->error_list["username"]=array("Username is not correct");
      }//check user exist in staff
    }//check form is valid
  }//if post
  return render($request,'admin/admin_login.php', array('form'=>$form,'user'=>$user));
}//login()

function object_list(Request $request, $arguments){
  global $user;
  if($user->is_staff!=1)
  redirect(reverse('admin_login'));
  
  $table = $arguments['table'];
  $table_data = MODEL_METADATA[$table];
  $rows = DB::read($table,order_array:array($table_data['order_array'][0]=>'desc'));

  if(in_array($table,SITE_MODELS)) return render($request,'admin/object_list.php',array('table'=>$table,'table_data'=>$table_data,'user'=>$user,'rows'=>$rows));
  else return render($request,'404.php');
}

function object_detail(Request $request){
  global $user;
  if($user->is_staff!=1)
  redirect(reverse('admin_login'));
  return render($request,'admin/object_detail.php',array('user'=>$user));
}



function create_user(Request $request){
  global $user;
  if(!$user->is_staff)
    redirect(reverse('admin_login'));
  $form = new UserCreationForm();
  if($request->getMethod() == "POST") {
    $form->fill_form($request->POST);
  if($form->is_valid){
    $passwordEqual = Validator::checkequal(Validator::clean($request->POST['password']),Validator::clean($request->POST['confirm_password']));
    if($passwordEqual==false){
     $form->error_list['confirm_password']=array("Both passwords Must be same");
    } else {
      $data = Validator::clean($request->POST);
      $fields = UserCreationForm::$fields;
      $data_array = array();
      foreach($fields as $key=>$value) {
        $data_array[$value] = $data[$value];
      }
      $data_array['password'] = password_hash($data_array['password'], PASSWORD_DEFAULT);
     $res =  DB::column_exists('User',data:array('username'=>$data_array['username']))?$form->error_list['username']=array("Username already exists"):DB::insert('User',data:$data_array);
     if(is_int($res)){
      redirect(reverse('object_list', array("User")));
     }
    }
  }
  }//If POST
  return render($request,'admin/object_create.php', array('form'=>$form,'table'=>"User",'user'=>$user));
}

function change_user(Request $request,$arguments){
  global $user;
  if(!$user->is_staff) redirect(reverse('admin_login'));
  $form = new UserChangeForm();
  $id = $arguments['id'];
  $data = DB::read("User",filter:array('id'=>$id))[0];
 
  $form->fill_form($data);
  if($request->getMethod()=="POST"){
    $form->fill_form($request->POST);
    if($form->is_valid){
      $formdata = Validator::clean($request->POST);
      $formdata["is_staff"] = (isset($formdata["is_staff"]))?1:0;
      DB::update("User",data:$formdata,filter: array('id'=>$id));
      redirect(reverse('object_list', arguments:array("User")));
    }
  }
  return render($request,'admin/auth/user_change_form.php', array('table'=>"User",'id'=>$id,'form'=>$form,'user'=>$user,'dbdata'=>$data));
}

/**
 * View function to  Change User Password in admin dashboard 
 * 
 * @param array $request this is $_SERVER variable
 * @param array $arguments 
 * @return string 
 */
function change_password(Request $request,$arguments){
  global $user;
  if($user->is_staff!=1) redirect(reverse('admin_login'));
  $id = $arguments["user"];
  $form = new PasswordChangeForm();
  $target_user = (object)DB::read("User",filter:["id"=>$id])[0];
  if($request->getMethod()=="POST") {
    $form->fill_form($request->POST);
    if($form->is_valid){
      $cd = Validator::clean($request->POST);
      
      if(!password_verify($cd["old_password"],$target_user->password)){
        $form->error_list["old_password"]=array("Password is not correct");
      } else {
        if($cd["new_password"]==$cd["confirm_new_password"]) {
          if(DB::update("User",filter:array("id"=>$target_user->id),data:array("password"=>password_hash($cd["new_password"], PASSWORD_DEFAULT)))){
            redirect(reverse('object_list',["User"]));
          }
        } else {
            $form->error_list["confirm_new_password"] = array("Both Passwords must be same");
        }//check Password Equal 
      }
    }
  }
  return render($request,'admin/auth/password_change_form.php', array('table'=>"User",'id'=>$id,'form'=>$form,'user'=>$user));
}

function object_create(Request $request,$arguments){
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

function object_edit(Request $request,$arguments){
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

function object_delete(Request $request,$args){
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
