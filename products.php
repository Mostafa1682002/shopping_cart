<?php
include('connection.php');
function getAllData($tabel)
{
   global $connection, $result;
   $result = $connection->query("SELECT * FROM $tabel");
   return $result->fetchAll(PDO::FETCH_ASSOC);
}
$products = getAllData('products');


if (isset($_POST['add_to_cart'])) {
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $quanitiy = 1;

   // check If Product Is Exist In Cart
   $alerdy = $connection->query("SELECT * FROM `cart` WHERE `name`='$product_name'");
   if ($alerdy->rowCount() > 0) {
      echo "<div class='message'><span>This Product IS Alerdy Exist In Cart</span> <i class='fas fa-times' onclick='this.parentElement.style.display = `none`;'></i> </div>";
   } else {
      $add_to_cart = $connection->query("INSERT INTO `cart` (`cart_id`, `name`, `price`, `image`, `quanitiy`) VALUES (NULL, '$product_name', '$product_price', '$product_image', $quanitiy)");
      if ($add_to_cart) {
         echo "<div class='message'><span>Sucessed Add Product TO Cart</span> <i class='fas fa-times' onclick='this.parentElement.style.display = `none`;'></i> </div>";
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
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <title>products</title>
</head>

<body>
   <?php include 'header.php'; ?>
   <div class="container">
      <section class="products">
         <h1 class="heading">latest products</h1>
         <div class="box-container">
            <?php foreach ($products as $product) : ?>
               <form action="" method="post">
                  <div class="box">
                     <!-- <img src="uploaded_img/" alt=""> -->
                     <img src="images/<?php echo $product['product_img'] ?>" alt="">
                     <h3><?php echo $product['product_name'] ?></h3>
                     <div class="price">$<?php echo $product['product_price'] ?>/-</div>
                     <input type="hidden" name="product_name" value="<?php echo $product['product_name'] ?>">
                     <input type="hidden" name="product_price" value="<?php echo $product['product_price'] ?>">
                     <input type="hidden" name="product_image" value="<?php echo $product['product_img'] ?>">
                     <input type="submit" class="btn" value="add to cart" name="add_to_cart">
                  </div>
               </form>
            <?php endforeach ?>
         </div>
      </section>
   </div>
   <!-- custom js file link  -->
   <script src="js/script.js"></script>

</body>

</html>