elseif($page=="delete"){


$userid='';
if(isset($_GET['userid'])&& is_numeric($_GET['userid'])) {
  $userid= intval($_GET['userid']);
}else{
  echo"no data";
}

 
$check = $connection->prepare("SELECT* FROM  users where id=?");
$check->execute(array($userid));
$rows=$check->rowCount();

if($rows > 0 ){
$delstatment = $connection->prepare('DELETE FROM users where id=?');
$delstatment->execute(array($userid));
$delrows=$delstatment->rowCount();

if($delrows > 0) {
  header('Location:user.php');
  exit();
}

}


}

?>































































<?php }elseif($page=='adduser'){ ?>

<h2>Add new user6</h2>



<form style="width:450px" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<div class="form-group">
  <label for="exampleInputEmail1">username</label>
  <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="username">
</div>


<div class="form-group">
  <label for="exampleInputEmail1">email</label>
  <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email">
</div>


<div class="form-group">
  <label for="exampleInputPassword1">Password</label>
  <input type="password" class="form-control" id="exampleInputPassword1" name="password">
</div>
<br>

<label>Role</label>
<select name="role" class="foem-control">
  <option readonly>---chose role</option>
  <option value="admin">admin</option>
  <option value="user">user</option>

</select>
<br>

<input type="submit" class="btn btn-primary" name="admin-login">
</form>


<?php}?> 

































<?php
elseif($page=="saveadduser"){
 
 if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
 
   if(isset($_POST['save'])){
     
     
     $usernameErr = $emailErr = $passwordErr = $roleErr = '';
 
     $username=$_POST['username'];
      $email=$_POST['email'];
     $password=$_POST['password'];
     $hasedpassword=sha1($password);
     $role=$_POST['role'];
 //الجزء ده عشان السيكيورتي هنعمل ايه 
 
   
       
 //اختبار الدوال 
  if(!empty($username)){
 $username = filter_var($username,FILTER_SANITIZE_STRING);
        }else{
          $usernameErr = "username is required";
        }
 
   
 
 
        if(!empty($email)){
         $email = filter_var($email,FILTER_SANITIZE_EMAIL);
                }else{
                  $emailErr = "EMAIL is required";
                }
         
 
 
 
                if(!empty($password)){
                 $password= filter_var($password,FILTER_SANITIZE_STRING);
                        }else{
                          $passwordErr = "PASSWORD is required";
                        }
                 
 
 
                        if(!empty($role)){
                         $role = filter_var($role,FILTER_SANITIZE_STRING);
                                }else{
                                  $roleErr = "role is required";
                                }
 
 
 
 
                               
 // في حاله القيم صحيحه نعمل الانسيرت
 //check if this is right AND NO ERRORS
 
 if(empty($usernameErr) && empty($emailErr) && empty($passwordErr) && empty($roleErr)){
  
   $statment=$connection->
   prepare('INSERT INTO users 
   (`username`=?,`email`=?,`password`=?,`role`=?,
   `created_at`= now() )');
 
 
   $statment->execute(
     array(
      $username,
      $email,
     $hasedpassword,
     
      $role
     
   ));
 
 
   // $states=$state->rowCount();
   if($statment->rowCount() > 0 ){
     echo"users has been created";
     exit();
 
   }
 
     }else{
     echo"there are errors";
   }
 
   }
 }
 
 
 
 
 
   }






















































   elseif($page=="showuser"){

    $userid='';
  if(isset($_GET['userid'])&& is_numeric($_GET['userid'])) {
    $userid= intval($_GET['userid']);
  }else{
    echo"no data";
  }
  
  // check if it exist in databaise
  $show = $connection->prepare("SELECT* FROM  users where id=?");
  $show->execute(array($userid));
  $show1=$show->rowCount();
  
  if($show1 > 0){
    $userinfo=$show->fetch();
  }?>
   
  
  
  <h1>user edit</h1>
  
   <form style="width:450px,margin-right:100px;" method="post" >
  <div class="form-group">
    <label for="exampleInputEmail1">username</label>
    <input value="<?php echo $userinfo['username'];?>" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="username">
  </div>
  
  
  <div class="form-group">
    <label for="exampleInputEmail1">email</label>
    <input value="<?php echo $userinfo['email'];?>" type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email">
  </div>
  
  
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input value=<?php $userinfo['password']; ?>  type="password" class="form-control" id="exampleInputPassword1" name="password">
  </div>
  <br>
  <input name="userid" type="hidden" value="<?php echo $userinfo['id'];?>" >
  
  <label class="mr-2">status</label>
  <input <?php if($userinfo['status']==="0"){
    echo "checked";
  }else{
    echo "";
  }
  ?> type="radio" name="status" value="0">user
  <!-- <========================================================> -->
  <input <?php if($userinfo['status']==='1'){
    echo "checked";
  }else{
  echo ""; 
  }
    ?> type="radio" name="status" value="1">Admin
  
  <br>
  
  <label>Role</label>
  <br>
  <select name="role" class="form-control">
    <option readonly>---chose role</option>
  
    <option <?php
    if($userinfo['role']==='admin'){
      echo 'selected';
    }else{
      echo "";
    } ?>
     value="admin">admin</option>
    <option <?php
    if($userinfo['role']==='user'){
      echo "selected";
    }else{
      echo " ";
    } ?> 
    value="user">user</option>
  
  </select>
  <br>
  
  <input type="submit" class="btn btn-primary" name="save" >
  </form>
  
  
  
  
  
  
  <?php}?>
  




















































































  <?php}elseif($page=="show"){

$userid='';
if(isset($_GET['userid'])&& is_numeric($_GET['userid'])) {
  $userid= intval($_GET['userid']);
}else{
  $userid = '';
}

   $statments = $connection -> prepare("SELECT * FROM users WHERE id = ?");
  $statments -> execute(array($userid));
  $userscount = $statments -> rowCount();
  if($userscount > 0){
    $usersInfo = $statments ->fetch();
  }
?>


<!-- start html code  -->


<h1 class="text-center  mt-2"> Edit User</h1>
  <div class="container">
    <div class="row">
      <div class="col-md-6">


      <form action="?page=Update" class="mt-3 mb-5 f_add" method="post">
    <label>User Name</label>
    <input type="text" name="username" class="form-control" value="<?php echo  $usersInfo ['username'] ; ?>"/>
    <br>
    <label>Email</label>
    <input type="text" name="email" class="form-control"  value="<?php echo  $usersInfo ['email'] ; ?>"/>
    <br>
    <input type="hidden" name="userid" value="<?php echo  $usersInfo ['id'] ; ?>"/>
      <label class="mr-2">Status</label>
        <input 
          <?php
            if($usersInfo ['status']==='0'){
            echo 'checked';
          }else{
            echo '';
          }
          ?>
        type="radio" name="status" value="0">user
        <input
        <?php
            if($usersInfo ['status']==='1'){
            echo 'checked';
          }else{
            echo '';
          }
          ?>
        type="radio" name="status" value="1">Admin
        <br>
      <label>Role</label>
      <select name="role" class="form-control">
       <option readonly>--Choose Role--</option>
       <option 
       <?php
            if($usersInfo ['role']==='admin'){
            echo 'selected';
          }else{
            echo '';
          }
          ?>
       value="admin">Admin</option>
       <option
       <?php
            if($usersInfo ['role']==='user'){
            echo 'selected';
          }else{
            echo '';
          }
          ?>
       value="user">User</option>
      </select><br>
    <input type="submit" class="btn save" name="save-user" value="Save" />
  </form>
      </div>
    </div>
  </div>


<!-- end html code  -->


<?php}?>















