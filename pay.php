<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
} else {
  $user_id = '';
}
;



$select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
$select_orders->execute([$user_id]);
if ($select_orders->rowCount() > 0) {
  while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
    $total = $fetch_orders['total_price'] . '00';
    $oid = $fetch_orders['id'];



    require_once dirname(__FILE__) . '/omise-php/lib/Omise.php';
    define('OMISE_API_VERSION', '2015-11-17');
    // define('OMISE_PUBLIC_KEY', 'PUBLIC_KEY');
// define('OMISE_SECRET_KEY', 'SECRET_KEY');
    define('OMISE_PUBLIC_KEY', 'pkey_test_5vehruuk0v38wnxs6no');
    define('OMISE_SECRET_KEY', 'skey_test_5vefyvabqou4l94b2vt');

    $charge = OmiseCharge::create(
      array(
        'amount' => $total,
        'currency' => 'thb',
        'card' => $_POST["omiseToken"]
      )
    );

    $status = ($charge['status']);
    $id = ($charge['id']);

    // print('<pre>');
    // print_r($charge); //ใส่DB ในฟิวorder
    // print('</pre>');


    $sql = "UPDATE orders SET payment_status = '$status', charge='$id' WHERE id = $oid";
    $select_orders = $conn->query($sql);



    
    // add_review.php?pid='.$fetch_orders['pid'].'

    if($status == 'successful'){
  //successful
  echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';

      echo ' <script>
  setTimeout(function() {
   swal({
       title: "ชำระเงินสำเร็จ",
       text: "by compagnoOfficail",
       type: "success"
   }, function() {
       window.location = "orders.php"; //หน้าที่ต้องการให้กระโดดไป
   });
}, 1000);
</script>';
}else{
  //error
  echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';

      echo ' <script>
  setTimeout(function() {
   swal({
       title: "ชำระเงินไม่สำเร็จ",
       text: "by compagnoOfficail",
       type: "error"
   }, function() {
       window.location = "pay.php"; //หน้าที่ต้องการให้กระโดดไป
   });
}, 1000);
</script>';
}

  }
}

?>