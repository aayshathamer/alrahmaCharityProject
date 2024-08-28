<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:user_login.php');
   exit;
}

include 'components/wishlist_discharge.php';

if(isset($_POST['delete'])){
   $wishlist_id = $_POST['wishlist_id'];
   $delete_wishlist_item = $conn->prepare("DELETE FROM `wishlist` WHERE id = ?");
   $delete_wishlist_item->execute([$wishlist_id]);
}

if(isset($_GET['delete_all'])){
   $delete_wishlist_item = $conn->prepare("DELETE FROM `wishlist` WHERE user_id = ?");
   $delete_wishlist_item->execute([$user_id]);
   header('location:wishlist.php');
   exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Wishlist</title>
   
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
         background-color: #4caf50;
         width: 0%;
      }
   </style>

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="projects">

   <h3 class="heading">Your Wishlist</h3>

   <div class="box-container">

   <?php
      $grand_total = 0;
      $select_wishlist = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
      $select_wishlist->execute([$user_id]);
      if($select_wishlist->rowCount() > 0){
         while($fetch_wishlist = $select_wishlist->fetch(PDO::FETCH_ASSOC)){
            // Fetch project details
            $select_project = $conn->prepare("SELECT * FROM `projects` WHERE id = ?");
            $select_project->execute([$fetch_wishlist['pid']]);
            $project = $select_project->fetch(PDO::FETCH_ASSOC);
            
            $goal_amount = $project['goal_amount'];
            $current_amount = $project['current_amount'];
            $progress_percentage = ($goal_amount > 0) ? ($current_amount / $goal_amount) * 100 : 0;

            $grand_total += $fetch_wishlist['user_donation'];
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= htmlspecialchars($fetch_wishlist['pid']); ?>">
      <input type="hidden" name="wishlist_id" value="<?= htmlspecialchars($fetch_wishlist['id']); ?>">
      <input type="hidden" name="name" value="<?= htmlspecialchars($fetch_wishlist['name']); ?>">
      <input type="hidden" name="goal_amount" value="<?= htmlspecialchars($goal_amount); ?>">
      <input type="hidden" name="image" value="<?= htmlspecialchars($fetch_wishlist['image']); ?>">
      <a href="quick_view.php?pid=<?= htmlspecialchars($fetch_wishlist['pid']); ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= htmlspecialchars($fetch_wishlist['image']); ?>" alt="">
      <div class="name"><?= htmlspecialchars($fetch_wishlist['name']); ?></div>
      <div class="description"><?= htmlspecialchars($project['description']); ?></div>
      <div class="goal-amount">Goal: $<?= htmlspecialchars($goal_amount); ?></div>
      <div class="current-amount">Current: $<?= htmlspecialchars($current_amount); ?></div>
      <div class="progress-bar">
         <div class="progress-bar-fill" style="width: <?= $progress_percentage; ?>%;"></div>
      </div>
      <div class="donation-amount">
         <label for="user_donation">Your Donation: </label>
         <input type="number" name="user_donation" class="user_donation" min="1" max="<?= $goal_amount - $current_amount ?>" required value="<?= htmlspecialchars($fetch_wishlist['user_donation']); ?>">
      </div>
      <input type="submit" value="Add to Discharge" class="btn" name="add_to_discharge">
      <input type="submit" value="Delete Item" onclick="return confirm('delete this from wishlist?');" class="delete-btn" name="delete">
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">Your wishlist is empty</p>';
   }
   ?>
   </div>

   <div class="wishlist-total">
      <p>Grand Total : <span>$<?= htmlspecialchars($grand_total); ?>/-</span></p>
      <a href="contribute.php" class="option-btn">Continue Contributing</a>
      <a href="wishlist.php?delete_all" class="delete-btn <?= ($grand_total > 1)?'':'disabled'; ?>" onclick="return confirm('delete all from wishlist?');">Delete All Items</a>
   </div>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>

