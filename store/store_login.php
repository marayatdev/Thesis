<?php

include '../components/connect.php';

session_start();

if(isset($_SESSION['store_id'])){
   $store_id = $_SESSION['store_id'];
}else{
   $store_id = '';
};

if(isset($_POST['submit'])){

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_store = $conn->prepare("SELECT * FROM `stores` WHERE email = ? AND password = ?");
   $select_store->execute([$email, $pass]);
   $row = $select_store->fetch(PDO::FETCH_ASSOC);

   if($select_store->rowCount() > 0){
      $_SESSION['store_id'] = $row['id'];
      header('location:dashboard.php');
   }else{
      $message[] = 'incorrect username or password!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/store_style.css">

</head>
<body>

<?php
   if(isset($message)){
      foreach($message as $message){
         echo '
         <div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }
?>

<section class="form-container">

   <form action="" method="post">
      <h3>store login</h3>
      <!-- <p>default username = <span>store</span> & password = <span>111</span></p> -->
      <input type="email" name="email" required placeholder="enter your email"   class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="enter your password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="login now" class="btn" name="submit">
      <a href="register_store.php"><p>สมัคสมาชิก</p></a>
   </form>

</section>
   
</body>
</html>