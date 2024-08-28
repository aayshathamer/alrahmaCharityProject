<?php

include 'components/connect.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    $user_id = '';
    // Redirect the user to the login page if not logged in
    header('location:user_login.php');
    exit;
} else {
    $user_id = $_SESSION['user_id'];
}


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
            // Check if the item is already in the discharge table
            $check_discharge = $conn->prepare("SELECT * FROM `discharge` WHERE user_id = ? AND pid = ?");
            $check_discharge->execute([$user_id, $pid]);

            if ($check_discharge->rowCount() > 0) {
                $message[] = 'This project is already in your discharge list!';
            } else {
                // Insert into discharge table
                $insert_discharge = $conn->prepare("INSERT INTO `discharge` (user_id, pid, name, goal_amount, user_donation, image) VALUES (?, ?, ?, ?, ?, ?)");
                $insert_discharge->execute([$user_id, $pid, $name, $goal_amount, $user_donation, $image]);

                $message[] = 'Added to discharge successfully!';
            }
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
   <title>Contribute</title>
   
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

<section class="projects">

   <h1 class="heading">Latest projects.</h1>

   <div class="box-container">

   <?php
     // Select only ongoing projects
     $select_projects = $conn->prepare("SELECT * FROM `projects` WHERE status = 'ongoing'"); 
     $select_projects->execute();
     if ($select_projects->rowCount() > 0) {
        while ($fetch_project = $select_projects->fetch(PDO::FETCH_ASSOC)) {
            $goal_amount = $fetch_project['goal_amount'];
            $current_amount = $fetch_project['current_amount'];
            $progress_percentage = ($goal_amount > 0) ? ($current_amount / $goal_amount) * 100 : 0;
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= $fetch_project['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_project['name']; ?>">
      <input type="hidden" name="goal_amount" value="<?= $goal_amount; ?>">
      <input type="hidden" name="image" value="<?= $fetch_project['image']; ?>">
      <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
      <a href="quick_view.php?pid=<?= $fetch_project['id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_project['image']; ?>" alt="">
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
     } else {
         echo '<p class="empty">No projects found!</p>';
     }
   ?>

   </div>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
