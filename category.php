<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/wishlist_cart.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>category</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="products">

   <h1 class="heading">category</h1>

   <div class="box-container">

   <?php
     $category = $_GET['category'];
     $select_products = $conn->prepare("SELECT * FROM `products` WHERE brand LIKE '%{$category}%'"); 
     $select_products->execute();
     if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
      <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['sname']; ?>">
      <input type="hidden" name="brand" value="<?= $fetch_product['brand']; ?>">
      <input type="hidden" name="details" value="<?= $fetch_product['details']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
      <input type="hidden" name="carry" value="<?= $fetch_product['carry']; ?>">
      <input type="hidden" name="shipping" value="<?= $fetch_product['shipping']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
      <!-- <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button> -->
      <!-- <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a> -->
      <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
      <div class="name"><?= $fetch_product['name']; ?></div>
      <div class="brand"><?= $fetch_product['brand']; ?></div>
      <div class="brand"><a href="profile.php?pid=<?= $fetch_product['id']; ?>"> ร้าน : <?= $fetch_product['sname']; ?></a></div>
      <?php
               $select_sto = $conn->prepare("SELECT * FROM `stores`");
               $select_sto->execute();
               if ($select_sto->rowCount() > 0) {
                  if ($fetch_sto = $select_sto->fetch(PDO::FETCH_ASSOC)) {
                     $name = $fetch_sto['sname'];
                     

                  }
               }

               ?>
      <div class="detail">
         <div class="total"><?= $fetch_product['price']; ?></div>
         <div class="carry"><span>ราคาค่าหิ้วสินค้า </span><?= $fetch_product['carry']; ?><span>/-</span></div>
         <div class="carry"><span>ราคาค่าส่งสินค้า </span><?= $fetch_product['shipping']; ?><span>/-</span></div>
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1"><br>
      </div>
      <!-- <div class="flex">
         <div class="price"><span>ราคาสินค้า</span><?= $fetch_product['price']; ?><span>/-</span></div>
         <div class="price"><span>ราคาค่าหิ้วสินค้า</span><?= $fetch_product['carry']; ?><span>/-</span></div>
         <div class="price"><span>ราคาค่าส่งสินค้า</span><?= $fetch_product['shipping']; ?><span>/-</span></div><br>
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
      </div> -->
      <input type="submit" value="add to cart" class="btn sub" name="add_to_cart">
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">no products found!</p>';
   }
   ?>

   </div>

</section>















<script src="js/script.js"></script>

</body>
</html>