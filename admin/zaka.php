<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['add_zakah'])){

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

   $select_zaka = $conn->prepare("SELECT * FROM `zaka` WHERE name = ?");
   $select_zaka->execute([$name]);

   if($select_zaka->rowCount() > 0){
      $message[] = 'zakat name already exist!';
   }else{

      $insert_zaka = $conn->prepare("INSERT INTO `zaka`(name, description, goal_amount, image) VALUES(?,?,?,?)");
      $insert_zaka->execute([$name, $description, $goal_amount, $image]);

      if($insert_zaka){
         if($image_size_01 > 2000000 ){
            $message[] = 'image size is too large!';
         }else{
            move_uploaded_file($image_tmp_name_01, $image_folder_01);
            $message[] = 'new zakat added!';
         }

      }

   }  

};

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_zaka_image = $conn->prepare("SELECT * FROM `zaka` WHERE id = ?");
   $delete_zaka_image->execute([$delete_id]);
   $fetch_delete_image = $delete_zaka_image->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_img/'.$fetch_delete_image['image']);
   $delete_zaka = $conn->prepare("DELETE FROM `zaka` WHERE id = ?");
   $delete_zaka->execute([$delete_id]);
   $delete_discharge = $conn->prepare("DELETE FROM `discharge` WHERE pid = ?");
   $delete_discharge->execute([$delete_id]);
   $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE pid = ?");
   $delete_wishlist->execute([$delete_id]);
   header('location:zaka.php');
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Zakat</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="add-projects">

   <h1 class="heading">Add Zakat</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
            <span>zakat Name (required)</span>
            <input type="text" class="box" required maxlength="100" placeholder="enter zakat name" name="name">
         </div>
         <div class="inputBox">
            <span>zakat goal amount (required)</span>
            <input type="number" min="0" class="box" required max="9999999999" placeholder="enter zaka goal amount" onkeypress="if(this.value.length == 10) return false;" name="goal_amount">
         </div>
        <div class="inputBox">
            <span>Image (required)</span>
            <input type="file" name="image" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
        </div>
         <div class="inputBox">
            <span>zakat description (required)</span>
            <textarea name="description" placeholder="enter zaka description" class="box" required maxlength="500" cols="30" rows="10"></textarea>
         </div>
      </div>
      
      <input type="submit" value="add zaka" class="btn" name="add_zakah">
   </form>

</section>

<section class="show-projects">

   <h1 class="heading">zakat Added.</h1>

   <div class="box-container">

   <?php
      $select_zaka = $conn->prepare("SELECT * FROM `zaka`");
      $select_zaka->execute();
      if($select_zaka->rowCount() > 0){
         while($fetch_zaka = $select_zaka->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <div class="box">
      <img src="../uploaded_img/<?= $fetch_zaka['image']; ?>" alt="">
      <div class="name"><?= $fetch_zaka['name']; ?></div>
      <div class="goal-amount">$<span><?= $fetch_zaka['goal_amount']; ?></span></div>
      <div class="description"><span><?= $fetch_zaka['description']; ?></span></div>
      <div class="flex-btn">
         <a href="update_zaka.php?update=<?= $fetch_zaka['id']; ?>" class="option-btn">update</a>
         <a href="zaka.php?delete=<?= $fetch_zaka['id']; ?>" class="delete-btn" onclick="return confirm('delete this zakat?');">delete</a>
      </div>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">no zakat added yet!</p>';
      }
   ?>
   
   </div>

</section>








<script src="../js/admin_script.js"></script>
   
</body>
</html>