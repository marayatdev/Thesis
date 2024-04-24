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

<header class="header">

   <section class="flex">

      <a href="../admin/dashboard.php" class="logo">Admin<span>Panel</span></a>

      <nav class="navbar">
         <!-- <a href="../store/dashboard.php">home</a> -->
         <a href="../admin/categor.php">category</a>
         <!-- <a href="../store/placed_orders.php">orders</a> -->
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `stores` WHERE id = ?");
            $select_profile->execute([$store_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <p><?= $fetch_profile['name']; ?></p>
         <a href="../store/update_profile.php" class="btn">update profile</a>
         <div class="flex-btn">
            <!-- <a href="../store/register_store.php" class="option-btn">register</a> -->
            <!-- <a href="../store/store_login.php" class="option-btn">login</a> -->
         </div>
         <a href="../components/store_logout.php" class="delete-btn" onclick="return confirm('logout from the website?');">logout</a> 
      </div>

   </section>

</header>