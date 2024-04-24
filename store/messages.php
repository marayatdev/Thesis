<?php

include '../components/connect.php';

session_start();

$store_id = $_SESSION['store_id'];

if(!isset($store_id)){
   header('location:store_login.php');
};

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_message = $conn->prepare("DELETE FROM `messages` WHERE id = ?");
   $delete_message->execute([$delete_id]);
   header('location:messages.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>messages</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/store_style.css">
   <script>
        function countdown(endDate) {
            var timer, days, hours, minutes, seconds;

            endDate = new Date(endDate).getTime();

            if (isNaN(endDate)) {
                return;
            }

            timer = setInterval(calculate, 1000);

            function calculate() {
                var startDate = new Date();
                startDate = startDate.getTime();

                var timeRemaining = parseInt((endDate - startDate) / 1000);

                if (timeRemaining >= 0) {
                    days = parseInt(timeRemaining / 86400);
                    timeRemaining = timeRemaining % 86400;

                    hours = parseInt(timeRemaining / 3600);
                    timeRemaining = timeRemaining % 3600;

                    minutes = parseInt(timeRemaining / 60);
                    timeRemaining = timeRemaining % 60;

                    seconds = parseInt(timeRemaining);

                    document.getElementById("days").innerHTML = pad(days);
                    document.getElementById("hours").innerHTML = pad(hours);
                    document.getElementById("minutes").innerHTML = pad(minutes);
                    document.getElementById("seconds").innerHTML = pad(seconds);
                } else {
                    clearInterval(timer);
                    document.getElementById("countdown").innerHTML = "Countdown has ended!";
                }
            }

            function pad(n) {
                return (n < 10 ? '0' : '') + n;
            }
        }
    </script>

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="contacts">

<h1 class="heading">messages</h1>

<div class="box-container">

   <?php
      $select_messages = $conn->prepare("SELECT * FROM `messages`");
      $select_messages->execute();
      if($select_messages->rowCount() > 0){
         while($fetch_message = $select_messages->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
   <p> user id : <span><?= $fetch_message['user_id']; ?></span></p>
   <p> message : <span><?= $fetch_message['message']; ?></span></p>
   <a href="messages.php?delete=<?= $fetch_message['id']; ?>" onclick="return confirm('delete this message?');" class="delete-btn">delete</a>
   <a href="messages.php?delete=<?= $fetch_message['id']; ?>" onclick="return confirm('delete this message?');" class="delete-btn">Reply</a>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">you have no messages</p>';
      }
   ?>

</div>

</section>












<script src="../js/admin_script.js"></script>
   
</body>
</html>