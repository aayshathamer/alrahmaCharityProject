<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
   exit();
}

if (isset($_GET['delete'])) {

   $delete_id = $_GET['delete'];
   $delete_project_image = $conn->prepare("SELECT image FROM `projects` WHERE id = ?");
   $delete_project_image->execute([$delete_id]);
   $fetch_delete_image = $delete_project_image->fetch(PDO::FETCH_ASSOC);

   if ($fetch_delete_image) {
      unlink('../uploaded_img/'.$fetch_delete_image['image']);
   }

   $delete_project = $conn->prepare("DELETE FROM `projects` WHERE id = ?");
   $delete_project->execute([$delete_id]);

   $delete_discharge = $conn->prepare("DELETE FROM `discharge` WHERE pid = ?");
   $delete_discharge->execute([$delete_id]);

   $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE pid = ?");
   $delete_wishlist->execute([$delete_id]);

   header('location:completed_projects.php');
   exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Completed Projects</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="show-projects">

   <h1 class="heading">Completed Projects</h1>

   <div class="box-container">

   <?php
      $select_projects = $conn->prepare("SELECT * FROM `projects` WHERE status = 'completed'");
      $select_projects->execute();
      if($select_projects->rowCount() > 0){
         while($fetch_projects = $select_projects->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <div class="box">
      <img src="../uploaded_img/<?= htmlspecialchars($fetch_projects['image']); ?>" alt="">
      <div class="name"><?= htmlspecialchars($fetch_projects['name']); ?></div>
      <div class="description"><span><?= htmlspecialchars($fetch_projects['description']); ?></span></div>
      <div class="goal-amount">Goal: $<span><?= htmlspecialchars($fetch_projects['goal_amount']); ?></span></div>
      <div class="current-amount">Current: $<span><?= htmlspecialchars($fetch_projects['current_amount']); ?></span></div>
      <div class="flex-btn">
      <a href="update_project.php?update=<?= $fetch_projects['id']; ?>" class="option-btn">Update</a>
         <a href="completed_projects.php?delete=<?= $fetch_projects['id']; ?>" class="delete-btn" onclick="return confirm('Delete this project?');">Delete</a>
      </div>
   </div>
   
   <?php
         }
      } else {
         echo '<p class="empty">No completed projects yet!</p>';
      }
   ?>
   
   </div>

</section>

<script src="../js/admin_script.js"></script>
   
</body>
</html>
