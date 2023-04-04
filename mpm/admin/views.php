<?php
use function Mpm\Urls\{redirect,reverse};
use function Mpm\View\render;
use Mpm\Auth\{UserLoginForm,UserCreationForm,UserChangeForm, PasswordChangeForm};
use function Mpm\Validation\{cleaned_data,checkequal,test_input};
use function Mpm\Database\{db_read,db_column_exists,db_update,db_insert,db_delete};
use function Mpm\Handlers\upload_file_handler;
use Mpm\Handlers\FileUploadHandler as FUH;



foreach(APPS as $app) {
  if(count(glob($app."/forms.php"))>0) {
    require_once($app."/forms.php");
  };
}

function admin_dashboard($server){
  global $user;
  if($user->is_staff!=1)
  redirect(reverse('admin_login'));
  return render($server,'admin/dashboard.php', array('groups'=>MODEL_GROUPS,'user'=>$user));
}


function admin_login($server){
  global $user;
  $form = new UserLoginForm();
  if($server["REQUEST_METHOD"]=="POST"){
    $form->fill_form($_POST);
    if($form->is_valid){
      $form_data = cleaned_data($_POST);
      $staff = db_read('User',filter:array('is_staff'=>1));
      $staff_users = array_column($staff,"username");
      if(in_array($form_data["username"],$staff_users)) {
        $row = db_read("User",filter: array("username"=>$form_data["username"]))[0];
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
    }//check errors
  }//if post
  return render($server,'admin/admin_login.php', array('form'=>$form,'user'=>$user));
}//login()

function object_list($server, $arguments){
  global $user;
  if($user->is_staff!=1)
  redirect(reverse('admin_login'));
  
  $table = $arguments['table'];
  $table_data = MODEL_METADATA[$table];
  $rows = db_read($table,order_array:array($table_data['order_array'][0]=>'desc'));

  if(in_array($table,SITE_MODELS)) return render($server,'admin/object_list.php',array('table'=>$table,'table_data'=>$table_data,'user'=>$user,'rows'=>$rows));
  else return render($server,'404.php');
}

function object_detail($server){
  global $user;
  if($user->is_staff!=1)
  redirect(reverse('admin_login'));
  return render($server,'admin/object_detail.php',array('user'=>$user));
}



function create_user($server){
  global $user;
  if(!$user->is_staff)
    redirect(reverse('admin_login'));
  $form = new UserCreationForm();
  if($server['REQUEST_METHOD'] == "POST") {
    $form->fill_form($_POST);
  if($form->is_valid){
    $passwordEqual = checkequal(test_input($_POST['password']),test_input($_POST['confirm_password']));
    if($passwordEqual==false){
     $form->error_list['confirm_password']=array("Both passwords Must be same");
    } else {
      $data = cleaned_data($_POST);
      $fields = UserCreationForm::$fields;
      $data_array = array();
      foreach($fields as $key=>$value) {
        $data_array[$value] = $data[$value];
      }
      $data_array['password'] = password_hash($data_array['password'], PASSWORD_DEFAULT);
     $res =  db_column_exists('User',data:array('username'=>$data_array['username']))?$form->error_list['username']=array("Username already exists"):db_insert('User',data:$data_array);
     if(is_int($res)){
      redirect(reverse('object_list', array("User")));
     }
    }
  }
  }//If POST
  return render($server,'admin/object_create.php', array('form'=>$form,'table'=>"User",'user'=>$user));
}

function change_user($server,$arguments){
  global $user;
  if(!$user->is_staff) redirect(reverse('admin_login'));
  $form = new UserChangeForm();
  $id = $arguments['id'];
  $data = db_read("User",filter:array('id'=>$id))[0];
 
  $form->fill_form($data);
  if($server["REQUEST_METHOD"]=="POST"){
    $form->fill_form($_POST);
    if($form->is_valid){
      $formdata = cleaned_data($_POST);
      $formdata["is_staff"] = (isset($formdata["is_staff"]))?1:0;
      db_update("User",data:$formdata,filter: array('id'=>$id));
      redirect(reverse('object_list', arguments:array("User")));
    }
  }
  return render($server,'admin/auth/user_change_form.php', array('table'=>"User",'id'=>$id,'form'=>$form,'user'=>$user,'dbdata'=>$data));
}

/**
 * View function to  Change User Password in admin dashboard 
 * 
 * @param array $server this is $_SERVER variable
 * @param array $arguments 
 * @return string 
 */
function change_password($server,$arguments){
  global $user;
  if($user->is_staff!=1) redirect(reverse('admin_login'));
  $id = $arguments["user"];
  $form = new PasswordChangeForm();
  $target_user = (object)db_read("User",filter:["id"=>$id])[0];
  if($server["REQUEST_METHOD"]=="POST") {
    $form->fill_form($_POST);
    if($form->is_valid){
      $cd = cleaned_data($_POST);
      
      if(!password_verify($cd["old_password"],$target_user->password)){
        $form->error_list["old_password"]=array("Password is not correct");
      } else {
        if($cd["new_password"]==$cd["confirm_new_password"]) {
          if(db_update("User",filter:array("id"=>$target_user->id),data:array("password"=>password_hash($cd["new_password"], PASSWORD_DEFAULT)))){
            redirect(reverse('object_list',["User"]));
          }
        } else {
            $form->error_list["confirm_new_password"] = array("Both Passwords must be same");
        }//check Password Equal 
      }
    }
  }
  return render($server,'admin/auth/password_change_form.php', array('table'=>"User",'id'=>$id,'form'=>$form,'user'=>$user));
}

function object_create($server,$arguments){
  global $user;
  if($user->is_staff!=1)
    redirect(reverse('admin_login'));
  $table = $arguments['table'];
  //$table_data = MODEL_METADATA[$table];
  if(!in_array($table,SITE_MODELS))  return render($server,'404.php');
  if($table == "User") $formClass = "UserCreationForm";
  else $formClass=$table."Form";
  $form = new $formClass();
      
  $formEnctype = FUH::formEnctype($form);
  
  if($server['REQUEST_METHOD']=="POST") {
    $hasFileField = FUH::hasFileField($form);
    if($hasFileField)
      $form->fill_form($_POST,$_FILES);
    else 
      $form->fill_form($_POST);
    
    if($form->is_valid){
      $data = cleaned_data($_POST);
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
      db_insert($table,data:$data);
      redirect(reverse('object_list', arguments:array($table)));
    }
  }
  return render($server,'admin/object_create.php', array('table'=>$table,'form'=>$form,'user'=>$user,'enctype'=>$formEnctype));
}

function object_edit($server,$arguments){
  global $user;
  if(!$user->is_staff) redirect(reverse('admin_login'));
    
  $table = $arguments['table'];//Current Table

  if(!in_array($table,SITE_MODELS))  return render($server,'404.php');
  $formClass=$table."Form";
    
  $form = new $formClass();
  //Set form enctype for file upload support 
  $formEnctype = FUH::formEnctype($form);
  
  $id = $arguments['id'];
  $data = db_read($table,filter:array('id'=>$id))[0];
  $hasFileField = FUH::hasFileField($form);
  if($hasFileField){

  }
  $form->fill_form($data);
  if($server['REQUEST_METHOD']=="POST") {
    $hasFileField?
      $form->fill_form($_POST,$_FILES)
     :$form->fill_form($_POST);

    if($form->is_valid){
      $formdata = cleaned_data($_POST);
      if($hasFileField){
        $uploadDirs = [];
        foreach($form->fileFields() as $fileField) {
          $uploadDirs[$fileField] = $form->$fileField->upload_to;
        }
        $fileResponse = FUH::uploadFiles($_FILES,$uploadDirs);
        $FILESJSON = $fileResponse["files_json"];
        $formdata = array_merge($formdata,$FILESJSON);
      }
      
      db_update($table,data:$formdata,filter: array('id'=>$id));
      redirect(reverse('object_list', arguments:array($table)));
    }
  }
  return render($server,'admin/object_edit.php', array('table'=>$table,'id'=>$id,'form'=>$form,'user'=>$user,'enctype'=>$formEnctype,"filesData"=>$data));
}

function object_delete($server,$args){
  global $user;
  if($user->is_staff!=1) redirect(reverse('admin_login'));
  
  if($server["REQUEST_METHOD"]=="POST"){
    $actionData = (object)$_POST;
    var_dump($actionData);
    $action  = strtolower(trim($actionData->action));
    $targets = json_decode($actionData->actionTargets);
    
    if($action=='delete'){
     db_delete("User",filter:["id"=>$targets]);
    } 
    redirect(reverse(('object_list'),[$args["table"]]));
    //return render($server,'admin/object_list.php',array('user'=>$user));
  } else {
     redirect(reverse(('object_list'),[$args["table"]]));
  }
}


function action(){
  
}