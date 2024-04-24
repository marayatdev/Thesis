<?php

include 'components/connect.php';
include 'components/alers.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:user_login.php');
};

if(isset($_POST['delete'])){
   $cart_id = $_POST['cart_id'];
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
   $delete_cart_item->execute([$cart_id]);
   $warning_msg[] = 'ลบสินค้าลงในตระกร้าเรียบร้อย';
}

if(isset($_GET['delete_all'])){
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart_item->execute([$user_id]);
   header('location:cart.php');
}

if(isset($_POST['update_qty'])){
   $cart_id = $_POST['cart_id'];
   $quantity = $_POST['quantity'];
   $quantity = filter_var($quantity, FILTER_SANITIZE_STRING);

   
   $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
   $update_qty->execute([$quantity, $cart_id]);
   $message[] = 'cart quantity updated';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shopping cart</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="products shopping-cart">

   <h3 class="heading">shopping cart</h3>

   <div class="box-container">

   <?php
      $grand_total = 0;
      $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
      $select_cart->execute([$user_id]);
      if($select_cart->rowCount() > 0){
         while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" class="box" >
      <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
      <!-- <a href="quick_view.php?pid=<?= $fetch_cart['pid']; ?>" class="fas fa-eye"></a> -->
      <img src="uploaded_img/<?= $fetch_cart['image']; ?>" alt="">
      <div class="name"><?= $fetch_cart['name']; ?></div>
      <div class="brand"><?= $fetch_cart['brand']; ?></div>
      <div class="detail">
         <div class="total"><?= $fetch_cart['price']; ?></div>
         <div class="carry"><span>ราคาค่าหิ้วสินค้า </span><?= $fetch_cart['carry']; ?><span>/-</span></div>
         <div class="carry"><span>ราคาค่าส่งสินค้า </span><?= $fetch_cart['shipping']; ?><span>/-</span></div>
         <input type="number" name="quantity" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="<?= $fetch_cart['quantity']; ?>">
         <button type="submit" class="fas fa-edit" name="update_qty"></button><br>
      </div>
      <!-- <div class="flex">
         <div class="price">$<?= $fetch_cart['price']; ?>/-</div>
         <div class="price">$<?= $fetch_cart['carry']; ?>/-</div>
         <div class="price">$<?= $fetch_cart['shipping']; ?>/-</div>
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="<?= $fetch_cart['quantity']; ?>">
         <button type="submit" class="fas fa-edit" name="update_qty"></button>
      </div> -->
      <!-- <div class="sub-total"> ราคารวมสินค้า : <span>฿ <?= $sub_total = ($fetch_cart['quantity'] * ($fetch_cart['price']+$fetch_cart['carry']+$fetch_cart['shipping'])); ?>/-</span> </div> -->
      <input type="submit" value="delete item" onclick="return confirm('delete this from cart?');" class="delete-btn de" name="delete">
   </form>
   <?php
   $grand_total += $sub_total;
      }
   }else{
      echo '<p class="empty">ไม่มีสินค้าในตระกร้า</p>';
   }
   ?>
   </div>

   <div class="cart-total">
      <p>ราคารวมสินค้า : <span>฿ <?= $grand_total; ?>/-</span></p>
      <a href="shop.php" class="option-btn">ฝากช้อปสินค้าต่อ</a>
      <a href="cart.php?delete_all" class="delete-btn <?= ($grand_total > 1)?'':'disabled'; ?>" onclick="return confirm('delete all from cart?');">ลบสินค้าในตระกร้าทั้งหมด</a>
      <a href="checkout.php" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>">กำเนินการต่อ</a>
   </div>

</section>













<!-- <?php include 'components/footer.php'; ?> -->

<script src="js/script.js"></script>
<?php include 'components/alers.php'; ?>
</body>
</html>