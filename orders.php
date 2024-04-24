<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};




if (isset($_POST['order'])) {

   $charge = $_POST['charge'];
   $charge = filter_var($charge, FILTER_SANITIZE_STRING);


   
   $insert_order = $conn->prepare("INSERT INTO `orders`(charge) VALUES(?)");
   $insert_order->execute([$charge]);

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>orders</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="orders">

   <h1 class="heading">placed orders</h1>

   <div class="box-container">

   <?php
      if($user_id == ''){
         echo '<p class="empty">please login to see your orders</p>';
      }else{
         $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
         $select_orders->execute([$user_id]);
         if($select_orders->rowCount() > 0){
            while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>

   <div class="box">
      <a href="add_review.php?pid=<?= $fetch_orders['pid']; ?>"><input class="btn" type="button" value="Review"></a>
      <p>วันที่สั่งซื้อ : <span><?= $fetch_orders['placed_on']; ?></span></p>
      <p>ชื่อที่อยู่จัดส่ง : <span><?= $fetch_orders['name']; ?></span></p>
      <p>email : <span><?= $fetch_orders['email']; ?></span></p>
      <p>เบอร์มือถือ : <span><?= $fetch_orders['number']; ?></span></p>
      <p>ที่อยู่จัดส่ง : <span><?= $fetch_orders['address']; ?></span></p>
      <p>วิธีการชำระเงิน : <span><?= $fetch_orders['method']; ?></span></p>
      <p>เลขพัสดุ : <span><?= $fetch_orders['track']; ?></span></p>
      <p>บริษัทขนส่ง : <span><?= $fetch_orders['transport']; ?></span></p>
      <p>ราคาสินค้า: <span><?= $fetch_orders['total_products']; ?></span></p>
      <p>ราคาค่าหิ้วสินค้า : <span><?= $fetch_orders['carry']; ?></span></p>
      <p>ราคาค่าส่งสินค้า : <span><?= $fetch_orders['shipping']; ?></span></p>
      <p>รวมราคาสินค้า: <span><?= $fetch_orders['total_price']; ?></span></p>
      <p>สถานะคำสังซื้อ : <span style="color:<?php if($fetch_orders['payment_status'] == 'รอการชำระเงิน'){ echo 'red'; }else{ echo 'green'; }; ?>"><?= $fetch_orders['payment_status']; ?></span> </p>
      
      <form name="payForm" method="POST" action="pay.php" class= <?php if ($fetch_orders['payment_status'] == 'successful'){ echo 'btn-p'; }else{echo 'btn';}?>>
         
  <script type="text/javascript" src="https://cdn.omise.co/omise.js"
    data-key="pkey_test_5vehruuk0v38wnxs6no"
    data-image="http://bit.ly/customer_image"
    data-frame-label="Conpagno"
    data-button-label="ชำระเงิน"
    data-submit-label="ชำระเงิน"
    data-location="no"
    data-amount="<?= $fetch_orders['total_price']; ?>00"
    data-currency="thb"
    >
  </script>

</form>
   </div>
   <?php
      }
      }else{
         echo '<p class="empty">no orders placed yet!</p>';
      }
      }
   ?>
   

   </div>

</section>




<!-- <?php include 'components/footer.php'; ?> -->

<script src="js/script.js"></script>

</body>
</html>