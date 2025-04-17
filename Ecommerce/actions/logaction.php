<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once './function.class.php';
require_once '../db.php';

if($_POST){
 $post =$_POST;
//  if(isset($_POST['upass']) && isset($_POST['uemail']) && isset($_POST['uname'])){  } we can also use this without creating variable $post

if($post['upass'] && $post['uemail']  ){
  
    // we can directly assign value as $full_name =$db->$post['uname'];   
    //  but we avoid to mascular injection data lead them to acces or modification database or it filters and validates the strings and if some write
    //sql query then it show as string

    $password =md5($db->real_escape_string($post['upass'])); // we write md5 to store password in database as encrypted
    //  instead of md5 we can also use password_hash it is more safe but it requires two time password

    $email_id = $db->real_escape_string($post['uemail']);

   $result=$db->query(" SELECT id, full_name  FROM users WHERE ( email_id='$email_id' AND password='$password' )"); 

    $result = $result->fetch_assoc();

   if($result){
     $fn->setAuth($result);
     $fn->setAlert('Logged in Successfully');
     $fn->redirect('../index.php');
    die();
   }
   
    else{
        $fn->setError('Incorrect Email id or Password');
        $fn->redirect('../user/login.php');
        die();
    }

 }
   else{
    $fn->setError('Please fill the all required field');
    $fn->redirect('../user/login.php');
    die();
}

}

else{

    // header("Location:".'../user/reg.php');  we can directly use this to redirect but we create class show we can use same fun many times
 $fn->redirect('../user/login.php');
 die();
}


