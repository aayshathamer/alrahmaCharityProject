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

if(isset($_POST['donation'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);
   $total_projects = $_POST['total_projects'];
   $total_amounts = $_POST['total_amounts'];

   $check_discharge = $conn->prepare("SELECT * FROM `discharge` WHERE user_id = ?");
   $check_discharge->execute([$user_id]);

   if($check_discharge->rowCount() > 0){

      // Redirect to payment.php with the necessary information
      header("Location: payment.php?name=$name&number=$number&email=$email&method=$method&total_projects=$total_projects&total_amounts=$total_amounts");
      exit;

   } else {
      $message[] = 'Your discharge is empty';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Checkout</title>
   
   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="checkout-donations">

   <form action="" method="POST">

      <h3>Your donations</h3>

      <div class="display-donations">
      <?php
         $grand_total = 0;
         $discharge_items = [];
         $select_discharge = $conn->prepare("SELECT * FROM `discharge` WHERE user_id = ?");
         $select_discharge->execute([$user_id]);
         if($select_discharge->rowCount() > 0){
            while($fetch_discharge = $select_discharge->fetch(PDO::FETCH_ASSOC)){
               $discharge_items[] = $fetch_discharge['name'].' ('.$fetch_discharge['user_donation'].' $)';
               $grand_total += $fetch_discharge['user_donation'];
      ?>
         <p> <?= htmlspecialchars($fetch_discharge['name']); ?> <span>($<?= htmlspecialchars($fetch_discharge['user_donation']); ?> )</span> </p>
      <?php
            }
            $total_projects = implode(', ', $discharge_items);
         }else{
            echo '<p class="empty">Your discharge is empty!</p>';
         }
      ?>
         <input type="hidden" name="total_projects" value="<?= htmlspecialchars($total_projects); ?>">
         <input type="hidden" name="total_amounts" value="<?= $grand_total; ?>">
         <div class="grand-total">Grand Total : <span>$<?= $grand_total; ?></span></div>
      </div>

      <h3>Place your donations</h3>

      <div class="flex">
      <div class="inputBox">
            <span>Name:</span>
            <input type="text" name="name" placeholder="Enter your name" class="box" maxlength="20" required>
         </div>
         <div class="inputBox">
            <span>Phone Number :</span>
            <input type="number" name="number" placeholder="Enter your number" class="box" min="0" max="9999999999" onkeypress="if(this.value.length == 10) return false;" required>
         </div>
         <div class="inputBox">
            <span>Email :</span>
            <input type="email" name="email" placeholder="Enter your email" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>Payment Method:</span>
            <select name="method" class="box" required>
               <option value="Visa Card">Visa Card</option>
               <option value="Master Card">Mastercard</option>
               <option value="Visa Card">DahabShiil</option>
               <option value="Master Card">Zaad</option>
            </select>
         </div>
      </div>

      <input type="submit" name="donation" class="btn <?= ($grand_total > 0)?'':'disabled'; ?>" value="Place Donation">

   </form>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
