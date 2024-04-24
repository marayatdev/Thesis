<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/wishlist_cart.php';

if(isset($_POST['delete_review'])){

   $delete_id = $_POST['delete_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

   $verify_delete = $conn->prepare("SELECT * FROM `reviews` WHERE id = ?");
   $verify_delete->execute([$delete_id]);
   
   if($verify_delete->rowCount() > 0){
      $delete_review = $conn->prepare("DELETE FROM `reviews` WHERE id = ?");
      $delete_review->execute([$delete_id]);
      $success_msg[] = 'Review deleted!';
   }else{  
      $warning_msg[] = 'Review already deleted!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shop</title>
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="quick-view">


   <?php
     $pid = $_GET['pid'];
     $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?"); 
     $select_products->execute([$pid]);
     if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){

         $total_ratings = 0;
         $rating_1 = 0;
         $rating_2 = 0;
         $rating_3 = 0;
         $rating_4 = 0;
         $rating_5 = 0;
 
         $select_ratings = $conn->prepare("SELECT * FROM `reviews` WHERE pid = ?");
         $select_ratings->execute([$fetch_product['id']]);
         $total_reivews = $select_ratings->rowCount();
         while($fetch_rating = $select_ratings->fetch(PDO::FETCH_ASSOC)){
             $total_ratings += $fetch_rating['rating'];
             if($fetch_rating['rating'] == 1){
                $rating_1 += $fetch_rating['rating'];
             }
             if($fetch_rating['rating'] == 2){
                $rating_2 += $fetch_rating['rating'];
             }
             if($fetch_rating['rating'] == 3){
                $rating_3 += $fetch_rating['rating'];
             }
             if($fetch_rating['rating'] == 4){
                $rating_4 += $fetch_rating['rating'];
             }
             if($fetch_rating['rating'] == 5){
                $rating_5 += $fetch_rating['rating'];
             }
         }
 
         if($total_reivews != 0){
             $average = round($total_ratings / $total_reivews, 1);
         }else{
             $average = 0;
         } 

   ?>
   <form action="" method="post" class="box" <?php if($fetch_product['stock'] == 0){echo 'disabled';}; ?>>
      <input type="hidden" name="pid" value="<?= $total_reivews; ?> ">
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
      <input type="hidden" name="brand" value="<?= $fetch_product['brand']; ?>">
      <input type="hidden" name="stock" value="<?= $fetch_product['stock']; ?>">
      <input type="hidden" name="stock" value="<?= $fetch_product['stock']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
      <input type="hidden" name="carry" value="<?= $fetch_product['carry']; ?>">
      <input type="hidden" name="shipping" value="<?= $fetch_product['shipping']; ?>">
      <input type="hidden" name="details" value="<?= $fetch_product['details']; ?>">
      <input type="hidden" name="quantity" value="<?= $fetch_product['quantity']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">

      <div class="row">
         <div class="image-container">
            <div class="main-image">
               <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
            </div>
            <div class="sub-image">
               <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
               <img src="uploaded_img/<?= $fetch_product['image_02']; ?>" alt="">
               <img src="uploaded_img/<?= $fetch_product['image_03']; ?>" alt="">
            </div>
         </div>
         <div class="content" >
            <div class="name"><?= $fetch_product['name']; ?></div>
            <div class="brand"><?= $fetch_product['brand']; ?></div>
            <div class="details"><?= $fetch_product['details']; ?></div>
            <div class="flex">
               <div class="total"><span>฿ </span><?= $fetch_product['price']; ?><span></span></div><br>
               <div class="carry"><span>฿ </span>ค่าหิ้วสินค้า <?= $fetch_product['carry']; ?><span></span></div>
               <div class="carry"><span>฿ </span>ค่าส่งสินค้า <?= $fetch_product['shipping']; ?><span></span></div>
               <input type="number" name="quantity" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
            </div>
            <div class="flex-btn">
            <?php if($fetch_product['stock'] != 0){ ?>
         <input type="submit" value="add to cart" class="btn sub" name="add_to_cart">
      <?php }; ?>
            </div>
         </div>
      </div>
   </form>

<section class="view-post">
<div class="row">
      <div class="col">
         <div class="flex">
            <div class="total-reviews">
               <h3><?= $average; ?><i class="fas fa-star"></i></h3>
               <p>จำนวนรับหิ้วสินค้า <?= $total_reivews; ?> ครั้ง</p>
            </div>
            <div class="total-ratings">
               <p>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <span><?= $rating_5; ?></span>
               </p>
               <p>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <span><?= $rating_4; ?></span>
               </p>
               <p>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <span><?= $rating_3; ?></span>
               </p>
               <p>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <span><?= $rating_2; ?></span>
               </p>
               <p>
                  <i class="fas fa-star"></i>
                  <span><?= $rating_1; ?></span>
               </p>
            </div>
         </div>
      </div>
   </div>
</section>


   <?php
      }
   }else{
      echo '<p class="empty">no products added yet!</p>';
   }
   ?>

</section>

<!-- reviews section starts  -->

<section class="reviews-container">

   <!-- <div class="heading"><h1></h1> <a href="add_review.php?pid=<?= $pid; ?>" class="inline-btn" style="margin-top: 0;">add review</a></div> -->

   <div class="box-container">

   <?php
      $select_reviews = $conn->prepare("SELECT * FROM `reviews` WHERE pid = ?");
      $select_reviews->execute([$pid]);
      if($select_reviews->rowCount() > 0){
         while($fetch_review = $select_reviews->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box" <?php if($fetch_review['user_id'] == $user_id){echo 'style="order: -1;"';}; ?>>
      <?php
         $select_user = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
         $select_user->execute([$fetch_review['user_id']]);
         while($fetch_user = $select_user->fetch(PDO::FETCH_ASSOC)){
      ?>
      <!-- <div class="user">
         <?php if($fetch_user['image'] != ''){ ?>
            <img src="uploaded_files/<?= $fetch_user['image']; ?>" alt="">
         <?php }else{ ?>   
            <h3><?= substr($fetch_user['name'], 0, 1); ?></h3>
         <?php }; ?>   
         <div>
            <p><?= $fetch_user['name']; ?></p>
            <span><?= $fetch_review['date']; ?></span>
         </div>
      </div> -->
      <?php }; ?>
      <div class="ratings">
         <?php if($fetch_review['rating'] == 1){ ?>
            <p style="background:var(--red);"><i class="fas fa-star"></i> <span><?= $fetch_review['rating']; ?></span></p>
         <?php }; ?> 
         <?php if($fetch_review['rating'] == 2){ ?>
            <p style="background:var(--orange);"><i class="fas fa-star"></i> <span><?= $fetch_review['rating']; ?></span></p>
         <?php }; ?>
         <?php if($fetch_review['rating'] == 3){ ?>
            <p style="background:var(--orange);"><i class="fas fa-star"></i> <span><?= $fetch_review['rating']; ?></span></p>
         <?php }; ?>   
         <?php if($fetch_review['rating'] == 4){ ?>
            <p style="background:var(--main-color);"><i class="fas fa-star"></i> <span><?= $fetch_review['rating']; ?></span></p>
         <?php }; ?>
         <?php if($fetch_review['rating'] == 5){ ?>
            <p style="background:var(--main-color);"><i class="fas fa-star"></i> <span><?= $fetch_review['rating']; ?></span></p>
         <?php }; ?>
      </div>
      <h3 class="title"><?= $fetch_review['title']; ?></h3>
      <?php if($fetch_review['description'] != ''){ ?>
         <p class="description"><?= $fetch_review['description']; ?></p>
      <?php }; ?>  
      <?php if($fetch_review['user_id'] == $user_id){ ?>

         <form action="" method="post" class="flex-btn">
            <input type="hidden" name="delete_id" value="<?= $fetch_review['id']; ?>">
            <a href="update_review.php?pid=<?= $fetch_review['id']; ?>" class="inline-option-btn">edit review</a>
            <input type="submit" value="delete review" class="inline-delete-btn" name="delete_review" onclick="return confirm('delete this review?');">
         </form>

      <?php }; ?>   
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">no reviews added yet!</p>';
      }
   ?>

   </div>

</section>


<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php include 'components/alers.php'; ?>
<script src="js/script.js"></script>
<script src="js/rating.js"></script>

</body>
</html>