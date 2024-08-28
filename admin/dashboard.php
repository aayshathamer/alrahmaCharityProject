<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>

<body>

   <?php include '../components/admin_header.php'; ?>

   <section class="dashboard">

      <h1 class="heading">Dashboard</h1>

      <div class="box-container">

         <div class="box">
            <h3>Welcome!</h3>
            <p><?= $fetch_profile['name']; ?></p>
            <a href="update_profile.php" class="btn">Update Profile</a>
         </div>


         <div class="box">
         <?php
            $total_donations = 0;
            $select_total = $conn->prepare("SELECT SUM(total_amounts) AS total_collected FROM `donations`");
            $select_total->execute();
            if ($select_total->rowCount() > 0) {
               $fetch_total = $select_total->fetch(PDO::FETCH_ASSOC);
               $total_donations = $fetch_total['total_collected'];
            }
            ?>
            <h3><span>$</span><?= $total_donations; ?><span></span></h3>
            <p>donations fund</p>
            <a href="placed_donations.php" class="btn">See donations</a>
         </div>
         
            <?php
            $total_donations = 0;
            $select_total = $conn->prepare("SELECT SUM(total_amounts) AS total_collected FROM `donations`");
            $select_total->execute();
            if ($select_total->rowCount() > 0) {
               $fetch_total = $select_total->fetch(PDO::FETCH_ASSOC);
               $total_donations = $fetch_total['total_collected'];
            }
            ?>

         <div class="box">
            <?php
            $select_donations = $conn->prepare("SELECT * FROM `donations`");
            $select_donations->execute();
            $number_of_donations = $select_donations->rowCount()
            ?>
            <h3><?= $number_of_donations; ?></h3>
            <p>donations Placed</p>
            <a href="placed_donations.php" class="btn">See donations</a>
         </div>

         <div class="box">
            <?php
            $select_projects = $conn->prepare("SELECT * FROM `projects`");
            $select_projects->execute();
            $number_of_projects = $select_projects->rowCount()
            ?>
            <h3><?= $number_of_projects; ?></h3>
            <p>projects added</p>
            <a href="projects.php" class="btn">See projects</a>
         </div>

         <div class="box">
            <?php
            $select_benefactor = $conn->prepare("SELECT * FROM `benefactor`");
            $select_benefactor->execute();
            $number_of_benefactor = $select_benefactor->rowCount()
            ?>
            <h3><?= $number_of_benefactor; ?></h3>
            <p>benefactors added</p>
            <a href="benefactor.php" class="btn">See benefactors</a>
         </div>

         <div class="box">
            <?php
            $select_zaka = $conn->prepare("SELECT * FROM `zaka`");
            $select_zaka->execute();
            $number_of_zaka = $select_zaka->rowCount()
            ?>
            <h3><?= $number_of_zaka; ?></h3>
            <p>zakat added</p>
            <a href="zaka.php" class="btn">See zakat</a>
         </div>

         <div class="box">
            <?php
            $select_sadaqa = $conn->prepare("SELECT * FROM `sadaqa`");
            $select_sadaqa->execute();
            $number_of_sadaqa = $select_sadaqa->rowCount()
            ?>
            <h3><?= $number_of_sadaqa; ?></h3>
            <p>sadaqah added</p>
            <a href="sadaqa.php" class="btn">See sadaqah</a>
         </div>

         <div class="box">
            <?php
            $select_users = $conn->prepare("SELECT * FROM `users`");
            $select_users->execute();
            $number_of_users = $select_users->rowCount()
            ?>
            <h3><?= $number_of_users; ?></h3>
            <p>Normal users</p>
            <a href="users_accounts.php" class="btn">See Users</a>
         </div>

         <div class="box">
            <?php
            $select_admins = $conn->prepare("SELECT * FROM `admins`");
            $select_admins->execute();
            $number_of_admins = $select_admins->rowCount()
            ?>
            <h3><?= $number_of_admins; ?></h3>
            <p>Admin users</p>
            <a href="admin_accounts.php" class="btn">See admins</a>
         </div>

         <div class="box">
            <?php
            $select_messages = $conn->prepare("SELECT * FROM `messages`");
            $select_messages->execute();
            $number_of_messages = $select_messages->rowCount()
            ?>
            <h3><?= $number_of_messages; ?></h3>
            <p>New messages</p>
            <a href="messages.php" class="btn">See messages</a>
         </div>

      </div>

   </section>












   <script src="../js/admin_script.js"></script>

</body>

</html>