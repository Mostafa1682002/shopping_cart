<?php
include "connection.php";
$allCart = $connection->query("SELECT * FROM `cart`");
$allCart = $allCart->fetchAll(PDO::FETCH_ASSOC);
$price_total = 0;

$all_product_in_cart = [];
foreach ($allCart as $ele) :
   $sub_total = (int)$ele['price'] * $ele['quanitiy'];
   $price_total += $sub_total;
   array_push($all_product_in_cart, ($ele['name'] . " (" . $ele['quanitiy'] . " x " . $ele['price'] . ")"));
endforeach;

if (isset($_POST['order_btn'])) {
   $name = $_POST['name'];
   $number = $_POST['number'];
   $email = $_POST['email'];
   $method = $_POST['method'];
   $flat = $_POST['flat'];
   $street = $_POST['street'];
   $city = $_POST['city'];
   $state = $_POST['state'];
   $country = $_POST['country'];
   $pin_code = $_POST['pin_code'];
   $total_product = implode(",", $all_product_in_cart);

   //ADD ORDER 
   $add_order = $connection->query("INSERT INTO `orders` (`id`, `name`, `email`, `number`, `method`, `flat`, `street`, `city`, `state`, `country`, `pin_code`, `total_products`, `total_price`) VALUES (NULL, '$name', '$email', '$number', '$method', '$flat', '$street', '$city', '$state', '$country', '$pin_code', '$total_product', '$price_total')");
   if ($add_order) {
      $connection->query("DELETE FROM `cart`");
   }
?>
   <div class='order-message-container'>
      <div class='message-container'>
         <h3>thank you for shopping!</h3>
         <div class='order-detail'>
            <span><?php echo $total_product ?></span>
            <span class='total'> total : $<?php echo $price_total ?>/- </span>
         </div>
         <div class='customer-details'>
            <p> your name : <span><?php echo $name ?></span> </p>
            <p> your number : <span><?php echo $number ?></span> </p>
            <p> your email : <span><?php echo $email ?></span> </p>
            <p> your address :
               <span><?php echo $flat . ", " . $street . ", " . $city . ", " . $state . ", " . $country . " - " . $pin_code ?></span>
            </p>
            <p> your payment mode : <span><?php echo $method ?></span> </p>
            <p>(*pay when product arrives*)</p>
         </div>
         <a href='products.php' class='btn'>continue shopping</a>
      </div>
   </div>
<?php
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>
   <?php include 'header.php'; ?>
   <div class="container">
      <section class="checkout-form">
         <h1 class="heading">complete your order</h1>
         <form action="" method="post">
            <div class="display-order">
               <?php foreach ($all_product_in_cart as $ele) : ?>
                  <span class="display-order"><?php echo $ele ?></span>
               <?php endforeach;
               ?>
               <span class="grand-total"> grand total : $<?php echo $price_total ?>/- </span>
            </div>
            <div class="flex">
               <div class="inputBox">
                  <span>your name</span>
                  <input type="text" placeholder="enter your name" name="name" required>
               </div>
               <div class="inputBox">
                  <span>your number</span>
                  <input type="number" placeholder="enter your number" name="number" required>
               </div>
               <div class="inputBox">
                  <span>your email</span>
                  <input type="email" placeholder="enter your email" name="email" required>
               </div>
               <div class="inputBox">
                  <span>payment method</span>
                  <select name="method">
                     <option value="cash on delivery" selected>cash on devlivery</option>
                     <option value="credit cart">credit cart</option>
                     <option value="paypal">paypal</option>
                  </select>
               </div>
               <div class="inputBox">
                  <span>address line 1</span>
                  <input type="text" placeholder="e.g. flat no." name="flat" required>
               </div>
               <div class="inputBox">
                  <span>address line 2</span>
                  <input type="text" placeholder="e.g. street name" name="street" required>
               </div>
               <div class="inputBox">
                  <span>city</span>
                  <input type="text" placeholder="e.g. mumbai" name="city" required>
               </div>
               <div class="inputBox">
                  <span>state</span>
                  <input type="text" placeholder="e.g. maharashtra" name="state" required>
               </div>
               <div class="inputBox">
                  <span>country</span>
                  <input type="text" placeholder="e.g. india" name="country" required>
               </div>
               <div class="inputBox">
                  <span>pin code</span>
                  <input type="text" placeholder="e.g. 123456" name="pin_code" required>
               </div>
            </div>
            <input type="submit" value="order now" onclick="confirm('Are You Soure')" name="order_btn" class="btn">
         </form>
      </section>
   </div>
   <!-- custom js file link  -->
   <script src="js/script.js"></script>

</body>

</html>