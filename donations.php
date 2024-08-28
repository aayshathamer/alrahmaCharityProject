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
   <title>Donations</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   
   <!-- jsPDF library -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
   
   <style>
   @media print {
      body * {
         visibility: hidden;
      }
      .receipt, .receipt * {
         visibility: visible;
         font-size: 12px;
      }
      .receipt {
         position: absolute;
         left: 0;
         top: 0;
         right: 0;
         margin: 0 auto;
      }
      .action-buttons {
         display: none;
      }
   }
   .action-buttons {
      margin-top: 10px;
   }
   .action-buttons .btn {
      margin-right: 10px;
   }
   </style>
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="donations">

   <h1 class="heading">Placed donations.</h1>

   <div class="box-container" id="receipts">

   <?php
      if($user_id == ''){
         echo '<p class="empty">please login to see your donations</p>';
      }else{
         $select_donations = $conn->prepare("SELECT * FROM `donations` WHERE user_id = ?");
         $select_donations->execute([$user_id]);
         if($select_donations->rowCount() > 0){
            while($fetch_donations = $select_donations->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box receipt" id="receipt-<?= $fetch_donations['id']; ?>">
      <p><strong>Al-Rahma Charity Organization</strong></p>
      <p><strong>Date:</strong> <span><?= date('Y-m-d'); ?></span></p>
      <p>______________________________________</p>
      <p>Placed on : <span><?= $fetch_donations['placed_on']; ?></span></p>
      <p>Name : <span><?= $fetch_donations['name']; ?></span></p>
      <p>Email : <span><?= $fetch_donations['email']; ?></span></p>
      <p>Phone Number : <span><?= $fetch_donations['number']; ?></span></p>
      <p>Payment Method : <span><?= $fetch_donations['method']; ?></span></p>
      <p>Your donations : <span><?= $fetch_donations['total_projects']; ?></span></p>
      <p>Total price : <span>$<?= $fetch_donations['total_amounts']; ?></span></p>
      <p><strong>Thank you for your donation!</strong></p>
      <div class="action-buttons">
         <button onclick="printReceipt('receipt-<?= $fetch_donations['id']; ?>')" class="btn">Print</button>
         <button onclick="downloadPDF('receipt-<?= $fetch_donations['id']; ?>')" class="btn">Download PDF</button>
      </div>
   </div>
   <?php
      }
      }else{
         echo '<p class="empty">no donations placed yet!</p>';
      }
      }
   ?>

   </div>

</section>

<?php include 'components/footer.php'; ?>

<script>
function printReceipt(id) {
   const receipt = document.getElementById(id).outerHTML;
   const originalContent = document.body.innerHTML;
   document.body.innerHTML = receipt;
   window.print();
   document.body.innerHTML = originalContent;
   location.reload(); // Reload the page to restore the original content
}

async function downloadPDF(id) {
   const { jsPDF } = window.jspdf;
   const doc = new jsPDF();
   const receipt = document.getElementById(id);

   // Hide action buttons for PDF generation
   receipt.querySelector('.action-buttons').style.display = 'none';

   const canvas = await html2canvas(receipt);
   const imgData = canvas.toDataURL('image/png');
   const imgProps = doc.getImageProperties(imgData);
   const pdfWidth = doc.internal.pageSize.getWidth();
   const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

   doc.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
   doc.save('receipt.pdf');

   // Restore action buttons
   receipt.querySelector('.action-buttons').style.display = 'block';
}
</script>

</body>
</html>
