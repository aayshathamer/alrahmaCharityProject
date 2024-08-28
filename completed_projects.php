<?php
include 'components/connect.php';

session_start();

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Completed Projects</title>
   
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

      /* CSS for Image Slider */
      .slider {
         position: relative;
         width: 600px; /* Set width based on the image size */
         height: 400px; /* Set height based on the image size */
         margin: 40px auto;
         overflow: hidden;
         border-radius: 12px;
         box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      }
      .slides {
         display: flex;
         transition: transform 0.5s ease-in-out;
      }
      .slides img {
         width: 100%; /* Ensure images fit within the slider */
         height: 100%; /* Ensure images fit within the slider */
         object-fit: contain; /* Ensure the full image is visible without being cropped */
         border-radius: 12px;
      }
      .navigation {
         position: absolute;
         top: 50%;
         width: 100%;
         display: flex;
         justify-content: space-between;
         transform: translateY(-50%);
      }
      .navigation .prev,
      .navigation .next {
         background-color: rgba(0, 0, 0, 0.5);
         border: none;
         color: #fff;
         padding: 10px;
         cursor: pointer;
      }
      .navigation .prev {
         border-radius: 0 10px 10px 0;
      }
      .navigation .next {
         border-radius: 10px 0 0 10px;
      }
   </style>

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<!-- Image Slider Section -->
<section class="slider-section">
   
<h1 class="heading">Completed projects</h1>
<h2 class="subheading">Thanks to your generous donations, Al-Rahma Charity has successfully supported countless individuals and communities in need.</h2>

   <div class="slider">
      <div class="slides">
         <img src="images/09.jpg" alt="Image 1">
         <img src="images/98.jpg" alt="Image 2">
         <img src="images/589.jpeg" alt="Image 3">
         <img src="images/832.jpg" alt="Image 4">
         <img src="images/985.jpg" alt="Image 5">
      </div>
      <div class="navigation">
         <button class="prev">&#10094;</button>
         <button class="next">&#10095;</button>
      </div>
   </div>
</section>

<section class="projects">
<h1 class="heading">Latest projects</h1>

   
   <div class="box-container">
   <?php
     // Select only completed projects
     $select_projects = $conn->prepare("SELECT * FROM `projects` WHERE status = 'completed'"); 
     $select_projects->execute();
     if ($select_projects->rowCount() > 0) {
        while ($fetch_project = $select_projects->fetch(PDO::FETCH_ASSOC)) {
            $goal_amount = $fetch_project['goal_amount'];
            $current_amount = $fetch_project['current_amount'];
            $progress_percentage = ($goal_amount > 0) ? ($current_amount / $goal_amount) * 100 : 0;
   ?>
   <div class="box">
      <img src="uploaded_img/<?= htmlspecialchars($fetch_project['image']); ?>" alt="">
      <div class="name"><?= htmlspecialchars($fetch_project['name']); ?></div>
      <div class="description"><?= htmlspecialchars($fetch_project['description']); ?></div>
      <div class="goal-amount">Goal: $<?= htmlspecialchars($goal_amount); ?></div>
      <div class="current-amount">Current: $<?= htmlspecialchars($current_amount); ?></div>
      <div class="progress-bar">
         <div class="progress-bar-fill" style="width: <?= $progress_percentage; ?>%;"></div>
      </div>
   </div>
   <?php
        }
     } else {
         echo '<p class="empty">No completed projects found!</p>';
     }
   ?>
   </div>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

<script>
   const slides = document.querySelector('.slides');
   const images = document.querySelectorAll('.slides img');
   const prev = document.querySelector('.prev');
   const next = document.querySelector('.next');

   let currentIndex = 0;

   function showSlide(index) {
      if (index >= images.length) {
         currentIndex = 0;
      } else if (index < 0) {
         currentIndex = images.length - 1;
      } else {
         currentIndex = index;
      }
      slides.style.transform = `translateX(${-currentIndex * 600}px)`; // Adjust width based on image size
   }

   prev.addEventListener('click', () => showSlide(currentIndex - 1));
   next.addEventListener('click', () => showSlide(currentIndex + 1));

   // Auto-slide every 5 seconds
   setInterval(() => showSlide(currentIndex + 1), 5000);
</script>

</body>
</html>
