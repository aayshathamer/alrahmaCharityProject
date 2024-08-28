<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>About</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="about">

   <div class="row">

      <div class="image">
         <img src="images/44.jpg" alt="">
      </div>

      <div class="content">
         <h3>Al_Rahma Charity Mission</h3>
         <p>Al_Rahma Charity's website mission is to serve as a digital gateway to compassion and philanthropy. Through user-friendly navigation and engaging content, it aims to raise awareness about pressing social issues and inspire action towards positive change. By showcasing their projects, sharing impactful stories, and providing easy donation channels, the website embodies the organization's commitment to making a difference in the lives of those in need, fostering a culture of empathy and generosity in the online realm.</p>

         <a href="contact.php" class="btn">Contact Us</a>
      </div>

   </div>

</section>

<section class="reviews">
   
   <h1 class="heading">Our Team</h1>

   <div class="swiper reviews-slider">

   <div class="swiper-wrapper">

      <div class="swiper-slide slide">
         <img src="images/629.jpg" alt="">
         <p>Zakiah, with her boundless empathy and organizational prowess, serves as the compassionate heart of the team, ensuring every initiative reflects the charity's values of kindness and inclusivity. </p>
         <h3> <a href="www.facebook.com" target="_blank">Zakiah Omar Haj</a></h3>
      </div>

      <div class="swiper-slide slide">
         <img src="images/765.jpg" alt="">
         <p>Mariam, a visionary strategist, navigates the charity's path forward with wisdom and determination, weaving innovative solutions into the fabric of their endeavors. </p>
         <h3><a href="www.facebook.com" target="_blank">Mariam Ali Yahya</a></h3>
      </div>

      <div class="swiper-slide slide">
         <img src="images/000.jpg" alt="">
         <p>Ahmad, the Projects Director, orchestrates the implementation of their vision, leveraging his technical expertise and unwavering dedication to ensure that each project unfolds seamlessly, making a tangible difference in the lives of those they serve. </p>
         <h3><a href="www.facebook.com" target="_blank">Ahmad Khalil Abdi</a></h3>
      </div>

      <div class="swiper-slide slide">
         <img src="images/tr.jpg" alt="">
         <p>Zainab, the financial manager, ensures transparency and efficiency in resource allocation. </p>      
         <h3><a href="www.facebook.com" target="_blank">Zainab Abdi Mukhtar</a></h3>
      </div>

      <div class="swiper-slide slide">
         <img src="images/93.jpg" alt="">
         <p>Ali, the community liaison, fosters connections and engagement.</p>
         <h3><a href="www.facebook.com" target="_blank">Ali Zaher Mahdi</a></h3>
      </div>

      <div class="swiper-slide slide">
         <img src="images/653.jpg" alt="">
         <p>Fatima, the communications specialist, amplifies their impact through compelling storytelling and outreach efforts.</p>
        
         <h3><a href="www.facebook.com"  target="_blank">Fatima Zakaria Sadiq</a></h3>
      </div>

   </div>

   <div class="swiper-pagination"></div>

   </div>

</section>









<?php include 'components/footer.php'; ?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<script src="js/script.js"></script>

<script>

var swiper = new Swiper(".reviews-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      0: {
        slidesPerView:1,
      },
      768: {
        slidesPerView: 2,
      },
      991: {
        slidesPerView: 3,
      },
   },
});

</script>

</body>
</html>