<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_POST['update'])){

   $pid = $_POST['pid'];
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $goal_amount = $_POST['goal_amount'];
   $goal_amount = filter_var($goal_amount, FILTER_SANITIZE_STRING);
   $description = $_POST['description'];
   $description = filter_var($description, FILTER_SANITIZE_STRING);

   $update_zaka = $conn->prepare("UPDATE `zaka` SET name = ?, goal_amount = ?, description = ? WHERE id = ?");
   $update_zaka->execute([$name, $goal_amount, $description, $pid]);

   $message[] = 'zakat updated successfully!';

   $old_image = $_POST['old_image'];
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size_01 = $_FILES['image']['size'];
   $image_tmp_name_01 = $_FILES['image']['tmp_name'];
   $image_folder_01 = '../uploaded_img/'.$image;

   if(!empty($image)){
      if($image_size_01 > 2000000){
         $message[] = 'image size is too large!';
      }else{
         $update_image = $conn->prepare("UPDATE `zaka` SET image = ? WHERE id = ?");
         $update_image->execute([$image, $pid]);
         move_uploaded_file($image_tmp_name_01, $image_folder_01);
         unlink('../uploaded_img/'.$old_image);
         $message[] = 'image updated successfully!';
      }
   }

   
   }



?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update zakat</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="update-project">

   <h1 class="heading">Update zakat</h1>

   <?php
      $update_id = $_GET['update'];
      $select_zakas = $conn->prepare("SELECT * FROM `zaka` WHERE id = ?");
      $select_zakas->execute([$update_id]);
      if($select_zakas->rowCount() > 0){
         while($fetch_zakas = $select_zakas->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="pid" value="<?= $fetch_zakas['id']; ?>">
      <input type="hidden" name="old_image" value="<?= $fetch_zakas['image']; ?>">
      <div class="image-container">
         <div class="main-image">
            <img src="../uploaded_img/<?= $fetch_zakas['image']; ?>" alt="">
         </div>
         <div class="sub-image">
            <img src="../uploaded_img/<?= $fetch_zakas['image']; ?>" alt="">
         </div>
      </div>
      <span>Update Name</span>
      <input type="text" name="name" required class="box" maxlength="100" placeholder="enter zakat name" value="<?= $fetch_zakas['name']; ?>">
      <span>Update goal_amount</span>
      <input type="number" name="goal_amount" required class="box" min="0" max="9999999999" placeholder="enter zakat goal_amount" onkeypress="if(this.value.length == 10) return false;" value="<?= $fetch_zakas['goal_amount']; ?>">
      <span>Update description</span>
      <textarea name="description" class="box" required cols="30" rows="10"><?= $fetch_zakas['description']; ?></textarea>
      <span>Update image</span>
      <input type="file" name="image" accept="image/jpg, image/jpeg, image/png, image/webp" class="box">
      <div class="flex-btn">
         <input type="submit" name="update" class="btn" value="update">
         <a href="zaka.php" class="option-btn">Go Back.</a>
      </div>
   </form>
   
   <?php
         }
      }else{
         echo '<p class="empty">no zakat found!</p>';
      }
   ?>

</section>












<script src="../js/admin_script.js"></script>
   
</body>
</html>