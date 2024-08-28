<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/wishlist_discharge.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Quick view</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="quick-view">

   <h1 class="heading">Quick view</h1>

   <?php
     $pid = $_GET['pid'];
     $select_sadaqa = $conn->prepare("SELECT * FROM `sadaqa` WHERE id = ?"); 
     $select_sadaqa->execute([$pid]);
     if($select_sadaqa->rowCount() > 0){
      while($fetch_project = $select_sadaqa->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= $fetch_project['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_project['name']; ?>">
      <input type="hidden" name="goal_amount" value="<?= $fetch_project['goal_amount']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_project['image']; ?>">
      <div class="row">
         <div class="image-container">
            <div class="main-image">
               <img src="uploaded_img/<?= $fetch_project['image']; ?>" alt="">
            </div>
            <div class="sub-image">
               <img src="uploaded_img/<?= $fetch_project['image']; ?>" alt="">
            </div>
         </div>
         <div class="content">
            <div class="name"><?= $fetch_project['name']; ?></div>
            <div class="description"><?= $fetch_project['description']; ?></div>
            <div class="flex">
               <div class="goal_amount"><span>$</span><?= $fetch_project['goal_amount']; ?><span></span></div>
               <div class="donation-amount">
                  <label for="user_donation">Add Donation:</label>
                  <input type="number" name="user_donation" class="user_donation" min="1" required>
               </div>
            </div>
            
            <div class="flex-btn">
               <input type="submit" value="Add to Discharge" class="btn" name="add_to_discharge">
            </div>
         </div>
      </div>
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">no sadaqah added yet!</p>';
   }
   ?>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
