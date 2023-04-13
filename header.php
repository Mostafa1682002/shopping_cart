<?php
include_once('connection.php');
$cart = $connection->query("SELECT * FROM `cart`");
$count = $cart->rowCount();

?>
<header class="header">

   <div class="flex">

      <a href="admin.php" class="logo">foodies</a>

      <nav class="navbar">
         <a href="admin.php">add products</a>
         <a href="products.php">view products</a>
      </nav>


      <a href="cart.php" class="cart">cart <span><?php echo $count ?></span> </a>

      <div id="menu-btn" class="fas fa-bars"></div>

   </div>

</header>