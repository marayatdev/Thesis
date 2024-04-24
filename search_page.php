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
   <title>search page</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <style>
table, th, td {
  border: 1px solid;
}
</style>

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="search-form">
   <form action="" method="post">
      <input type="text" name="search_box" placeholder="ค้นหาแบรนด์สินค้า / ประเทศสินค้า ..." maxlength="100" class="box" required>
      <button type="submit" class="fas fa-search" name="search_btn"></button>
   </form>
</section>



<section class="products" style="padding-top: 0; min-height:100vh;">

   <div class="box-container">

   <?php
     if(isset($_POST['search_box']) OR isset($_POST['search_btn'])){
     $search_box = $_POST['search_box'];
     $select_products = $conn->prepare("SELECT * FROM `products` WHERE country LIKE '%{$search_box}%'OR brand LIKE '%{$search_box}%'"); 
     $select_products->execute();
     if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
<form action="" method="post" class="box" <?php if($fetch_product['stock'] == 0){echo 'สินค้าหมด';}; ?>>
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
      <input type="hidden" name="brand" value="<?= $fetch_product['brand']; ?>">
      <input type="hidden" name="details" value="<?= $fetch_product['details']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
      <input type="hidden" name="carry" value="<?= $fetch_product['carry']; ?>">
      <input type="hidden" name="shipping" value="<?= $fetch_product['shipping']; ?>">
      <input type="hidden" name="stock" value="<?= $fetch_product['stock']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
      <!-- <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button> -->
      <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
      <?php if($fetch_product['stock'] > 9){ ?>
         <span class="stock" style="color: green;"><i class="fas fa-check"></i> in stock</span>
      <?php }elseif($fetch_product['stock'] == 0){ ?>
         <span class="stock" style="color: red;"><i class="fas fa-times"></i> ไม่มีสินค้า</span>
      <?php }else{ ?>
         <span class="stock" style="color: #088F8F;">จำนวนที่สามรถรับหิ้วได้ <?= $fetch_product['stock']; ?> ชิ้น</span>
      <?php } ?>
      <div class="chat"><a href="home.php"><i class='far fa-comment-dots' style='font-size:20px;color:black' ></i></a></div>
      <div class="name"><?= $fetch_product['name']; ?></div>
      <div class="name"> <div><?= $fetch_product['date']; ?></div></div>
      <div class="brand"><?= $fetch_product['brand']; ?> <?= $fetch_product['country']; ?></div>
      <div class="brand"><a href="profile.php?pid=<?= $fetch_product['id']; ?>"><?= $fetch_product['sname']; ?></a></div>
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
         <input type="number" name="quantity" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1"><br>
      </div>
      <?php if($fetch_product['stock'] != 0){ ?>
         <input type="submit" value="add to cart" class="btn sub" name="add_to_cart">
      <?php }; ?>
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">no products found!</p>';
      }
   }
   ?>

   </div>

</section>





<script src="js/script.js"></script>

</body>
</html>