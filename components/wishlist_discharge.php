<?php

if(isset($_POST['add_to_wishlist'])){

   if($user_id == ''){
      header('location:user_login.php');
   }else{

      $pid = $_POST['pid'];
      $pid = filter_var($pid, FILTER_SANITIZE_STRING);
      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_STRING);
      $goal_amount = $_POST['goal_amount'];
      $goal_amount = filter_var($goal_amount, FILTER_SANITIZE_NUMBER_INT);
      $image = $_POST['image'];
      $image = filter_var($image, FILTER_SANITIZE_STRING);

      $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
      $check_wishlist_numbers->execute([$name, $user_id]);

      $check_discharge_numbers = $conn->prepare("SELECT * FROM `discharge` WHERE name = ? AND user_id = ?");
      $check_discharge_numbers->execute([$name, $user_id]);

      if($check_wishlist_numbers->rowCount() > 0){
         $message[] = 'Already added to wishlist!';
      }elseif($check_discharge_numbers->rowCount() > 0){
         $message[] = 'Already added to discharge!';
      }else{
         $insert_wishlist = $conn->prepare("INSERT INTO `wishlist`(user_id, pid, name, goal_amount, image) VALUES(?,?,?,?,?)");
         $insert_wishlist->execute([$user_id, $pid, $name, $goal_amount, $image]);
         $message[] = 'Added to wishlist!';
      }

   }

}

if(isset($_POST['add_to_discharge'])){

   if($user_id == ''){
      header('location:user_login.php');
   }else{

      $pid = $_POST['pid'];
      $pid = filter_var($pid, FILTER_SANITIZE_STRING);
      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_STRING);
      $goal_amount = $_POST['goal_amount'];
      $goal_amount = filter_var($goal_amount, FILTER_SANITIZE_NUMBER_INT);
      $image = $_POST['image'];
      $image = filter_var($image, FILTER_SANITIZE_STRING);
      $user_donation = $_POST['user_donation'];
      $user_donation = filter_var($user_donation, FILTER_SANITIZE_NUMBER_INT);

      $check_discharge_numbers = $conn->prepare("SELECT * FROM `discharge` WHERE name = ? AND user_id = ?");
      $check_discharge_numbers->execute([$name, $user_id]);

      if($check_discharge_numbers->rowCount() > 0){
         $message[] = 'Already added to discharge!';
      }else{

         $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
         $check_wishlist_numbers->execute([$name, $user_id]);

         if($check_wishlist_numbers->rowCount() > 0){
            $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE name = ? AND user_id = ?");
            $delete_wishlist->execute([$name, $user_id]);
         }

         $insert_discharge = $conn->prepare("INSERT INTO `discharge`(user_id, pid, name, goal_amount, user_donation, image) VALUES(?,?,?,?,?,?)");
         $insert_discharge->execute([$user_id, $pid, $name, $goal_amount, $user_donation, $image]);
         $message[] = 'Added to discharge!';
         
      }

   }

}

?>
