<html>
<head>
    <meta charset="utf-8">
</head>
<body>

<?php
  $total = 1000;
  echo 'ราคารวม'.$total;
?>

<form name="payForm" method="POST" action="pay.php">
  <script type="text/javascript" src="https://cdn.omise.co/omise.js"
    data-key="pkey_test_5vehruuk0v38wnxs6no"
    data-image="http://bit.ly/customer_image"
    data-frame-label="Conpagno"
    data-button-label="ชำระเงิน"
    data-submit-label="ชำระเงิน"
    data-location="no"
    data-amount="<?php echo $total; ?>00"
    data-currency="thb"
    >
  </script>
  <!--the script will render <input type="hidden" name="omiseToken"> for you automatically-->
</form>

<!-- data-key="YOUR_PUBLIC_KEY" -->
</body>
</html>