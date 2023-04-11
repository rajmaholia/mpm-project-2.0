<?php
namespace Mpm\Contrib\Admin\Controller;
use function Mpm\Urls\{redirect,reverse};
use function Mpm\View\render;
use Mpm\Contrib\Auth\Forms\{UserLoginForm,UserCreationForm,UserChangeForm, PasswordChangeForm};
use Mpm\Validation\Validator;
use Mpm\Database\DB;
use Mpm\Handlers\FileUploadHandler as FUH;
use Mpm\Core\Request;

class AdminAuthController {
  public function admin_login(Request $request){
    $user = $request->user;
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

  /**
   * View function to  Change User Password in admin dashboard 
   * 
   * @param array $request this is $_SERVER variable
   * @param array $arguments 
   * @return string 
   */
  public function change_password(Request $request,$arguments){
    $user = $request->user;
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
  
  public function change_user(Request $request,$arguments){
    $user = $request->user;
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
  
  public function create_user(Request $request){
    $user = $request->user;
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
}