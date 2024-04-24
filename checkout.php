<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:user_login.php');
}
;

$store_id = $_SESSION['store_id'];

if (!isset($store_id)) {
   header('location:store_login.php');
}

if (isset($_POST['order'])) {

   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);
   $address = '' . $_POST['flat'] . ', ' . $_POST['street'] . ', ' . $_POST['city'] . ', ' . $_POST['state'] . ', ' . $_POST['country'] . ' - ' . $_POST['pin_code'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $total_products = $_POST['total_products'];
   $total_price = $_POST['total_price'];
   $carry = $_POST['carry'];
   $carry = filter_var($carry, FILTER_SANITIZE_STRING);
   $shipping = $_POST['shipping'];
   $shipping = filter_var($shipping, FILTER_SANITIZE_STRING);

   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->execute([$user_id]);

   if ($check_cart->rowCount() > 0) {

      $insert_order = $conn->prepare("INSERT INTO `orders`(user_id,store_id,pid, name, number, email, method, address, total_products, total_price,carry,shipping) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)");
      $insert_order->execute([$user_id, $store_id,$pid, $name, $number, $email, $method, $address, $total_products, $total_price, $carry, $shipping]);

      $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
      $delete_cart->execute([$user_id]);

      $message[] = 'order placed successfully!';
   } else {
      $message[] = 'your cart is empty';
   }

}


?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'components/user_header.php'; ?>


   <section class="checkout-orders">

      <?php
      $select_products = $conn->prepare("SELECT * FROM `products`");
      $select_products->execute();
      if ($select_products->rowCount() > 0) {
         while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
            ?>

            <form action="" method="POST">

               <h3></h3>

               <div class="display-orders">
                  <?php
                  $grand_total = 0;
                  $cart_items[] = '';
                  $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                  $select_cart->execute([$user_id]);
                  if ($select_cart->rowCount() > 0) {
                     while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                        $cart_items[] = $fetch_cart['name'] . ' (' . $fetch_cart['price'] . ' x ' . $fetch_cart['quantity'] . ') - ';
                        $total_products = implode($cart_items);
                        $grand_total += ($fetch_cart['quantity'] * ($fetch_cart['price'] + $fetch_cart['carry'] + $fetch_cart['shipping']));
                        ?>
                        <p> ราคาสินค้า :
                           <?= '฿ ' . $fetch_cart['price'] . '/- x ' . $fetch_cart['quantity']; ?> <br>
                           ราคาค่าหิ้ว :
                           <?= '฿ ' . $fetch_cart['carry'] ?><br>
                           ราคาจัดส่ง :
                           <?= '฿ ' . $fetch_cart['shipping'] ?>
                        </p>
                        <?php
                     }
                  } else {
                     echo '<p class="empty">your cart is empty!</p>';
                  }
                  ?>

                  <input type="hidden" name="total_products" value="<?= $total_products; ?>">
                  <input type="hidden" name="carry" value="<?= $fetch_product['carry']; ?>">
                  <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
                  <input type="hidden" name="shipping" value="<?= $fetch_product['shipping']; ?>">
                  <input type="hidden" name="total_price" value="<?= $grand_total; ?>" >
                  <div class="grand-total">ยอดรวมของสินค้าทั้งหมด : <span>฿
                        <?= $grand_total; ?>/-
                     </span></div>
               </div>

               <h3>place your orders</h3>


               <div class="flex">
               <?php          
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            if($select_profile->rowCount() > 0){
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
                        <div class="inputBox">
                           <span>ชื่อที่อยู๋จัดส่ง :</span>
                           <input type="text" name="name" placeholder="กรอกที่อยู๋จัดส่ง" class="box" maxlength="20" required value="<?= $fetch_profile["name"]; ?>">
                        </div>
                        <div class="inputBox">
                           <span>เบอร์มือถือ :</span>
                           <input type="number" name="number" placeholder="กรอกเบอร์มือถือ" class="box" min="0" maxlength="10"
                              max="9999999999" onkeypress="if(this.value.length == 10) return false;" required value="<?= $fetch_profile["number"]; ?>">
                        </div>
                        <div class="inputBox">
                           <span>email :</span>
                           <input type="email" name="email" placeholder="กรอก email" class="box" maxlength="50" required value="<?= $fetch_profile["email"]; ?>">
                        </div>
                        <div class="inputBox">
                           <span>วิธีกาชำระเงิน :</span>
                           <select name="method" class="box" required>
                              <option value="บัตรเครดิต/เดบิต">บัตรเครดิต/เดบิต</option>
                              <!-- <option value="เงินสด">เงินสด</option> -->
                              <option value="พร้อมเพย์">พร้อมเพย์</option>
                           </select>
                        </div>
                        <div class="inputBox">
                           <span>บ้านเลขที่ :</span>
                           <input type="text" name="flat" placeholder="บ้านเลขที่" class="box" maxlength="50" value="<?= $fetch_profile["hnumber"]; ?>" required>
                        </div>
                        <div class="inputBox">
                           <span>ถนน-แขวง-ซอย :</span>
                           <input type="text" name="street" placeholder="ถนน-แขวง-ซอย" class="box" maxlength="50" value="<?= $fetch_profile["soi"]; ?>" required>
                        </div>
                        <div class="inputBox">
                           <span>อำเภอ :</span>
                           <input type="text" name="city" placeholder="อำเภอ" class="box" maxlength="50"value="<?= $fetch_profile["aumper"]; ?>" required>
                        </div>
                        <div class="inputBox">
                           <span>ตำบล :</span>
                           <input type="text" name="state" placeholder="ตำบล" class="box" maxlength="50" value="<?= $fetch_profile["tumbon"]; ?>"required>
                        </div>
                        <div class="inputBox">
                           <span>จังหวัด</span>
                           <input type="text" name="country" placeholder="จังหวัด" class="box"value="<?= $fetch_profile["province"]; ?>" maxlength="50" required>
                        </div>
                        <div class="inputBox">
                           <span>รหัสไปรษณีย์ :</span>
                           <input type="number" min="0" name="pin_code"value="<?= $fetch_profile["code"]; ?>" placeholder="รหัสไปรษณีย์" min="0" max="999999"
                              onkeypress="if(this.value.length == 6) return false;" class="box" required>
                        </div>
                        <?php
            }
         ?>
               </div>

               <input type="submit" name="order" class="btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>" value="place order">

            </form>

         </section>


         <?php
         }
      } else {
         echo '<p class="empty"></p>';
      }
      ?>










   <!-- <?php include 'components/footer.php'; ?> -->

   <script src="js/script.js"></script>

</body>

</html>