<?php
use Mpm\Auth\{UserLoginForm,UserCreationForm, PasswordChangeForm};
use function Mpm\View\render;
use function Mpm\Validation\{test_input,checkequal,cleaned_data};
use function Mpm\Database\{db_read,db_column_exists,db_insert};
use function Mpm\Urls\redirect;
use function Mpm\Auth\login_required;


function login($server){
  $form = new UserLoginForm();
  if($server["REQUEST_METHOD"]=="POST") {
    $form->fill_form($_POST);
    $username = test_input($_POST['username']);
    $password = test_input($_POST['password']);
    $result = db_read('User',filter:array('username'=>$username));
    if(count($result)>0) {
      $row = $result[0];
      if(password_verify($password,$row['password'])){
        
        $_SESSION['user'] = array();
        foreach($row as $key=>$value) {
         $_SESSION['user'][$key] = $value;
        }
        redirect(reverse(LOGIN_REDIRECT_URL));
      } else {
        $form->error_list['password'] = array("Password is not correct");
      }
    } else {
      $form->error_list['username'] = array("Username doesn't exist");
    }
  }
  return render($server,'auth/login.php', array('form'=>$form));
}

function signup($server){
  $form = new UserCreationForm();
  if($server['REQUEST_METHOD'] == "POST") {
  $form->fill_form($_POST);
  if(count($form->get_errors())==0){
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
       redirect(reverse('login'));
     }
    }
  }
  }
  return render($server,'auth/signup.php', array('form'=>$form));
}

function logout($server){
  session_destroy();
  redirect(reverse(LOGOUT_REDIRECT_URL));
}

function page_not_found($server){
  return render($server,'404.php');
}

function permission_denied($server){
  return render($server,'permission_denied.php');
}

function password_change($server){
  login_required();
  global $user;
  $form = new PasswordChangeForm();
  
  if($server["REQUEST_METHOD"]=="POST") {
    $form->fill_form($_POST);
    if(count($form->get_errors())==0) {
      $cd = cleaned_data($_POST);
      if(!password_verify($cd["old_password"],$user->password)){
        $form->error_list["old_password"]=array("Password is not correct");
      }else {
        if($cd["new_password"]==$cd["confirm_new_password"]) {
          if(db_update("User",filter:array("id"=>$user->id),data:array("password"=>password_hash($cd["new_password"], PASSWORD_DEFAULT)))){
            echo reverse('password_change_done');
            redirect(
            reverse('password_change_done'));
          }
        } else {
            $form->error_list["confirm_new_password"] = array("Both Passwords must be same");
          }//check Password Equal 
      }
    }
  }
  return render($server,'auth/password_change_form.php', array('form'=>$form));
}

function password_change_done($server){
  global $user;
  return render($server,'auth/password_change_done.php',array("user"=>$user));
}