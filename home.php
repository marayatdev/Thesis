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
   <title>home</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<div class="home-bg">

<section class="home">

   <div class="swiper home-slider">
   
   <div class="swiper-wrapper">

      <div class="swiper-slide slide">
         <div class="image">
            <img src="images/home-img-1.png" alt="">
         </div>
         <div class="content">
            <span>upto 50% off</span>
            <h3>แพลตฟอร์มรับหิ้วสินค้า</h3>
            <a href="shop.php" class="btn">shop now</a>
         </div>
      </div>

      <div class="swiper-slide slide">
         <div class="image">
            <img src="images/home-img-2.png" alt="">
         </div>
         <div class="content">
            <span>upto 50% off</span>
            <h3>latest watches</h3>
            <a href="shop.php" class="btn">shop now</a>
         </div>
      </div>

      <div class="swiper-slide slide">
         <div class="image">
            <img src="images/home-img-3.png" alt="">
         </div>
         <div class="content">
            <span>upto 50% off</span>
            <h3>latest headsets</h3>
            <a href="shop.php" class="btn">shop now</a>
         </div>
      </div>

   </div>

      <div class="swiper-pagination"></div>

   </div>

</section>

</div>

<section class="category">

   <h1 class="heading">Brand</h1>

   <div class="swiper category-slider">

   <div class="swiper-wrapper">

   <a href="category.php?category=nike" class="swiper-slide slide">
      <img src="brand/nike.png" alt="">
      <h3>Nike</h3>
   </a>

   <a href="category.php?category=care" class="swiper-slide slide">
      <img src="brand/care.png" alt="">
      <h3>Care Bear</h3>
   </a>

   <a href="category.php?category=adidas" class="swiper-slide slide">
      <img src="brand/adidas.png" alt="">
      <h3>Adidas</h3>
   </a>

   <a href="category.php?category=keen" class="swiper-slide slide">
      <img src="brand/keen.png" alt="">
      <h3>Keen</h3>
   </a>

   <a href="category.php?category=converse" class="swiper-slide slide">
      <img src="brand/converse.png" alt="">
      <h3>Converse</h3>
   </a>

   
   <a href="category.php?category=H&M" class="swiper-slide slide">
      <img src="brand/H&M.png" alt="">
      <h3>H&M</h3>
   </a>
   <a href="category.php?category=puma" class="swiper-slide slide">
      <img src="brand/puma.png" alt="">
      <h3>Puma</h3>
   </a>

   <a href="category.php?category=uniqlo" class="swiper-slide slide">
      <img src="brand/uniqlo.png" alt="">
      <h3>Uniql</h3>
   </a>

   <a href="category.php?category=zara" class="swiper-slide slide">
      <img src="brand/zara.png" alt="">
      <h3>Zara</h3>
   </a>

   <a href="category.php?category=apple" class="swiper-slide slide">
      <img src="brand/apple.png" alt="">
      <h3>Apple</h3>
   </a>

   </div>

   <div class="swiper-pagination"></div>

   </div>

</section>
<section class="products">


   <div class="box-container">

   <?php
     $select_products = $conn->prepare("SELECT * FROM `products`"); 
     $select_products->execute();
     if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
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
      <div class="detail">
         <div class="total"><?= $fetch_product['price']; ?></div>
         <div class="carry"><span>ราคาค่าหิ้วสินค้า </span><?= $fetch_product['carry']; ?><span>/-</span></div>
         <div class="carry"><span>ราคาค่าส่งสินค้า </span><?= $fetch_product['shipping']; ?><span>/-</span></div>
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1"><br>
      </div>
      <input type="submit" value="add to cart" class="btn" name="add_to_cart">
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">ไม่มีสินค้า</p>';
   }
   ?>

   </div>

</section>








<!-- <?php include 'components/footer.php'; ?> -->

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<script src="js/script.js"></script>

<script>

var swiper = new Swiper(".home-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
    },
});

 var swiper = new Swiper(".category-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      0: {
         slidesPerView: 2,
       },
      650: {
        slidesPerView: 3,
      },
      768: {
        slidesPerView: 4,
      },
      1024: {
        slidesPerView: 5,
      },
   },
});

var swiper = new Swiper(".products-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      550: {
        slidesPerView: 2,
      },
      768: {
        slidesPerView: 2,
      },
      1024: {
        slidesPerView: 3,
      },
   },
});

</script>

</body>
</html>