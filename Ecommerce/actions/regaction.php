<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once './function.class.php';
require_once '../config.php';

if($_POST){
 $post =$_POST;
//  if(isset($_POST['upass']) && isset($_POST['uemail']) && isset($_POST['uname'])){  } we can also use this without creating variable $post

if($post['upass'] && $post['uname'] && $post['uemail']  ){
  
    $full_name = $db->real_escape_string($post['uname']); // we can directly assign value as $full_name =$db->$post['uname'];   
    //  but we avoid to mascular injection data lead them to acces or modification database or it filters and validates the strings and if some write
    //sql query then it show as string

    $password =md5($db->real_escape_string($post['upass'])); // we write md5 to store password in database as encrypted
    //  instead of md5 we can also use password_hash it is more safe but it requires two time password

    $email_id = $db->real_escape_string($post['uemail']);

   $result=$db->query("SELECT COUNT(*) as user FROM users WHERE ( email_id='$email_id')"); 

    $result = $result->fetch_assoc();

   if($result['user']>0){
     $fn->setError($email_id.'is already registered!');
     $fn->redirect('../user/login.php');
    die();
   }
   try{
    $db->query("INSERT INTO  users(full_name, email_id, password) VALUES('$full_name','$email_id','$password')"); //query to insert into table
   
    $fn->setAlert('Registered Successfully !');
    $fn->redirect('../user/login.php');
    die();

}

   catch(Exception $error){
   $fn->setError($error->getMessage()); 
   $fn->redirect('../user/reg.php');
   die();

   }                                                  
}
else{
    $fn->setError('Please fill the all required field');
    $fn->redirect('../user/reg.php');
    die();
}

}

else{

    // header("Location:".'../user/reg.php');  we can directly use this to redirect but we create class show we can use same fun many times
 $fn->redirect('../user/reg.php');
 die();
}


