<?php 
namespace Mpm\Contrib\Auth\Controller;

use Mpm\Contrib\Auth\Forms\{UserLoginForm,UserCreationForm, PasswordChangeForm};
use function Mpm\View\render;
use function Mpm\Validation\cleaned_data;
use Mpm\Validation\Validator;
use Mpm\Database\DB;
use function Mpm\Urls\redirect;
use function Mpm\Contrib\Auth\login_required;
use Mpm\Core\Request;

class AuthController {
  
  public function login(Request $request){
    $form = new UserLoginForm();
    if($request->getMethod()=="POST") {
      $form->fill_form($request->POST);
      if($form->is_valid) {
        $cd = Validator::clean($request->POST);
        $username = $cd['username'];
        $password = $cd['password'];
        $result = DB::read('User',filter:array('username'=>$username));
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
    }
    return render($request,'auth/login.php', array('form'=>$form));
  }
  
  function signup(Request $request){
    $form = new UserCreationForm();
    if($request->getMethod() == "POST") {
      $form->fill_form($request->POST);
      if($form->is_valid){
        $data = Validator::clean($request->POST);
        $passwordEqual = Validator::checkequal($data['password'],$data['confirm_password']);
        if($passwordEqual==false){
         $form->error_list['confirm_password']=array("Both passwords Must be same");
        } else {
          $fields = UserCreationForm::$fields;
          $data_array = array();
          foreach($fields as $key=>$value) {
            $data_array[$value] = $data[$value];
          }
          $data_array['password'] = password_hash($data_array['password'], PASSWORD_DEFAULT);
         $res =  DB::column_exists('User',data:array('username'=>$data_array['username']))?$form->error_list['username']=array("Username already exists"):DB::insert('User',data:$data_array);
         if(is_int($res)){
           redirect(reverse('login'));
         }
        }
      }
    }
    return render($request,'auth/signup.php', array('form'=>$form));
  }

  public function logout(Request $request){
    session_destroy();
    redirect(reverse(LOGOUT_REDIRECT_URL));
  }
  
  public function password_change(Request $request){
    login_required();
    global $user;
    $form = new PasswordChangeForm();
    
    if($request->getMethod()=="POST") {
      $form->fill_form($request->POST);
      if(count($form->get_errors())==0) {
        $cd = Validator::clean($request->POST);
        if(!password_verify($cd["old_password"],$user->password)){
          $form->error_list["old_password"]=array("Password is not correct");
        }else {
          if($cd["new_password"]==$cd["confirm_new_password"]) {
            if(DB::update("User",filter:array("id"=>$user->id),data:array("password"=>password_hash($cd["new_password"], PASSWORD_DEFAULT)))){
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
    return render($request,'auth/password_change_form.php', array('form'=>$form));
  }

  public function password_change_done($request){
    global $user;
    return render($request,'auth/password_change_done.php',array("user"=>$user));
  }
}