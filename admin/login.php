<?php

session_start();


if(isset($_SESSION['Admin'])){
    header('Location:dashbord.php');
    exit();
}

include 'init.php';
// include 'includes/db/db.php';

if(isset($_POST['admin-login'])){

    $email=$_POST['email'];
    $password=$_POST['password'];
    $hashedPassword=sha1($password);  
//  هنا مش قاريء المسار الي تبع الداتا بيز 
    $check1=$connection -> prepare('SELECT * FROM users WHERE `email` = ? and `password` = ? LIMIT 1 ');
    $check1->execute(array($email,$hashedPassword));
    
    $checknumber = $check1->rowCount();
    if($checknumber > 0 ){
        $fetchData = $check1->fetch();


        if($fetchData['role']=='admin'){
            $_SESSION['Admin'] = $fetchData['username'];
            $_SESSION['AdminId'] = $fetchData['id'];
            header('Location:dashbord.php');
            exit();

        }else{
            echo "<div class='alert alert-danger m-auto w-50'>you are not admin</div>";

            header('refresh:3;url=login.php');
            exit();
        }




    }else{  //ROW =0
        echo "<div class='alert alert-danger m-auto w-50'> you are not in db</div>";

  header('refresh:3;url=login.php');
  exit();
    }




}

?>

<header>
    
<div class="jack">
    <div class="mark">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
            <h2>Admin Log In</h2> 
            <div class="form-group">
                <label for="exampleInputEmail1"><h5>Email Address</h5></label>
                <input type="email" name="email" class="form-control l_input"/>
              
              </div>
              <div class="form-group">
                <label for="exampleInputPassword1"><h5>Password</h5></label>
                <input type="password" name="password" class="form-control l_input" />
                
              </div>
          
            <!-- 2 column grid layout for inline styling -->


            <div>
                <!-- Checkbox -->
                <!-- <button type="button" class="btn btn-btn-lg btn-block" onclick="validate()">LOG IN</button> -->
                <input type="submit"class="btn btn-btn-lg btn-block"  name="admin-login" value="Login" id="button" />

                

              <div class=" lima">
                <a href="#!">Forgot User ID?</a><br>
                
              </div>
            </div>
          </form>
    </div>
</div>
</header>




