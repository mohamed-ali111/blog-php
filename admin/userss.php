<!-- بسم الله الرحمن الرحيم  -->
<!-- الله اجعله فهما مباركا فيه  -->

<?php 
session_start();
include('init.php');
include('includes/templets/navbar.php');

// $page = "All";
if(isset($_GET['page'])){
    $page = $_GET['page'];
}else{
    $page = "All";
}

    $statments = $connection->prepare('SELECT * FROM users');
    $statments->execute();
    $userscount = $statments->rowCount();
    $clients = $statments ->fetchAll();
?>
<?php if($page == "All"){?>

<!-- هنا كوود التش تي ام ال كامل  -->
<!-- start html  -->
<div class="container">
    <div class="text-center">users</div>
        <div class="row">
            <div class="col-md-12">
    
            <div class="card mt-3 mb-4">
  <div class="card-header">
    User Managment
    <span class="badge badge-primary"><?php echo $userscount ;?></span>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive">
  <table class="table table-striped table-bordered table-hover  text-center">
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">UserName</th>
      <th scope="col">E-mail</th>
      <th scope="col">Status</th>
      <th scope="col">Role</th>
      <th scope="col">Operation</th>
    </tr>
  </thead>
<tbody>
<!-- دا جزء php لمسك الداتا ال راجعهمن من ملف النت  -->
<?php
      if($userscount > 0){
          foreach($clients as $x){
              ?>

<tr>
       <th scope="row"><?php echo $x['id']?></th>
      <td><?php echo $x['username']?></td>
      <td><?php echo $x['email']?></td>
      <td><?php echo $x['status']?></td>
      <td><?php echo $x['role']?></td>
      <td>
          <a href="#" class="btn btn-primary">
          <i class="fas fa-eye"></i>
          </a>
         

          <a class="btn btn-primary" href="#" >
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
            </div>
        </div>
     </div>


          </div>
        </div>
    </div>


<!-- end html  -->
<!-- نهايه الكود  -->

<!--   لازم قفله الكوس داخل كود بي اتش بي القفله خاصه بكود الاووووووووول -->
    <?php}?>





<?php
     include("includes/templets/footer.php");
?>

