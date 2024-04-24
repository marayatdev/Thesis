<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['add_product'])){


   $brand = $_POST['brand'];
   $brand = filter_var($brand, FILTER_SANITIZE_STRING);

   
      $select_category = $conn->prepare("SELECT * FROM `category` WHERE brand = ?");
      $select_category->execute([$brand]);
   
      if($select_category->rowCount() > 0){
         $message[] = 'category brand already exist!';
      }else{
   
         $insert_category = $conn->prepare("INSERT INTO `category`(brand ) VALUES(?)");
         $insert_category->execute([$brand ]);
      }  

};
if(isset($_POST['add_product'])){

   $transport = $_POST['transport'];
   $transport = filter_var($transport, FILTER_SANITIZE_STRING);

   
      $select_transport = $conn->prepare("SELECT * FROM `transport` WHERE transport = ?");
      $select_transport->execute([$transport]);
   
      if($select_transport->rowCount() > 0){
         $message[] = 'category transport already exist!';
      }else{
   
         $insert_transport = $conn->prepare("INSERT INTO `transport`(transport ) VALUES(?)");
         $insert_transport->execute([$transport]);
      }  

};
if(isset($_POST['add_product'])){

   $country = $_POST['country'];
   $country = filter_var($country, FILTER_SANITIZE_STRING);

   
      $select_country = $conn->prepare("SELECT * FROM `country` WHERE country = ?");
      $select_country->execute([$country]);
   
      if($select_country->rowCount() > 0){
         $message[] = 'category country already exist!';
      }else{
   
         $insert_country = $conn->prepare("INSERT INTO `country`(country ) VALUES(?)");
         $insert_country->execute([$country]);
      }  

};


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

<?php include '../components/store_header.php'; ?>

<section class="add-products">

   <h1 class="heading">add category</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <div class="flex">
      <div class="inputBox">
            <span>บริษัทขนส่งสินค้า</span>
            <input type="text" class="box"  maxlength="100" placeholder="enter product name" name="transport"  >
         </div>
         <div class="inputBox">
            <span>Brand</span>
            <input type="text" class="box"  maxlength="100" placeholder="enter product name" name="brand">
         </div>
         <div class="inputBox">
            <span>country</span>
            <input type="text" class="box"  maxlength="100" value="Thailand (ไทย)" name="country">
         </div>
         
      
      <input type="submit" value="add product" class="btn" name="add_product">
   </form>

</section>









<script src="../js/admin_script.js"></script>
   
</body>
</html>