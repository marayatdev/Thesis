<?php

include '../components/connect.php';

session_start();

$store_id = $_SESSION['store_id'];

if (!isset($store_id)) {
   header('location:store_login.php');
}
;



if (isset($_POST['add_product'])) {

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $carry = $_POST['carry'];
   $carry = filter_var($carry, FILTER_SANITIZE_STRING);
   $shipping = $_POST['shipping'];
   $shipping = filter_var($shipping, FILTER_SANITIZE_STRING);
   $stock = $_POST['stock'];
   $stock = filter_var($stock, FILTER_SANITIZE_STRING);
   $brand = $_POST['brand'];
   $brand = filter_var($brand, FILTER_SANITIZE_STRING);
   $country = $_POST['country'];
   $country = filter_var($country, FILTER_SANITIZE_STRING);
   $details = $_POST['details'];
   $details = filter_var($details, FILTER_SANITIZE_STRING);
   $sname = $_POST['sname'];
   $sname = filter_var($sname, FILTER_SANITIZE_STRING);
   $date = $_POST['date'];
   $date = filter_var($date, FILTER_SANITIZE_STRING);

   $image_01 = $_FILES['image_01']['name'];
   $image_01 = filter_var($image_01, FILTER_SANITIZE_STRING);
   $image_size_01 = $_FILES['image_01']['size'];
   $image_tmp_name_01 = $_FILES['image_01']['tmp_name'];
   $image_folder_01 = '../uploaded_img/' . $image_01;

   $image_02 = $_FILES['image_02']['name'];
   $image_02 = filter_var($image_02, FILTER_SANITIZE_STRING);
   $image_size_02 = $_FILES['image_02']['size'];
   $image_tmp_name_02 = $_FILES['image_02']['tmp_name'];
   $image_folder_02 = '../uploaded_img/' . $image_02;

   $image_03 = $_FILES['image_03']['name'];
   $image_03 = filter_var($image_03, FILTER_SANITIZE_STRING);
   $image_size_03 = $_FILES['image_03']['size'];
   $image_tmp_name_03 = $_FILES['image_03']['tmp_name'];
   $image_folder_03 = '../uploaded_img/' . $image_03;

   $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
   $select_products->execute([$name]);

   if ($select_products->rowCount() > 0) {
      $message[] = 'product name already exist!';
   } else {

      $insert_products = $conn->prepare("INSERT INTO `products`(store_id,sname,name,brand,country, details, price,carry,shipping,stock,date, image_01, image_02, image_03) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
      $insert_products->execute([$store_id,$sname, $name, $brand, $country, $details, $price, $carry, $shipping,$stock,$date, $image_01, $image_02, $image_03]);

      if ($insert_products) {
         if ($image_size_01 > 2000000 or $image_size_02 > 2000000 or $image_size_03 > 2000000) {
            $message[] = 'image size is too large!';
         } else {
            move_uploaded_file($image_tmp_name_01, $image_folder_01);
            move_uploaded_file($image_tmp_name_02, $image_folder_02);
            move_uploaded_file($image_tmp_name_03, $image_folder_03);
            $message[] = 'เพิ่มสินค้าเรียบร้อย';
         }

      }

   }

}
;

if (isset($_GET['delete'])) {

   $delete_id = $_GET['delete'];
   $delete_product_image = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
   $delete_product_image->execute([$delete_id]);
   $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_img/' . $fetch_delete_image['image_01']);
   unlink('../uploaded_img/' . $fetch_delete_image['image_02']);
   unlink('../uploaded_img/' . $fetch_delete_image['image_03']);
   $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
   $delete_product->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);
   $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE pid = ?");
   $delete_wishlist->execute([$delete_id]);
   header('location:products.php');
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>products</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/store_style.css">

</head>

<body>

   <?php include '../components/admin_header.php'; ?>

   <section class="add-products">

      <h1 class="heading">add product</h1>

      <form action="" method="post" enctype="multipart/form-data">
         <div class="flex">
            <div class="inputBox">
            <?php
               $select_sto = $conn->prepare("SELECT * FROM `stores`");
               $select_sto->execute();
               if ($select_sto->rowCount() > 0) {
                  while ($fetch_sto = $select_sto->fetch(PDO::FETCH_ASSOC)) {
                     $sto_name = $fetch_sto['sname'];
                     $sid = $fetch_sto['id'];
                  }

                  echo '<input type="input" name="sname" value=' .  $sto_name . '>';
               }

               ?>
               <span>ชื่อ/รุ่นสินค้า*</span>
               <input type="text" class="box" required maxlength="100" placeholder="กรุณากรอกชื่อ/รุ่นสินค้า" name="name">
            </div>
            <div class="inputBox">
               <span>ราคาสินค้า*</span>
               <input type="number" min="0" class="box" required placeholder="กรุณากรอกราคาสินค้า" max="9999999999" "
                  onkeypress="if(this.value.length == 10) return false;" name="price">
            </div>
            <div class="inputBox">
               <span>ราคาค่าหิ้วสินค้า*</span>
               <input type="number" min="0" class="box" required placeholder="กรุณากรอกราคาค่าหิ้วสินค้า" max="9999999999" 
                  onkeypress="if(this.value.length == 10) return false;" name="carry">
            </div>
            <div class="inputBox">
               <span>ราคาจัดส่งสินค้า*</span>
               <input type="number" min="0" class="box" placeholder="กรุณากรอกราคาจัดส่งสินค้า" required max="9999999999" 
                  onkeypress="if(this.value.length == 10) return false;" name="shipping">
            </div>
            <div class="inputBox">
               <span>จำนวนสินค้า*</span>
               <input type="number" min="0" class="box" placeholder="กรุณากรอกราคาจัดส่งสินค้า" required max="9999999999" 
                  onkeypress="if(this.value.length == 10) return false;" name="stock">
            </div>
            <select name="brand" class="inputBox">
               <?php
               $select_cat = $conn->prepare("SELECT * FROM `category`");
               $select_cat->execute();
               if ($select_cat->rowCount() > 0) {
                  while ($fetch_cat = $select_cat->fetch(PDO::FETCH_ASSOC)) {
                     $cat_id = $fetch_cat['cat_id'];
                     $brand = $fetch_cat['brand'];
                     echo '<option value=' . $brand . '>' . $brand . '</option>';

                  }
               }

               ?>
            </select>
            <select name="country" id="country" class="inputBox">
               <option value="" disabled selected>โปรดเลือกประเทศที่นำหิ้วสินค้า</option>
               <?php
               $select_country = $conn->prepare("SELECT * FROM `country`");
               $select_country->execute();
               if ($select_country->rowCount() > 0) {
                  while ($fetch_country = $select_country->fetch(PDO::FETCH_ASSOC)) {
                     $country_id = $fetch_country['country_id'];
                     $country = $fetch_country['country'];
                     echo '<option value=' . $country . '>' . $country . '</option>';

                  }
               }

               ?>
            </select>
            <div class="inputBox">
               <span>รูปภาพตัวอย่างที่ 1*</span>
               <input type="file" name="image_01"  accept="image/jpg, image/jpeg, image/png, image/webp" class="box"
                  required>
            </div>
            <div class="inputBox">
               <span>รูปภาพตัวอย่างที่ 2*</span>
               <input type="file" name="image_02" accept="image/jpg, image/jpeg, image/png, image/webp" class="box"
                  required>
            </div>
            <div class="inputBox">
               <span>รูปภาพตัวอย่างที่ 3*</span>
               <input type="file" name="image_03" accept="image/jpg, image/jpeg, image/png, image/webp" class="box"
                  required>
            </div>
            <div class="inputBox">
               <span>รายละเอียดสินค้า</span>
               <textarea name="details" class="box"placeholder="กรุณากรอกรายละเอียดสินค้า" required maxlength="500"
                  cols="30" rows="10"></textarea>
            </div>
            <div class="inputBox">
               <span>วันเวลากลับ</span>
               <input type="datetime-local" class="box" required maxlength="100" placeholder="กรุณากรอกชื่อ/รุ่นสินค้า" id="target_date" name="date">
            </div>

         </div>

         <input type="submit" value="add product" class="btn" name="add_product">
      </form>

   </section>



   <section class="show-products">

      <h1 class="heading">products added</h1>

      <div class="box-container">

         <?php
         $select_products = $conn->prepare("SELECT * FROM `products`");
         $select_products->execute();
         if ($select_products->rowCount() > 0) {
            while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
               ?>
               <div class="box">
                  <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
                  <div class="name">
                     รหัสสินค้า <?= $fetch_products['id']; ?> 
                  </div>
                  <div class="name">
                     <?= $fetch_products['name']; ?> 
                  </div>
                  <div class="details"><span>
                        <?= $fetch_products['brand']; ?> จำนวนสินค้า <?= $fetch_products['stock']; ?>
                     </span></div>
                  <div class="price">ราคาสินค้า <span>
                        <?= $fetch_products['price']; ?>
                     </span>/-</div>
                  <div class="price">ราคาค่าหิ้ว <span>
                        <?= $fetch_products['carry']; ?>
                     </span>/-</div>
                  <div class="price">ราคาค่าส่ง <span>
                        <?= $fetch_products['shipping']; ?>
                     </span>/-</div>
                  <div class="details"><span>
                        <?= $fetch_products['details']; ?>
                     </span></div>
                  <div class="flex-btn">
                     <a href="update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">update</a>
                     <a href="products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn"
                        onclick="return confirm('delete this product?');">delete</a>
                  </div>
               </div>
               <?php
            }
         } else {
            echo '<p class="empty">no products added yet!</p>';
         }
         ?>

      </div>

   </section>








   <script src="../js/admin_script.js"></script>

</body>

</html>