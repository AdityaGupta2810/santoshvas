 <?php
// session_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
class Functions{
public function redirect($address){
    header("Location:".$address);
}
public function setError($msg){
    $_SESSION['error'] = $msg; }


  public function setAuth($data){
    $_SESSION['Auth'] = $data;
  }                           
                                                //   this Auth section is for login page for authentication 
 public function Auth(){
  if(isset($_SESSION['Auth'])){
     return $_SESSION['Auth'];
 }
 else{
  return false;
 }
 }
public function error(){
  if(isset($_SESSION['error'])){
    echo "Swal.fire('','".$_SESSION['error']."','error')";
    unset($_SESSION['error']);
  }
}
public function setAlert($msg){
 $_SESSION['alert'] = $msg;
}

public function alert(){
    if(isset($_SESSION["alert"])){
      echo "Swal.fire('','".$_SESSION['alert']."','success')";
      unset($_SESSION['alert']);
      
    }
  }

}
$fn=new Functions();
