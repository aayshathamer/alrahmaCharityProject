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

if(isset($_POST['submit_payment'])){
   $name = $_POST['name'];
   $card_number = $_POST['card_number'];
   $expiry_date = $_POST['expiry_date'];
   $cvv = $_POST['cvv'];
   $email = $_POST['email'];
   $method = $_POST['method'];
   $total_projects = $_POST['total_projects'];
   $total_amounts = $_POST['total_amounts'];

   $conn->beginTransaction();

   try {
      // Insert donation into the donations table
      $insert_donation = $conn->prepare("INSERT INTO `donations`(user_id, name, number, email, method, total_projects, total_amounts) VALUES(?,?,?,?,?,?,?)");
      $insert_donation->execute([$user_id, $name, $card_number, $email, $method, $total_projects, $total_amounts]);

      // Fetch the discharge details
      $select_discharge = $conn->prepare("SELECT * FROM `discharge` WHERE user_id = ?");
      $select_discharge->execute([$user_id]);

      while($fetch_discharge = $select_discharge->fetch(PDO::FETCH_ASSOC)) {
         $pid = $fetch_discharge['pid'];
         $user_donation = $fetch_discharge['user_donation'];

         // Update current_amount in projects table
         $update_amount_query = $conn->prepare("UPDATE `projects` SET current_amount = current_amount + ? WHERE id = ?");
         $update_amount_query->execute([$user_donation, $pid]);

         // Check if the project is completed
         $select_project = $conn->prepare("SELECT current_amount, goal_amount FROM `projects` WHERE id = ?");
         $select_project->execute([$pid]);
         $project = $select_project->fetch(PDO::FETCH_ASSOC);

         if ($project && $project['current_amount'] >= $project['goal_amount']) {
            // Update project status to 'completed'
            $update_status_query = $conn->prepare("UPDATE `projects` SET status = 'completed' WHERE id = ?");
            $update_status_query->execute([$pid]);
         }
      }

      // Remove all items from discharge after successful donation
      $delete_discharge = $conn->prepare("DELETE FROM `discharge` WHERE user_id = ?");
      $delete_discharge->execute([$user_id]);

      $conn->commit();

      // Store donation details in session
      $_SESSION['name'] = $name;
      $_SESSION['total_amounts'] = $total_amounts;
      
      header('Location: success.php'); // Redirect to a success page
      exit;

   } catch (Exception $e) {
      $conn->rollBack();
      echo 'Transaction failed: ' . $e->getMessage();
   }
}

// Get the donation details from the URL
$name = $_GET['name'];
$number = $_GET['number'];
$email = $_GET['email'];
$method = $_GET['method'];
$total_projects = $_GET['total_projects'];
$total_amounts = $_GET['total_amounts'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Payment</title>
   <link rel="stylesheet" href="css/payment.css">
</head>
<body>

<form action="" method="POST">
    <h1>Payment Information</h1>
    <label for="name">Name on Card:</label>
    <input type="text" id="name" name="name" value="<?= htmlspecialchars($name); ?>" required>
    
    <label for="card_number">Card Number:</label>
    <input type="text" id="card_number" name="card_number" required>
    
    <label for="expiry_date">Expiry Date:</label>
    <input type="text" id="expiry_date" name="expiry_date" placeholder="MM/YY" required>
    
    <label for="cvv">CVV:</label>
    <input type="text" id="cvv" name="cvv" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($email); ?>" required>

    <label for="total_projects">Total Projects:</label>
    <input type="text" id="total_projects" name="total_projects" value="<?= htmlspecialchars($total_projects); ?>" readonly>

    <label for="total_amounts">Total Amount:</label>
    <input type="number" id="total_amounts" name="total_amounts" value="<?= htmlspecialchars($total_amounts); ?>" readonly>
    
    <input type="hidden" name="method" value="<?= htmlspecialchars($method); ?>">

    <input type="submit" name="submit_payment" value="Submit Payment">
</form>

</body>
</html>
