
<?php

ob_start();

session_start();
include("init.php");
include("includes/templets/navbar.php");




if(isset($_GET['page'])){
  $page = $_GET['page'];
}else{
  $page='All';
}

$statment = $connection-> prepare("SELECT p. * FROM (( posts p
INNER JOIN users u ON p.user_id =u.id ) 
INNER JOIN catogeries c ON p.catogery_id = c.id);");
$statment->execute();
$postCount= $statment->rowCount();
$posts = $statment->fetchAll();
?>



<?php if($page == "All")
{ ?>

<div class="card ">
  

  <a href="?page=addposts" type="button" class="btn btn-secondary soso mt-5">Add new post</a> 


  <div class="card-body">
    

  <div class="card-header">
 Post Mangement <span class="badge badge-primary"><?php echo $postCount;?></span>
  </div>


  <table class="table table-light table-hover table-striped table-bordered text-center">
  <thead>
    <tr>
      <th scope="col">ID</th>

      <th scope="col">Image</th>

      <th scope="col">Title</th>
      <th scope="col">Description</th>
      <th scope="col">Status</th>
      <th scope="col">Catogeres_id</th>
      <th scope="col">User_id</th>

      <th scope="col">operation</th>


    </tr>
  </thead>
  <tbody>
      <?php
      if($postCount>0){
          foreach($posts as $post){

          

      
      ?>
    <tr>
      <th scope="row"><?php echo $post['id']?></th>
    
    <!-- <=========================> -->
    <td>
         <?php
          echo "<a target='_blank' href='uploads/posts/".$post['img']."'>";
          echo "<img style='width:60px;height:60px;border-radius:10px' src='uploads/posts/".$post['img']."'>";
          echo "</a>";
        ?> 
      </td>
    <!-- <==========================> -->
    
      <td><?php echo $post['title']?></td>
      <td><?php echo $post['description']?></td>
      <td><?php
               if($post['status']== 0){
                 echo '<span class="badge bg-info">Pending</span>';
               }else{
                echo '<span class="badge bg-success">Approved</span>';

               }
              ?>
              </td>
     
    
      <td><?php echo $post['catogery_id']?></td>
      <td><?php echo $post['user_id']?></td>

     
      <td>
          <a href='?page=showpost&postid=<?php echo $post['id']?>' class="btn btn-primary">
          <i class="fas fa-eye"></i>
          </a>

          <a href='posts.php?page=delete&postid=<?php echo $post['id']?>'class="btn btn-danger" >
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


<?php
  }
  elseif($page=="showpost"){
  
  $postid='';
  if(isset($_GET['postid'])&& is_numeric($_GET['postid'])) {
    $postid= intval($_GET['postid']);
  }else{
    $postid = '';
  }
  
  $statment = $connection-> prepare("SELECT * FROM posts WHERE id = ?");
  $statment->execute(array($postid));
  $postCount= $statment->rowCount();
  if($postCount > 0){
    $postInfo = $statment ->fetch();
  }
  ?>
  
  
  <!-- start html code  -->
  
  <div class="container-fluid card">
  <h1 class="text-center  mt-2"> Edit posts</h1>
    
      <div class="row">
        <div class="col-md-6 inall">
  
  
        <form  class="mt-3 mb-5 f_add" method="post" action="?page=update">
      <label>title</label>
      <input type="text" name="title" class="form-control" value="<?php echo  $postInfo ['title'] ; ?>">
      <br>
      <label>description</label>
      <input type="text" name="description" class="form-control" value="<?php echo  $postInfo ['description'] ; ?>" />
      <br>
  <!-- <========================> -->
    <!-- <========================> -->
  <!-- <========================> -->

  <label>Post Image </label>
        <input type="file" name="uImage" class="form-control" value="<?php echo $postInfo['img'] ; ?>" /> 
        <br>
  <!-- <========================> -->
  <!-- <========================> -->
  <!-- <============================> -->
        <label class="mr-2">Status</label>
          <input 
          <?php
              if($postInfo['status']==='0'){
              echo 'checked';
            }else{
              echo '';
            }
            ?>
          type="radio" name="status" value="0"/>pending
          <input
          <?php
              if($postInfo['status']==='1'){
              echo 'checked';
            }else{
              echo '';
            }
            ?>
          type="radio" name="status" value="1"/>Approved
          <br>
     <!-- <=====================> -->
     <!-- دا الجزء الخاص بالكاتوجري زي ما احنا بنعمله  -->
     <label>Category</label>
        <select name="category_id" class="form-control">
         <option readonly>--Choose category--</option>
         <?php

         $postCates=$connection->prepare('SELECT * FROM catogeries');
         $postCates->execute();
         $allCategories=$postCates->fetchAll();  // السطر ده عشان امسك كل الي جاي من الكاتوجريز 
         foreach($allCategories as $category){
         echo '<option value="'.$category['id'].'"'. '>'.$category['title'].'</option>';  //افهم السطر ده بعد التنفيذ
         }
         
    ?> 
    </select>
     <!-- <======================> -->
<!-- الجزء االخاص باليوزر عشان اختار الاي دي  -->

<br>
     <label>Publisher</label>
        <select name="user_id" class="form-control">
         <option readonly>--Choose user--</option>
         <?php
         $postUser=$connection->prepare('SELECT * FROM users');
         $postUser->execute();
         $allUsers=$postUser->fetchAll(); //  كان فيه خطا هناااا) برده عشان امسك كا عناصر ليوزرر )
         foreach($allUsers as $user){
         echo '<option value="'.$user['id'].'">'.$user['username'].'</option>';
         }
         
         ?>
         
        </select>
     <!-- <======================> -->
        <br>
        <input type="hidden" name="postid" value="<?php echo $postInfo['id'] ; ?>"/>  <!-- هنا البوست اي دي  -->
   
      <input type="submit" class="btn btn-danger" name="save-post" value="Save" />
    </form>
        </div>
      </div>
    </div>
  
  
  <!-- end html code  -->
  
  
  <!-- <=================> -->
  <!-- <=================> -->
  <!-- <=================> -->
  
  <?php 
  }
  elseif($page=="delete")
  {


$postid='';
if(isset($_GET['postid'])&& is_numeric($_GET['postid'])) {
  $postid= intval($_GET['postid']);
}else{
  echo"no data";
}

 
$check = $connection->prepare("SELECT* FROM  posts where id=?");
$check->execute(array($postid));
$rows=$check->rowCount();

if($rows > 0 ){
$delstatment = $connection->prepare('DELETE FROM posts where id=?');
$delstatment->execute(array($postid));
$delrows=$delstatment->rowCount();

if($delrows > 0) {
  echo "<div class='alert alert-danger m-auto w-50'>post has been deleted successfully</div>";

  header('refresh:3;url=posts.php');
  exit();
}

}

}
elseif($page == 'addposts'){

?>

<div class="card">
  <div class="inall">
    <h1 class="text-center">Add New Post</h1>
    <div class="row">
      <!-- <div class="col-md-6"> -->
      <form action="?page=saveposts" class="mt-3 mb-5 f_add " method="post" enctype="multipart/form-data" style="width:950px,margin-right:100px;" >
      <label>Title</label>
      <input type="text" name="title" class="form-control" placeholder="Enter the title..  "/>
      <br>
      <label>Description</label>
      <textarea name="description"  cols="5" rows="2" class="form-control" placeholder="Enter post description..  "></textarea>
      <br>


      

      <br>
      <label>Post Image </label>
        <input type="file" name="postImage" class="form-control"  />
        <br>




      <!-- <label>Post Image </label>
        <input type="file" name="postImage" class="form-control"  />
        <br> -->
        <label class="mr-2">Status</label>
        <input type="radio" name="status" value="0" class="radio">pending
        <input type="radio" name="status" value="1">Approved
        <br>

        <!-- the part of catogery   -->
        <label>Category</label>
        <select name="category_id" class="form-control">
         <option readonly>--Choose category--</option>
         <?php
         $postCates=$connection->prepare('SELECT * FROM catogeries');
         $postCates->execute();
         $allCategories=$postCates->fetchAll();
         foreach($allCategories as $category){
         echo '<option value="'.$category['id'].'">'.$category['title'].'</option>';
         }
         
         ?>
         
        </select><br>
        <!-- the part of  user  -->
        <label>Publisher</label>
        <select name="user_id" class="form-control">
         <option readonly>--Choose user--</option>
         <?php
         $postUser=$connection->prepare('SELECT * FROM users');
         $postUser->execute();
         $allUsers=$postUser->fetchAll();
         foreach($allUsers as $user){
         echo '<option value="'.$user['id'].'">'.$user['username'].'</option>';
         }
         
         ?>
         
        </select><br>
      <input type="submit" class="btn btn-danger" name="save-post" value="Save" />
    </form>
  
      </div>
    </div>
    
    </div>
        <!-- </div> -->




<?php
}
elseif($page=='saveposts'){

  if($_SERVER['REQUEST_METHOD']=='POST'){
    if(isset($_POST['save-post'])){


      $postFromErrors = array();


      $title = $_POST['title'];
      $description =$_POST['description'];
      $status = $_POST['status'];
      $user_id =$_POST['user_id'];
      $catogery_id =$_POST['category_id'];
      //image
      $imageName = $_FILES['postImage']['name'];//name
      $imageSize = $_FILES['postImage']['size'];//size
      $imageTmp = $_FILES['postImage']['tmp_name'];//temporary name
      $imageType = $_FILES['postImage']['type'];//type

      $imageExtension1 = explode('.' , $imageName); //sperate
      $imageExtension2 = strtolower(end($imageExtension1));

      $allowedExtensions = array("jpeg","jpg","png","gif","svg");




     //validation



      if(!empty($title)){
        $title = filter_var($title , FILTER_SANITIZE_STRING);
      }else{
        $postFromErrors[] ="Title is required";
      }
      if(!empty($description)){
        $description = filter_var($description , FILTER_SANITIZE_EMAIL);
      }else{
        $postFromErrors[] ="Description is required";
      }
      if(!empty($user_id)){
        $user_id = $user_id;
      }else{
        $postFromErrors[] ="UseId is required";
      }
      if(!empty($catogery_id)){
        $catogery_id= filter_var($catogery_id , FILTER_SANITIZE_STRING);
      }else{
        $postFromErrors[] ="CategoryId is required";
      }

      if(!in_array($imageExtension2 ,$allowedExtensions)){
        $postFromErrors[] ="this extension is not allowed to upload";
      }

      if($imageSize > 1048576){
        $postFromErrors[] ="this image is greater than 1MG";
      }


 // check the errors


      if(empty($postFromErrors)){
         $finalImage = rand(0 , 10000)."_" .$imageName ;
         move_uploaded_file($imageTmp , "uploads/posts/".$finalImage);
        $stmt = $connection->
        prepare('INSERT INTO posts(`title` , `description`,`img` , `status` , `user_id` , `catogery_id` , `created_at`)
        VALUES (:ztitle , :zdescription , :zimg , :zstatus , :zuser_id , :zcatogery_id , now())
        ');
        $stmt->execute(array(
          'ztitle' => $title ,
          'zdescription' => $description ,
          'zimg' => $finalImage ,
          'zstatus' => $status,
          'zuser_id' => $user_id,
          'zcatogery_id' => $catogery_id
        ));

        if($stmt -> rowCount() >0){
          echo "<div class='alert alert-success m-auto w-50'>Post has been Created successfully</div>";
        header("refresh:3;url=posts.php");
        exit();
        }
      }else {
        echo 'There are errors';
        // print_r($postFromErrors);
      }

}
}
}
elseif($page=="update")
{

  if($_SERVER['REQUEST_METHOD']=='POST'){
    $title = $_POST['title'];
    $description = $_POST['description'];
    $postid = $_POST['postid'];
    $status = $_POST['status'];

    $category_id = $_POST['category_id'];
    $user_id = $_POST['user_id'];
    

    $updatepost = $connection->
    prepare('UPDATE posts SET `title`= ?,`description`= ?,`status`= ?,`catogery_id`=?,`user_id`=?,`updated_at`= now() WHERE id= ? ;');
    $updatepost ->execute(array($title,$description,$status,$category_id,$user_id,$postid ));
    $updaterow =$updatepost->rowCount();
    if($updaterow > 0){
      echo "<div class='alert alert-success m-auto w-50'>Post has been Updated successfully</div>";
        header("refresh:3;url=posts.php");
        exit();
    }
  }
}

?>








<?php
     include("includes/templets/footer.php");
?>

