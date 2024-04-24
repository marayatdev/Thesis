<?php

include '../components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

   $fristname = $_POST['fristname'];
   $fristname = filter_var($fristname, FILTER_SANITIZE_STRING);
   $lastname = $_POST['lastname'];
   $lastname = filter_var($lastname, FILTER_SANITIZE_STRING);
   $sname = $_POST['sname'];
   $sname = filter_var($sname, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);
   $address = $_POST['address'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $tell = $_POST['tell'];
   $tell = filter_var($tell, FILTER_SANITIZE_STRING);
   $idcard = $_POST['idcard'];
   $idcard = filter_var($idcard, FILTER_SANITIZE_STRING);
   $agree = $_POST['agree'];
   $agree = filter_var($agree, FILTER_SANITIZE_STRING);

   $select_store = $conn->prepare("SELECT * FROM `stores` WHERE email = ?");
   $select_store->execute([$email]);

   if($select_store->rowCount() > 0){
      $message[] = 'username already exist!';
   }else{
      if($pass != $cpass){
         $message[] = 'confirm password not matched!';
      }else{
         $insert_store = $conn->prepare("INSERT INTO `stores`(fristname,lastname,sname,email, password,address,tell,idcard,agree) VALUES(?,?,?,?,?,?,?,?,?)");
         $insert_store->execute([$fristname,$lastname,$sname,$email, $cpass,$address,$tell,$idcard,$agree]);
         $message[] = 'new store registered successfully!';
      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register admin</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/store_style.css">

</head>
<body>


<section class="form-container">

   <form action="" method="post">
      <h3>store register</h3>
      <input type="text" name="fristname" required placeholder="กรุณากรอกชื่อจริง"   class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="text" name="lastname" required placeholder="กรุณากรอกนามสกุลจริง"   class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="text" name="sname" required placeholder="enter your username" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="text" name="address" required placeholder="กรุณากรอกที่อยู่"   class="box" >
      <input type="text" name="tell" required placeholder="กรุณากรอกเบอร์โทร" maxlength="10"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="text" name="idcard" required placeholder="กรุณากรอกหมายเลขบัตรประชาชน" maxlength="13"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="email" name="email" required placeholder="กรุณากรอกอีเมลย์" maxlength="50"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="กรุณากรอกรหัสผ่าน" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" required placeholder="กรุณายืนยันรหัสผ่าน" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')"><br>
      <div class="agree">
         <input type="checkbox" required name="agree" > &nbsp;<a href="agree.php" id="agree">โปรดอ่านและยินยอมกฎเกณท์ข้อมังคับของทางแพลตฟอร์ม</a> <br>
         <input type="submit" value="register now" class="btn" name="submit">
      </div>
      <a href="store_login.php"><p>เข้าสู่ระบบ</p></a>
   </form>

</section>












<script src="../js/admin_script.js"></script>
   
</body>
</html>