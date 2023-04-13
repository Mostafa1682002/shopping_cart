<?php
include_once('connection.php');
$all_in_cart = $connection->query("SELECT * FROM `cart`");
$all_in_cart = $all_in_cart->fetchAll(PDO::FETCH_ASSOC);



$total = 0;
//Update Quanitity
if (isset($_POST['update_update_btn'])) {
   $id_update = $_POST['update_quantity_id'];
   $update_quanitiy = $_POST['update_quantity'];
   $update_update_btn = $connection->query("UPDATE `cart` SET `quanitiy`=$update_quanitiy WHERE `cart_id`=$id_update");
   if ($update_quanitiy) {
      header("Location:cart.php");
   }
}
//Delete One Product;
if (isset($_GET['remove'])) {
   $id_delete = $_GET['remove'];
   $deleted = $connection->query("DELETE FROM `cart` WHERE `cart_id`=$id_delete");
   if ($deleted) {
      header("Location: cart.php");
   }
}
//Delete All
if (isset($_GET['delete_all'])) {
   $delete_all = $connection->query("DELETE FROM `cart`");
   if ($delete_all) {
      header("Location:cart.php");
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shopping cart</title>
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'header.php'; ?>
   <div class="container">
      <section class="shopping-cart">
         <h1 class="heading">shopping cart</h1>
         <table>
            <thead>
               <th>image</th>
               <th>name</th>
               <th>price</th>
               <th>quantity</th>
               <th>total price</th>
               <th>action</th>
            </thead>
            <tbody>
               <?php foreach ($all_in_cart as $pro_cart) : ?>
                  <tr>
                     <td><img src="uploaded_img/<?php echo $pro_cart['image'] ?>" height="100" alt=""></td>
                     <td><?php echo $pro_cart['name'] ?> </td>
                     <td>$<?php echo $pro_cart['price'] ?>/-</td>
                     <td>
                        <form action="" method="post">
                           <input type="hidden" name="update_quantity_id" value="<?php echo $pro_cart['cart_id'] ?>">
                           <input type="number" name="update_quantity" min="1" value="<?php echo $pro_cart['quanitiy'] ?>">
                           <input type="submit" value="update" name="update_update_btn">
                        </form>
                     </td>
                     <td>$<?php
                           $sub_total = (int)$pro_cart['price'] * $pro_cart['quanitiy'];
                           $total += $sub_total;
                           echo $sub_total;
                           ?>/-</td>
                     <td><a href="cart.php?remove=<?php echo $pro_cart['cart_id'] ?>" onclick="return confirm('remove item from cart?')" class="delete-btn"> <i class="fas fa-trash"></i> remove</a></td>
                  </tr>
               <?php endforeach; ?>
               <tr class="table-bottom">
                  <td><a href="products.php" class="option-btn" style="margin-top: 0;">continue shopping</a>
                  </td>
                  <td colspan="3">grand total</td>
                  <td>$<?php echo $total; ?>/-</td>
                  <td><a href="cart.php?delete_all" onclick="return confirm('are you sure you want to delete all?');" class="delete-btn"> <i class="fas fa-trash"></i> delete all </a></td>
               </tr>
            </tbody>
         </table>
         <div class="checkout-btn">
            <a href="checkout.php" class="btn <?php echo $total < 1 ? 'disabled' : ''; ?>">procced to
               checkout</a>
         </div>
      </section>
   </div>

   <!-- custom js file link  -->
   <script src="js/script.js"></script>

</body>

</html>