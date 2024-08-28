<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['add_benefactors'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $goal_amount = $_POST['goal_amount'];
   $goal_amount = filter_var($goal_amount, FILTER_SANITIZE_STRING);
   $description = $_POST['description'];
   $description = filter_var($description, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size_01 = $_FILES['image']['size'];
   $image_tmp_name_01 = $_FILES['image']['tmp_name'];
   $image_folder_01 = '../uploaded_img/'.$image;

   $select_benefactor = $conn->prepare("SELECT * FROM `benefactor` WHERE name = ?");
   $select_benefactor->execute([$name]);

   if($select_benefactor->rowCount() > 0){
      $message[] = 'benefactor name already exist!';
   }else{

      $insert_benefactor = $conn->prepare("INSERT INTO `benefactor`(name, description, goal_amount, image) VALUES(?,?,?,?)");
      $insert_benefactor->execute([$name, $description, $goal_amount, $image]);

      if($insert_benefactor){
         if($image_size_01 > 2000000 ){
            $message[] = 'image size is too large!';
         }else{
            move_uploaded_file($image_tmp_name_01, $image_folder_01);
            $message[] = 'new benefactor added!';
         }

      }

   }  

};

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_benefactor_image = $conn->prepare("SELECT * FROM `benefactor` WHERE id = ?");
   $delete_benefactor_image->execute([$delete_id]);
   $fetch_delete_image = $delete_benefactor_image->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_img/'.$fetch_delete_image['image']);
   $delete_benefactor = $conn->prepare("DELETE FROM `benefactor` WHERE id = ?");
   $delete_benefactor->execute([$delete_id]);
   $delete_discharge = $conn->prepare("DELETE FROM `discharge` WHERE pid = ?");
   $delete_discharge->execute([$delete_id]);
   $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE pid = ?");
   $delete_wishlist->execute([$delete_id]);
   header('location:benefactor.php');
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>benefactor</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="add-projects">

   <h1 class="heading">Add Benefactor</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
            <span>benefactor Name (required)</span>
            <input type="text" class="box" required maxlength="100" placeholder="enter benefactor name" name="name">
         </div>
         <div class="inputBox">
            <span>benefactor goal amount (required)</span>
            <input type="number" min="0" class="box" required max="9999999999" placeholder="enter benefactor goal amount" onkeypress="if(this.value.length == 10) return false;" name="goal_amount">
         </div>
        <div class="inputBox">
            <span>Image (required)</span>
            <input type="file" name="image" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
        </div>
         <div class="inputBox">
            <span>benefactor description (required)</span>
            <textarea name="description" placeholder="enter benefactor description" class="box" required maxlength="500" cols="30" rows="10"></textarea>
         </div>
      </div>
      
      <input type="submit" value="add benefactor" class="btn" name="add_benefactors">
   </form>

</section>

<section class="show-projects">

   <h1 class="heading">benefactor Added.</h1>

   <div class="box-container">

   <?php
      $select_benefactor = $conn->prepare("SELECT * FROM `benefactor`");
      $select_benefactor->execute();
      if($select_benefactor->rowCount() > 0){
         while($fetch_benefactor = $select_benefactor->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <div class="box">
      <img src="../uploaded_img/<?= $fetch_benefactor['image']; ?>" alt="">
      <div class="name"><?= $fetch_benefactor['name']; ?></div>
      <div class="goal-amount">$<span><?= $fetch_benefactor['goal_amount']; ?></span></div>
      <div class="description"><span><?= $fetch_benefactor['description']; ?></span></div>
      <div class="flex-btn">
         <a href="update_benefactor.php?update=<?= $fetch_benefactor['id']; ?>" class="option-btn">update</a>
         <a href="benefactor.php?delete=<?= $fetch_benefactor['id']; ?>" class="delete-btn" onclick="return confirm('delete this benefactor?');">delete</a>
      </div>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">no benefactor added yet!</p>';
      }
   ?>
   
   </div>

</section>








<script src="../js/admin_script.js"></script>
   
</body>
</html>