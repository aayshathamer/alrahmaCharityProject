<?php

include 'components/connect.php';

session_start();

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';



// Handle form submission to add to discharge
if (isset($_POST['add_to_discharge'])) {
    $pid = $_POST['pid'];
    $name = $_POST['name'];
    $goal_amount = $_POST['goal_amount'];
    $image = $_POST['image'];
    $user_donation = $_POST['user_donation'];

    // Check if the item is already in the discharge table for the current user
    $check_discharge = $conn->prepare("SELECT * FROM `discharge` WHERE user_id = ? AND pid = ?");
    $check_discharge->execute([$user_id, $pid]);

    if ($check_discharge->rowCount() > 0) {
        $message[] = 'This project is already added to discharge!';
    } else {
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
                // Insert into discharge table without updating current amount in projects table
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
   <title>AlRahma Charity</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   
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

<div class="home-bg">

<section class="home">

   <div class="swiper home-slider">
   
   <div class="swiper-wrapper">

      <div class="swiper-slide slide">
         <div class="image">
            <img src="images/349.jpeg" alt="">
         </div>
         <div class="content">
            <span>Upto 200 projects</span>
            <h3>Latest Sadaqah Project</h3>
            <a href="sadaqah.php?sadaqah=sadaqah" class="btn">Donate Now</a>
         </div>
      </div>

      <div class="swiper-slide slide">
         <div class="image">
            <img src="images/543.jpg" alt="">
         </div>
         <div class="content">
            <span>Upto 500 projects</span>
            <h3>Latest Zakat Project</h3>
            <a href="zakat.php?zakat=zakat" class="btn">Donate Now</a>
         </div>
      </div>

      <div class="swiper-slide slide">
         <div class="image">
            <img src="images/589.jpeg" alt="">
         </div>
         <div class="content">
            <span>upto 700 projects</span>
            <h3>Benefactor Donations</h3>
            <a href="benefactor.php?benefactor=benefactor" class="btn">Donate Now</a>
         </div>
      </div>

   </div>

      <div class="swiper-pagination"></div>

   </div>

</section>

</div>

<section class="category">

   <h1 class="heading">Donate Now</h1>

   <div class="swiper category-slider">

   <div class="swiper-wrapper">

   <a  class="swiper-slide slide">
   <img src="images/4477610.png" alt="">
      <h2>Zaad: 4133006</h2>
      <h2>EDahab: 52010</h2>
   </a>

   <a href="sadaqah.php?sadaqah=sadaqah" class="swiper-slide slide">
      <img src="images/23.jpg" alt="">
      <h3>Sadaqah</h3>
   </a>

   <a href="zakat.php?zakat=zakat" class="swiper-slide slide">
      <img src="images/24.jpg" alt="">
      <h3>Zakat</h3>
   </a>

   <a href="benefactor.php?benefactor=benefactor" class="swiper-slide slide">
      <img src="images/64.jpg" alt="">
      <h3>Benefactor</h3>
   </a>

   


   </div>

   

   </div>

</section>

<section class="home-projects">

   <h1 class="heading">Latest projects</h1>

   <div class="swiper projects-slider">

   <div class="swiper-wrapper">

   <?php
     $select_projects = $conn->prepare("SELECT * FROM `projects` WHERE status = 'ongoing' LIMIT 6"); 
     $select_projects->execute();
     if ($select_projects->rowCount() > 0) {
        while ($fetch_project = $select_projects->fetch(PDO::FETCH_ASSOC)) {
            $goal_amount = $fetch_project['goal_amount'];
            $current_amount = $fetch_project['current_amount'];
            $progress_percentage = ($goal_amount > 0) ? ($current_amount / $goal_amount) * 100 : 0;
   ?>
   <form action="" method="post" class="swiper-slide slide">
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
     } else {
         echo '<p class="empty">No projects found!</p>';
     }
   ?>

   </div>

   <div class="swiper-pagination"></div>

   </div>

</section>

<?php include 'components/footer.php'; ?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<script src="js/script.js"></script>

<script>

var swiper = new Swiper(".home-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
    },
});

 var swiper = new Swiper(".category-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      0: {
         slidesPerView: 2,
       },
      650: {
        slidesPerView: 3,
      },
      768: {
        slidesPerView: 4,
      },
      
   },
});

var swiper = new Swiper(".projects-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      550: {
        slidesPerView: 2,
      },
      768: {
        slidesPerView: 2,
      },
      1024: {
        slidesPerView: 3,
      },
   },
});

</script>

</body>
</html>
