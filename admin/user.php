
<?php

session_start();
// هنا نفس الاستدعاء ملف init 
include("init.php");
include("includes/templets/navbar.php");


if(isset($_GET['page'])){
  $page = $_GET['page'];
}else{
  $page='All';
}


// هنا في صفحه اليوزر وباقي الصفح قاريء عادي المسار لكن المشكله ايه مش عارف 
$statment = $connection-> prepare("SELECT * FROM users ");//هنا لايساوي الي انا داخل منه
$statment->execute(); //دي عشاان ميظهرشي الاميل الي داخل منه يعني انا داخل من ايميل اسمه احمد ميظهرخوش واعمل عليه دليت وكدااااا
$userCount= $statment->rowCount();
$clints = $statment->fetchAll();
?>



<?php if($page == "All"){ ?>
            
<div class="card">
  

  <a href="?page=addusers" type="button" class="btn btn-secondary soso mt-5">Add new user</a> 
  
  
  <div class="card-body">
  
 <div class="card-header ">
  User Mangement <span class="badge badge-primary"><?php echo $userCount;?></span>
  </div>


  <table class="table table-light table-hover table-striped table-bordered text-center">
 
  
 
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Username</th>
      <th scope="col">E-mail</th>
      <th scope="col">Status</th>
      <th scope="col">Role</th>
      <th scope="col">operation</th>


    </tr>
  </thead>
  <tbody>
      <?php
      if($userCount>0){
          foreach($clints as $clint){

          

      
      ?>
    <tr>
      <th scope="row"><?php echo $clint['id']?></th>
      <td><?php echo $clint['username']?></td>
      <td><?php echo $clint['email']?></td>
      <td><?php 
           if($clint['status'] == 0)
           {
             echo '<span class="badge bg-info">Pending</span>';
           }else{
            echo '<span class="badge bg-success">Approved</span>';
           }
       ?>
       </td>
      <td><?php echo $clint['role']?></td>
      <td>
          <a href="?page=show&userid=<?php echo $clint['id']?>" class="btn btn-primary">
          <i class="fas fa-eye"></i>
          </a>
          <a href= > </a>

          <a class="btn btn-danger" href='?page=delete&userid=<?php echo $clint['id']?>' >
          <i class="fas fa-trash"></i>
          </a>
      </td>

    </tr>
  <?php
      }
    
    }


  ?>
  </tbody>
</table>






<?php }elseif($page=="saveuser"){
if($_SERVER['REQUEST_METHOD']=='POST'){
  if(isset($_POST['save-user'])){
    $usernameErr = $emailErr = $passwordErr = $roleErr = '';

    $username = $_POST['username'];
    $email =$_POST['email'];
    $password = $_POST['password'];
    $hashedPassword = sha1($password);
    $status = $_POST['status'];
    $role =$_POST['role'];


    if(!empty($username)){
      $username = filter_var($username , FILTER_SANITIZE_STRING);
    }else{
      $usernameErr ="Username is required";
    }
    if(!empty($email)){
      $email = filter_var($email , FILTER_SANITIZE_EMAIL);
    }else{
      $emailErr ="Email is required";
    }
    if(!empty($password)){
      $password = filter_var($password , FILTER_SANITIZE_STRING);
    }else{
      $passwordErr ="Password is required";
    }
    if(!empty($role)){
      $role = filter_var($role , FILTER_SANITIZE_STRING);
    }else{
      $roleErr ="Role is required";
    }


    if(empty($usernameErr) && empty($emailErr) && empty($passwordErr) && empty($roleErr)){
      $stmt = $connection->
      prepare('INSERT INTO users(`username` , `email` , `password` , `status` , `role` , `created_at`)
      VALUES (:zusername , :zemail , :zpassword , :zstatus , :zrole , now())
      ');
      $stmt->execute(array(
        'zusername' => $username ,
        'zemail' => $email ,
        'zpassword' => $hashedPassword ,
        'zstatus' => $status,
        'zrole' => $role
      ));

      if($stmt -> rowCount() >0){
        echo "<div class='alert alert-success m-auto w-50'>User has been Created successfully</div>";
      header("refresh:3;url=user.php");
      exit();
      }

  }else {
    echo 'There are errors';
  }

}

}
// <=============================>
// <=============================>
// <=============================>
// <=============================>

}elseif($page=="update"){

  if($_SERVER['REQUEST_METHOD']=='POST'){

    $username = $_POST['username'];
    $email = $_POST['email'];
    $userid = $_POST['userid'];
    $status = $_POST['status'];
    $role = $_POST['role'];

    $updatestatment = $connection->

    prepare('UPDATE users SET  `username`= ?,`email`= ?,`status`= ?,`role`= ?,`updated_at`= now() WHERE id= ?');
    $updatestatment ->execute(array($username ,$email ,$status , $role , $userid ));
    $updateRow =$updatestatment->rowCount();

    if($updateRow > 0){
      echo "<div class='alert alert-success m-auto w-50'>User has been Updated successfully</div>";
        header("refresh:3;url=user.php");
        exit();
    }

  }






 }
// <===================================>
// <===================================>
// <===================================>
// <===================================>

elseif($page=="show"){
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

<div class="container-fluid card">
<h1 class="text-center   mt-2 "> Edit User</h1>
  
    <div class="row ">
      <div class="col-md-6  inall">


      <form  class="mt-3 mb-5 f_add  " method="post" action="?page=update">
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
        type="radio" name="status" value="0">pending
        <input
        <?php
            if($usersInfo ['status']==='1'){
            echo 'checked';
          }else{
            echo '';
          }
          ?>
        type="radio" name="status" value="1">Approved
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
    <input type="submit" class="btn btn-danger" name="save-user" value="Save" />
  </form>
      </div>
    </div>
  </div>


<!-- end html code  -->


<?php }

elseif($page=="addusers"){ ?>


<div class="card">
  <div class="inall">
<h1>Add new user</h1>



<form style="width:450px,margin-right:100px;" method="post" action="?page=saveuser">
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
<br>
<select name="role" class="foem-control">
  <option readonly>---chose role</option>
  <option value="admin">admin</option>
  <option value="user">user</option>

</select>
<br>
<br>

      <label class="mr-2">Status</label>
        <input type="radio" name="status" value="Pending">Pending
        <input type="radio" name="status" value="1">Approved
        <br>
        <br>
<input type="submit" class="btn btn-primary" name="save-user" >
</form>
</div>
</div>
<?php 
}



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
  echo "<div class='alert alert-danger m-auto w-50'>User has been deleted successfully</div>";

  header('refresh:3;url=user.php');
  exit();
}

}



}


?>


  </div>
</div>




<?php
     include("includes/templets/footer.php");
?>