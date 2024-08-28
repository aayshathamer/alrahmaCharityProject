<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:user_login.php');
   exit;
};

if(isset($_POST['delete'])){
   $discharge_id = $_POST['discharge_id'];
   $delete_discharge_item = $conn->prepare("DELETE FROM `discharge` WHERE id = ?");
   $delete_discharge_item->execute([$discharge_id]);
}

if(isset($_GET['delete_all'])){
   $delete_discharge_item = $conn->prepare("DELETE FROM `discharge` WHERE user_id = ?");
   $delete_discharge_item->execute([$user_id]);
   header('location:discharge.php');
   exit;
}

if(isset($_POST['update_donation'])){
   $discharge_id = $_POST['discharge_id'];
   $user_donation = $_POST['user_donation'];
   $user_donation = filter_var($user_donation, FILTER_SANITIZE_NUMBER_INT);
   $update_donation = $conn->prepare("UPDATE `discharge` SET user_donation = ? WHERE id = ?");
   $update_donation->execute([$user_donation, $discharge_id]);
   $message[] = 'User donation updated';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Contributing Discharge</title>
   
   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="projects contributing-discharge">

   <h3 class="heading">Contributing Discharge</h3>

   <div class="box-container">

   <?php
      $grand_total = 0;
      $select_discharge = $conn->prepare("SELECT * FROM `discharge` WHERE user_id = ?");
      $select_discharge->execute([$user_id]);
      if($select_discharge->rowCount() > 0){
         while($fetch_discharge = $select_discharge->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="discharge_id" value="<?= $fetch_discharge['id']; ?>">
      <a href="quick_view.php?pid=<?= $fetch_discharge['pid']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_discharge['image']; ?>" alt="">
      <div class="name"><?= htmlspecialchars($fetch_discharge['name']); ?></div>
      <div class="goal-amount">Goal: $<?= htmlspecialchars($fetch_discharge['goal_amount']); ?></div>
      <div class="flex">
      <div class="donation-amount">
      <label for="user_donation">User Donation:</label>
            <input type="number" name="user_donation" class="user_donation" min="1" value="<?= htmlspecialchars($fetch_discharge['user_donation']); ?>">
         </div>
         <button type="submit" class="fas fa-edit" name="update_donation"></button>
      </div>
      <div class="sub-total"> Sub Total : <span>$<?= $sub_total = $fetch_discharge['user_donation']; ?></span> </div>
      <input type="submit" value="delete item" onclick="return confirm('Delete this from discharge?');" class="delete-btn" name="delete">
   </form>
   <?php
   $grand_total += $sub_total;
      }
   }else{
      echo '<p class="empty">Your discharge is empty</p>';
   }
   ?>
   </div>

   <div class="discharge-total">
      <p>Grand Total : <span>$<?= $grand_total; ?></span></p>
      <a href="contribute.php" class="option-btn">Continue contributing</a>
      <a href="discharge.php?delete_all" class="delete-btn <?= ($grand_total > 0)?'':'disabled'; ?>" onclick="return confirm('Delete all from discharge?');">Delete All Items?</a>
      <a href="checkout.php" class="btn <?= ($grand_total > 0)?'':'disabled'; ?>">Proceed to Checkout</a>
   </div>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
