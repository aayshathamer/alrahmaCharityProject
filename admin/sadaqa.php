<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['add_sadaqah'])){

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

   $select_sadaqa = $conn->prepare("SELECT * FROM `sadaqa` WHERE name = ?");
   $select_sadaqa->execute([$name]);

   if($select_sadaqa->rowCount() > 0){
      $message[] = 'sadaqa name already exist!';
   }else{

      $insert_sadaqa = $conn->prepare("INSERT INTO `sadaqa`(name, description, goal_amount, image) VALUES(?,?,?,?)");
      $insert_sadaqa->execute([$name, $description, $goal_amount, $image]);

      if($insert_sadaqa){
         if($image_size_01 > 2000000 ){
            $message[] = 'image size is too large!';
         }else{
            move_uploaded_file($image_tmp_name_01, $image_folder_01);
            $message[] = 'new sadaqa added!';
         }

      }

   }  

};

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_sadaqa_image = $conn->prepare("SELECT * FROM `sadaqa` WHERE id = ?");
   $delete_sadaqa_image->execute([$delete_id]);
   $fetch_delete_image = $delete_sadaqa_image->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_img/'.$fetch_delete_image['image']);
   $delete_sadaqa = $conn->prepare("DELETE FROM `sadaqa` WHERE id = ?");
   $delete_sadaqa->execute([$delete_id]);
   $delete_discharge = $conn->prepare("DELETE FROM `discharge` WHERE pid = ?");
   $delete_discharge->execute([$delete_id]);
   $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE pid = ?");
   $delete_wishlist->execute([$delete_id]);
   header('location:sadaqa.php');
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>sadaqa</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="add-sadaqa">

   <h1 class="heading">Add Sadaqah</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
            <span>sadaqah Name (required)</span>
            <input type="text" class="box" required maxlength="100" placeholder="enter sadaqa name" name="name">
         </div>
         <div class="inputBox">
            <span>sadaqah goal amount (required)</span>
            <input type="number" min="0" class="box" required max="9999999999" placeholder="enter sadaqa goal amount" onkeypress="if(this.value.length == 10) return false;" name="goal_amount">
         </div>
        <div class="inputBox">
            <span>Image (required)</span>
            <input type="file" name="image" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
        </div>
         <div class="inputBox">
            <span>sadaqah description (required)</span>
            <textarea name="description" placeholder="enter sadaqa description" class="box" required maxlength="500" cols="30" rows="10"></textarea>
         </div>
      </div>
      
      <input type="submit" value="add sadaqa" class="btn" name="add_sadaqah">
   </form>

</section>

<section class="show-sadaqa">

   <h1 class="heading">sadaqah Added.</h1>

   <div class="box-container">

   <?php
      $select_sadaqa = $conn->prepare("SELECT * FROM `sadaqa`");
      $select_sadaqa->execute();
      if($select_sadaqa->rowCount() > 0){
         while($fetch_sadaqa = $select_sadaqa->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <div class="box">
      <img src="../uploaded_img/<?= $fetch_sadaqa['image']; ?>" alt="">
      <div class="name"><?= $fetch_sadaqa['name']; ?></div>
      <div class="goal-amount">$<span><?= $fetch_sadaqa['goal_amount']; ?></span></div>
      <div class="description"><span><?= $fetch_sadaqa['description']; ?></span></div>
      <div class="flex-btn">
         <a href="update_sadaqa.php?update=<?= $fetch_sadaqa['id']; ?>" class="option-btn">update</a>
         <a href="sadaqa.php?delete=<?= $fetch_sadaqa['id']; ?>" class="delete-btn" onclick="return confirm('delete this sadaqa?');">delete</a>
      </div>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">no sadaqa added yet!</p>';
      }
   ?>
   
   </div>

</section>








<script src="../js/admin_script.js"></script>
   
</body>
</html>