<?php

include 'components/connect.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    $user_id = '';
    header('location:user_login.php');
    exit;
} else {
    $user_id = $_SESSION['user_id'];
}

include 'components/wishlist_discharge.php';

// Handle form submission to add to discharge
if (isset($_POST['add_to_discharge'])) {
    $pid = $_POST['pid'];
    $name = $_POST['name'];
    $goal_amount = $_POST['goal_amount'];
    $image = $_POST['image'];
    $user_donation = $_POST['user_donation'];

    // Fetch current amount from the database
    $select_project = $conn->prepare("SELECT current_amount, goal_amount FROM `projects` WHERE id = ?");
    $select_project->execute([$pid]);
    $project = $select_project->fetch(PDO::FETCH_ASSOC);

    if ($project) {
        $current_amount = $project['current_amount'];
        $goal_amount = $project['goal_amount'];

        // Check if the donation exceeds the goal amount
        if ($current_amount + $user_donation > $goal_amount) {
            $message[] = 'Donation exceeds the goal amount!';
        } else {
            // Insert into discharge table
            $insert_discharge = $conn->prepare("INSERT INTO `discharge` (user_id, pid, name, goal_amount, user_donation, image) VALUES (?, ?, ?, ?, ?, ?)");
            $insert_discharge->execute([$user_id, $pid, $name, $goal_amount, $user_donation, $image]);

            $message[] = 'Added to discharge successfully!';
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
   <title>Quick View</title>
   
   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="quick-view">

   <h1 class="heading">Quick View</h1>

   <?php
     $pid = $_GET['pid'];
     $select_projects = $conn->prepare("SELECT * FROM `projects` WHERE id = ?"); 
     $select_projects->execute([$pid]);
     if ($select_projects->rowCount() > 0) {
        while ($fetch_project = $select_projects->fetch(PDO::FETCH_ASSOC)) {
            $goal_amount = $fetch_project['goal_amount'];
            $current_amount = $fetch_project['current_amount'];
            $progress_percentage = ($goal_amount > 0) ? ($current_amount / $goal_amount) * 100 : 0;
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= htmlspecialchars($fetch_project['id']); ?>">
      <input type="hidden" name="name" value="<?= htmlspecialchars($fetch_project['name']); ?>">
      <input type="hidden" name="goal_amount" value="<?= htmlspecialchars($goal_amount); ?>">
      <input type="hidden" name="image" value="<?= htmlspecialchars($fetch_project['image']); ?>">
      <div class="row">
         <div class="image-container">
            <div class="main-image">
               <img src="uploaded_img/<?= htmlspecialchars($fetch_project['image']); ?>" alt="">
            </div>
            <div class="sub-image">
               <img src="uploaded_img/<?= htmlspecialchars($fetch_project['image']); ?>" alt="">
            </div>
         </div>
         <div class="content">
            <div class="name"><?= htmlspecialchars($fetch_project['name']); ?></div>
            <div class="description"><?= htmlspecialchars($fetch_project['description']); ?></div>
            <div class="flex">
               <div class="goal_amount">Goal: $<?= htmlspecialchars($goal_amount); ?><span></span></div>
               <div class="current-amount">Current: $<?= htmlspecialchars($current_amount); ?></div>
            </div>
            <div class="progress-bar">
               <div class="progress-bar-fill" style="width: <?= $progress_percentage; ?>%;"></div>
            </div>
            <div class="donation-amount">
               <label for="user_donation">Your Donation: </label>
               <input type="number" name="user_donation" class="user_donation" min="1" max="<?= $goal_amount - $current_amount ?>" required>
            </div>
            <div class="flex-btn">
               <input type="submit" value="Add to Discharge" class="btn" name="add_to_discharge">
               <input class="option-btn" type="submit" name="add_to_wishlist" value="Add to Wishlist">
            </div>
         </div>
      </div>
   </form>
   <?php
        }
     } else {
        echo '<p class="empty">No projects found!</p>';
     }
   ?>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
