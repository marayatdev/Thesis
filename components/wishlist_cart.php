<?php
include 'alers.php';

if(isset($_POST['add_to_wishlist'])){

   if($user_id == ''){
      header('location:user_login.php');
   }else{

      $pid = $_POST['pid'];
      $pid = filter_var($pid, FILTER_SANITIZE_STRING);
      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_STRING);
      $brand = $_POST['brand'];
      $brand = filter_var($brand, FILTER_SANITIZE_STRING);
      $details = $_POST['details'];
      $details = filter_var($details, FILTER_SANITIZE_STRING);
      $price = $_POST['price'];
      $price = filter_var($price, FILTER_SANITIZE_STRING);
      $carry = $_POST['carry'];
      $carry = filter_var($carry, FILTER_SANITIZE_STRING);
      $shipping = $_POST['shipping'];
      $shipping = filter_var($shipping, FILTER_SANITIZE_STRING);
      $quantity = $_POST['quantity'];
      $quantity = filter_var($quantity, FILTER_SANITIZE_STRING);
      $image = $_POST['image'];
      $image = filter_var($image, FILTER_SANITIZE_STRING);

      $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
      $check_wishlist_numbers->execute([$name, $user_id]);

      $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
      $check_cart_numbers->execute([$name, $user_id]);

      if($check_wishlist_numbers->rowCount() > 0){
         $message[] = 'already added to wishlist!';
      }elseif($check_cart_numbers->rowCount() > 0){
         $message[] = 'already added to cart!';
      }else{
         $insert_wishlist = $conn->prepare("INSERT INTO `wishlist`(user_id, pid, name,brand,details, price,carry,shipping,quantity, image) VALUES(?,?,?,?,?,?,?,?,?,?)");
         $insert_wishlist->execute([$user_id, $pid, $name,$brand,$details, $price,$carry,$shipping,$quantity, $image]);
         $message[] = 'added to wishlist!';
      }

   }

}

if(isset($_POST['add_to_cart'])){

   if($user_id == ''){
      header('location:user_login.php');
   }else{

      $pid = $_POST['pid'];
      $pid = filter_var($pid, FILTER_SANITIZE_STRING);
      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_STRING);
      $brand = $_POST['brand'];
      $brand = filter_var($brand, FILTER_SANITIZE_STRING);
      $details = $_POST['details'];
      $details = filter_var($details, FILTER_SANITIZE_STRING);
      $price = $_POST['price'];
      $price = filter_var($price, FILTER_SANITIZE_STRING);
      $carry = $_POST['carry'];
      $carry = filter_var($carry, FILTER_SANITIZE_STRING);
      $shipping = $_POST['shipping'];
      $shipping = filter_var($shipping, FILTER_SANITIZE_STRING);
      $image = $_POST['image'];
      $image = filter_var($image, FILTER_SANITIZE_STRING);
      $quantity = $_POST['quantity'];
      $quantity = filter_var($quantity, FILTER_SANITIZE_STRING);

      $verify_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ? AND pid = ?");
      $verify_cart->execute([$user_id, $pid]);
   
      $max_cart_limit = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
      $max_cart_limit->execute([$user_id]);

      if($verify_cart->rowCount() > 0){
         $warning_msg[] = 'ในตระกร้ามีสินค้านี้อยู่แล้ว';
      }elseif($max_cart_limit->rowCount() == 10){
         $warning_msg[] = 'Cart is full!';
      }else{

         $select_p = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
         $select_p->execute([$pid]);
         $fetch_p = $select_p->fetch(PDO::FETCH_ASSOC);

         $total_stock = ($fetch_p['stock'] - $quantity);

         $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
         $check_wishlist_numbers->execute([$name, $user_id]);

         if($check_wishlist_numbers->rowCount() > 0){
            $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE name = ? AND user_id = ?");
            $delete_wishlist->execute([$name, $user_id]);
         }
         if($quantity > $fetch_p['stock']){
            $warning_msg[] = 'Only '.$fetch_p['stock'].' stock is left';
         }else{
            
            
            $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name,brand,details,price,carry,shipping ,quantity, image) VALUES(?,?,?,?,?,?,?,?,?,?)");
            $insert_cart->execute([$user_id, $pid, $name,$brand,$details, $price,$carry,$shipping, $quantity, $image]);

            $update_stock = $conn->prepare("UPDATE `products` SET stock = ? WHERE id = ?");
            $update_stock->execute([$total_stock, $pid]);
            $success_msg[] = 'เพิ่มสินค้าลงในตระกร้าเรียบร้อย';
   
         }

         
      }

   }

}

?>
