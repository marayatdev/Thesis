<?php

include '../components/connect.php';

?>
<select name="">
<?php
      $select_products = $conn->prepare("SELECT * FROM `category`");
      $select_products->execute();
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
              $cat_id = $fetch_products['cat_id'];  
              $brand = $fetch_products['brand']; 
               echo '<option value='.$cat_id.'>'.$brand.'</option>';
            
               
               }
         }

?>
</select>
