<?php

include 'components/connect.php';

session_start();

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

include 'components/wishlist_discharge.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Search Page</title>
   
   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/style.css">

   <style>
      /* CSS for progress bar */
      .progress-bar {
         width: 100%;
         background-color: #f3f3f3;
         margin-top: 10px;
      }
      .progress-bar-fill {
         height: 20px;
         background-color: #1E90FF;
         width: 0%;
      }
   </style>

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="search-form">
   <form action="" method="post">
      <input type="text" name="search_box" placeholder="search here..." maxlength="100" class="box" required>
      <button type="submit" class="fas fa-search" name="search_btn"></button>
   </form>
</section>

<section class="projects" style="padding-top: 0; min-height:100vh;">

   <div class="box-container">

   <?php
     if(isset($_POST['search_box']) OR isset($_POST['search_btn'])){
     $search_box = $_POST['search_box'];
     $select_projects = $conn->prepare("SELECT * FROM `projects` WHERE name LIKE ?"); 
     $select_projects->execute(['%' . $search_box . '%']);
     if($select_projects->rowCount() > 0){
      while($fetch_project = $select_projects->fetch(PDO::FETCH_ASSOC)){
         $goal_amount = $fetch_project['goal_amount'];
         $current_amount = $fetch_project['current_amount'];
         $progress_percentage = ($goal_amount > 0) ? ($current_amount / $goal_amount) * 100 : 0;
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= htmlspecialchars($fetch_project['id']); ?>">
      <input type="hidden" name="name" value="<?= htmlspecialchars($fetch_project['name']); ?>">
      <input type="hidden" name="goal_amount" value="<?= htmlspecialchars($goal_amount); ?>">
      <input type="hidden" name="image" value="<?= htmlspecialchars($fetch_project['image']); ?>">
      <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
      <a href="quick_view.php?pid=<?= htmlspecialchars($fetch_project['id']); ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= htmlspecialchars($fetch_project['image']); ?>" alt="">
      <div class="name"><?= htmlspecialchars($fetch_project['name']); ?></div>
      <div class="description"><?= htmlspecialchars($fetch_project['description']); ?></div>
      <div class="goal-amount">Goal: $<?= htmlspecialchars($goal_amount); ?></div>
      <div class="current-amount">Current: $<?= htmlspecialchars($current_amount); ?></div>
      <div class="progress-bar">
         <div class="progress-bar-fill" style="width: <?= $progress_percentage; ?>%;"></div>
      </div>
      <div class="donation-amount">
         <label for="user_donation">Your Donation: </label>
         <input type="number" name="user_donation" class="user_donation" min="1" max="<?= $goal_amount - $current_amount ?>" required>
      </div>
      <input type="submit" value="Add to Discharge" class="btn" name="add_to_discharge">
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">No projects found!</p>';
      }
   }
   ?>

   </div>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
