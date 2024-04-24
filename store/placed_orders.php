<?php

include '../components/connect.php';

session_start();

$store_id = $_SESSION['store_id'];

if(!isset($store_id)){
   header('location:store_login.php');
}

if(isset($_POST['update_payment'])){
   $order_id = $_POST['order_id'];
   $track = $_POST['track'];
   $track = filter_var($track, FILTER_SANITIZE_STRING);
   $transport = $_POST['transport'];
   $transport = filter_var($transport, FILTER_SANITIZE_STRING);
   $update_payment = $conn->prepare("UPDATE `orders` SET track = ? ,transport = ?WHERE id = ?");
   $update_payment->execute([$track,$transport, $order_id]);
   $message[] = 'payment status updated!';
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>placed orders</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/store_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="orders">

<h1 class="heading">placed orders</h1>

<div class="box-container">

   <?php
      $select_orders = $conn->prepare("SELECT * FROM `orders`");
      $select_orders->execute();
      if($select_orders->rowCount() > 0){
         while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <p> วันที่สั่งซื้อสินค้า : <span><?= $fetch_orders['placed_on']; ?></span> </p>
      <p> ชื่อจัดส่ง : <span><?= $fetch_orders['name']; ?></span> </p>
      <p> เบอร์มือถือ : <span><?= $fetch_orders['number']; ?></span> </p>
      <p> ที่อยู่จัดส่ง : <span><?= $fetch_orders['address']; ?></span> </p>
      <p> ราคาสินค้า : <span><?= $fetch_orders['total_products']; ?></span> </p>
      <p> ราคาค่าส่งสินค้า : <span>฿ <?= $fetch_orders['carry']; ?>/-</span> </p>
      <p> ราคาค่าหิ้วสินค้า : <span>฿ <?= $fetch_orders['shipping']; ?>/-</span> </p>
      <p> ยอดรวมราคาสินค้า : <span>฿ <?= $fetch_orders['total_price']; ?>/-</span> </p>
      <p> สถานะการชำระเงิน : <span><?= $fetch_orders['payment_status']; ?></span> </p>
      <form action="" method="post">
         <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
         <input type="text" class="box" name="track" placeholder="กรอกหมายเลขพัสดุ" required value="<?= $fetch_orders['track']; ?>"><br>
         <select name="transport"  class="select" required>
               <?php
               $select_tran = $conn->prepare("SELECT * FROM `transport`");
               $select_tran->execute();
               if ($select_tran->rowCount() > 0) {
                  while ($fetch_tran = $select_tran->fetch(PDO::FETCH_ASSOC)) {
                     $transport = $fetch_tran['transport'];
                     echo '<option value=' . $transport . '>' . $transport . '</option>';
                  }
               }

               ?>
            </select>
        <div class="flex-btn">
         <input type="submit" value="compeacted" class="option-btn" name="update_payment">
        </div>
      </form>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">no orders placed yet!</p>';
      }
   ?>

</div>

</section>

</section>












<script src="../js/admin_script.js"></script>
   
</body>
</html>