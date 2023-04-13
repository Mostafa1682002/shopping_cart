<?php
include('connection.php');
function getAllData($tabel)
{
   global $connection, $result;
   $result = $connection->query("SELECT * FROM $tabel");
   return $result->fetchAll(PDO::FETCH_ASSOC);
}

$products = getAllData('products');
$Erorrs = [];
//Add Product
if (isset($_POST['add_product'])) {
   $Product_name = $_POST['p_name'];
   $Product_price = $_POST['p_price'];
   $Product_image = isset($_FILES['p_image']) ? $_FILES['p_image']['name'] : '';

   if (empty($Product_name)) {
      $Erorrs['product_name_required'] = "Product Name Is Reuired";
   }
   if (empty($Product_price)) {
      $Erorrs['product_price_required'] = "Product Price Is Reuired";
   }
   if (empty($Product_image)) {
      $Erorrs['product_image_required'] = "Product Image Is Reuired";
   }

   if (empty($Erorrs)) {
      $insert = $connection->query("INSERT INTO `products` (`product_id`, `product_name`, `product_price`, `product_img`) VALUES (NULL, '$Product_name', '$Product_price', '$Product_image'); ");
      if ($insert) {
         $from = $_FILES['p_image']['tmp_name'];
         $to = "uploaded_img/$Product_image";
         move_uploaded_file($from, $to);
         echo "<div class='message'><span>Sucessed Added Product</span> <i class='fas fa-times' onclick='this.parentElement.style.display = `none`;'></i> </div>";
         header("Location:admin.php");
         exit();
      }
   }
}

//Delete Product
if (isset($_GET['delete'])) {
   $id = $_GET['delete'];
   $proDel = $connection->query("SELECT * FROM `products` WHERE `product_id`=$id");
   $pro = $proDel->fetch(PDO::FETCH_ASSOC);
   $pro_img = $pro['product_img'];
   if ($proDel->rowCount() == 1) {
      $connection->query("DELETE FROM products WHERE `product_id`=$id ");
      unlink("uploaded_img/$pro_img");
      echo "<div class='message'><span>Sucessed Delete Product</span> <i class='fas fa-times' onclick='this.parentElement.style.display = `none`;'></i> </div>";
      header('Location:admin.php');
      exit();
   }
}

// Edit Product
if (isset($_GET['edit'])) {
   $edit_id = $_GET['edit'];
   $edit_pro = $connection->query("SELECT * FROM `products` WHERE `product_id`=$edit_id");
   $edit_pro = $edit_pro->fetch(PDO::FETCH_ASSOC);

   if (isset($_POST['update_product'])) {
      $new_price = $_POST['update_p_price'];
      $new_name = $_POST['update_p_name'];
      $new_imge = isset($_FILES['update_p_image']) ? $_FILES['update_p_image']['name'] : '';
      if (empty($new_name)) {
         $Erorrs['update_name_required'] = "Product Name Is Reuired";
      }
      if (empty($new_price)) {
         $Erorrs['update_price_required'] = "Product Price Is Reuired";
      }
      //Check if No Error To Update Product
      if (empty($Erorrs)) {
         if (empty($new_imge)) {
            $update = $connection->query("UPDATE  `products` SET `product_name`='$new_name' , `product_price`='$new_price' WHERE `product_id`=$edit_id");
         } else {
            $update = $connection->query("UPDATE  `products` SET `product_name`='$new_name' , `product_price`='$new_price' ,`product_img`='$new_imge'WHERE `product_id`=$edit_id");
            $from = $_FILES['update_p_image']['tmp_name'];
            $to = "uploaded_img/$new_imge";
            move_uploaded_file($from, $to);
            //Delete Old Image
            $old_image = $edit_pro['product_id'];
            unlink("/uploaded_img/$old_image");
         }
         //Check If Update Or No
         if ($update) {
            echo "<div class='message'><span>Sucessed Update Product</span> <i class='fas fa-times' onclick='this.parentElement.style.display = `none`;'></i> </div>";
            header('Location: admin.php');
            exit();
         }
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
   <title>admin panel</title>
   <!-- font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" />
   <!-- BootStarp  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css">
   <!-- CSS File  -->
   <link rel="stylesheet" href="css/style.css">
</head>

<body>
   <?php include 'header.php'; ?>
   <div class="container">
      <section>
         <form action="" method="post" class="add-product-form" enctype="multipart/form-data">
            <h3>add a new product</h3>
            <input type="text" name="p_name" placeholder="enter the product name" class="box input_required" autocomplete="off">
            <?php
            if (isset($Erorrs['product_name_required'])) {
               echo "<div class='alert alert-danger'>" . $Erorrs['product_name_required'] . "</div>";
            }
            ?>
            <input type="number" name="p_price" min="0" placeholder="enter the product price" class="box input_required" autocomplete="off">
            <?php
            if (isset($Erorrs['product_price_required'])) {
               echo "<div class='alert alert-danger'>" . $Erorrs['product_price_required'] . "</div>";
            }
            ?>
            <input type="file" name="p_image" accept="image/*" class="box input_required">
            <?php
            if (isset($Erorrs['product_image_required'])) {
               echo "<div class='alert alert-danger'>" . $Erorrs['product_image_required'] . "</div>";
            }
            ?>
            <input type="submit" value="add the product" name="add_product" class="btn">
         </form>
      </section>
      <section class="display-product-table">
         <table>
            <thead>
               <th>product image</th>
               <th>product name</th>
               <th>product price</th>
               <th>action</th>
            </thead>
            <tbody>
               <?php
               if (!empty($products)) {
                  foreach ($products as $product) :
               ?>
                     <tr>
                        <td><img src="uploaded_img/<?php echo $product['product_img'] ?>" height="100" alt=""></td>
                        <td><?php echo $product['product_name'] ?></td>
                        <td>$<?php echo $product['product_price'] ?>/-</td>
                        <td>
                           <a href="admin.php?delete=<?php echo $product['product_id'] ?>" class="delete-btn" onclick="return confirm('are your sure you want to delete this?');"> <i class="fas fa-trash"></i> delete </a>
                           <a href="admin.php?edit=<?php echo $product['product_id'] ?>" class="option-btn"> <i class="fas fa-edit"></i> update </a>
                        </td>
                     </tr>
               <?php endforeach;
               } else {
                  echo "<div class='empty'>No Products Added</div>";
               } ?>
            </tbody>
         </table>
      </section>
      <section class="edit-form-container">
         <?php
         if (isset($_GET['edit'])) {
         ?>
            <form action="" method="post" enctype="multipart/form-data">
               <img src="uploaded_img/<?php echo $edit_pro['product_img'] ?>" height="200" alt="">
               <input type="hidden" name="update_p_id" value="<?php echo $edit_pro['product_id'] ?>">
               <input type="text" class="box" name="update_p_name" value="<?php echo $edit_pro['product_name'] ?>">
               <?php
               if (isset($Erorrs['update_name_required'])) {
                  echo "<div class='alert alert-danger'>" . $Erorrs['update_name_required'] . "</div>";
               }
               ?>
               <input type="number" min="0" class="box" name="update_p_price" value="<?php echo $edit_pro['product_price'] ?>">
               <?php
               if (isset($Erorrs['update_price_required'])) {
                  echo "<div class='alert alert-danger'>" . $Erorrs['update_price_required'] . "</div>";
               }
               ?>
               <input type="file" class="box" name="update_p_image" accept="image/*">
               <input type="submit" value="update the prodcut" name="update_product" class="btn">
               <input type="reset" value="cancel" id="close-edit" class="option-btn">
            </form>
         <?php
            echo "<script>document.querySelector('.edit-form-container').style.display = 'flex';</script>";
         }
         ?>
      </section>
   </div>

   <!-- custom js file link  -->
   <script src="js/script.js"></script>
   <!-- BootStrap  -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
   </script>
</body>

</html>