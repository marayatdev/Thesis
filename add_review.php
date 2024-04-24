<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_GET['pid'])){
   $pid = $_GET['pid'];
}else{
   $pid = '';
}

if(isset($_POST['submit'])){

   if($user_id != ''){

      $id = $user_id;
      $title = $_POST['title'];
      $title = filter_var($title, FILTER_SANITIZE_STRING);
      $description = $_POST['description'];
      $description = filter_var($description, FILTER_SANITIZE_STRING);
      $rating = $_POST['rating'];
      $rating = filter_var($rating, FILTER_SANITIZE_STRING);


      $verify_review = $conn->prepare("SELECT * FROM `reviews` WHERE pid = ? AND user_id = ?");
      $verify_review->execute([$pid, $user_id]);

      if($verify_review->rowCount() > 0){
         $warning_msg[] = 'Your review already added!';
      }else{
         $add_review = $conn->prepare("INSERT INTO `reviews`( id,user_id, pid,  rating, title, description) VALUES(?,?,?,?,?,?)");
         $add_review->execute([ $id,$user_id, $pid,  $rating, $title, $description]);
         $success_msg[] = 'Review added!';
      }

   }else{
      $warning_msg[] = 'Please login first!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>add review</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<!-- <?php include 'components/user_header.php'; ?> -->
<!-- add review section starts  -->


<section class="account-form">

   <form action="" method="post">
      <h3>post your review</h3>
      <p class="placeholder">review title <span>*</span></p>
      <input type="text" name="title"  maxlength="50" placeholder="<?= $fetch_profile["name"]; ?>" class="box">
      <p class="placeholder">review description</p>
      <textarea name="description" class="box" placeholder="enter review description" maxlength="1000" cols="30" rows="10"></textarea>
      <p class="placeholder">review rating <span>*</span></p>
      <select name="rating" class="box" required>
         <option value="1">1</option>
         <option value="2">2</option>
         <option value="3">3</option>
         <option value="4">4</option>
         <option value="5">5</option>
      </select>
      <input type="submit" value="submit review" name="submit" class="btn">
      <a href="profile.php?pid=<?= $pid; ?>" class="option-btn">go back</a>
   </form>

</section>

<!-- add review section ends -->














<!-- sweetalert cdn link  -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<?php include 'components/alers.php'; ?>

</body>
</html>