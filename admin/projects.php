<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
   exit();
}

if (isset($_POST['add_project'])) {

   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
   $goal_amount = filter_var($_POST['goal_amount'], FILTER_SANITIZE_NUMBER_INT);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size_01 = $_FILES['image']['size'];
   $image_tmp_name_01 = $_FILES['image']['tmp_name'];
   $image_folder_01 = '../uploaded_img/' . time() . '_' . $image;

   $select_projects = $conn->prepare("SELECT * FROM `projects` WHERE name = ?");
   $select_projects->execute([$name]);

   if ($select_projects->rowCount() > 0) {
      $message[] = 'Project name already exists!';
   } else {

      $insert_projects = $conn->prepare("INSERT INTO `projects` (name, description, image, goal_amount, current_amount, status) VALUES (?, ?, ?, ?, 0, 'ongoing')");
      $insert_projects->execute([$name, $description, $image_folder_01, $goal_amount]);

      if ($insert_projects) {
         if ($image_size_01 > 2000000) {
            $message[] = 'Image size is too large!';
         } else {
            move_uploaded_file($image_tmp_name_01, $image_folder_01);
            $message[] = 'New project added!';
         }
      }

   }

}

if (isset($_GET['delete'])) {

   $delete_id = $_GET['delete'];
   $delete_project_image = $conn->prepare("SELECT * FROM `projects` WHERE id = ?");
   $delete_project_image->execute([$delete_id]);
   $fetch_delete_image = $delete_project_image->fetch(PDO::FETCH_ASSOC);

   if ($fetch_delete_image) {
      unlink($fetch_delete_image['image']);
   }

   $delete_project = $conn->prepare("DELETE FROM `projects` WHERE id = ?");
   $delete_project->execute([$delete_id]);

   $delete_discharge = $conn->prepare("DELETE FROM `discharge` WHERE pid = ?");
   $delete_discharge->execute([$delete_id]);

   $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE pid = ?");
   $delete_wishlist->execute([$delete_id]);

   header('location:projects.php');
   exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Projects</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
   <style>
      .progress-bar {
         width: 100%;
         background-color: #f3f3f3;
         margin-top: 10px;
      }
      .progress-bar-fill {
         height: 20px;
         background-color: #1E90FF;
      }
   </style>
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="add-projects">

   <h1 class="heading">Add Project</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
            <span>Project Name (required)</span>
            <input type="text" class="box" required maxlength="100" placeholder="Enter project name" name="name">
         </div>
         <div class="inputBox">
            <span>Goal Amount (required)</span>
            <input type="number" min="0" class="box" required max="9999999999" placeholder="Enter goal amount" name="goal_amount">
         </div>
         <div class="inputBox">
            <span>Image (required)</span>
            <input type="file" name="image" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
         </div>
         <div class="inputBox">
            <span>Project Description (required)</span>
            <textarea name="description" placeholder="Enter project description" class="box" required maxlength="500" cols="30" rows="10"></textarea>
         </div>
      </div>
      
      <input type="submit" value="Add Project" class="btn" name="add_project">
   </form>

</section>

<section class="show-projects">

   <h1 class="heading">Projects Added</h1>

   <div class="box-container">

   <?php
      $select_projects = $conn->prepare("SELECT * FROM `projects` WHERE status = 'ongoing'");
      $select_projects->execute();
      if ($select_projects->rowCount() > 0) {
         while ($fetch_projects = $select_projects->fetch(PDO::FETCH_ASSOC)) { 
   ?>
   <div class="box">
      <img src="<?= htmlspecialchars($fetch_projects['image']); ?>" alt="">
      <div class="name"><?= htmlspecialchars($fetch_projects['name']); ?></div>
      <div class="description"><span><?= htmlspecialchars($fetch_projects['description']); ?></span></div>
      <div class="goal-amount">Goal: $<?= htmlspecialchars($fetch_projects['goal_amount']); ?></div>
      <div class="current-amount">Current: $<span><?= htmlspecialchars($fetch_projects['current_amount']); ?></span></div>
      <div class="progress-bar">
         <div class="progress-bar-fill" style="width: <?= ($fetch_projects['current_amount'] / $fetch_projects['goal_amount']) * 100; ?>%;"></div>
      </div>
      <div class="flex-btn">
         <a href="update_project.php?update=<?= $fetch_projects['id']; ?>" class="option-btn">Update</a>
         <a href="projects.php?delete=<?= $fetch_projects['id']; ?>" class="delete-btn" onclick="return confirm('Delete this project?');">Delete</a>
      </div>
   </div>
   <?php
         }
      } else {
         echo '<p class="empty">No projects added yet!</p>';
      }
   ?>
   
   </div>

</section>

<script src="../js/admin_script.js"></script>
   
</body>
</html>
