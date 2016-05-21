<?php
include "header.php";
require_once "config.php";
include "dbconn.php";

//var_dump($_POST);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  // If method is POST, we update the cart. Else GET, just display the cart.
  $product_id = intval($_POST["id"]); //intval to make the value an integer
  if (array_key_exists($product_id, $_SESSION["cart"])) {
      $_SESSION["cart"][$product_id] += intval($_POST["count"]); //$_SESSION["cart"][$product_id] is a dictionnary.  [$product_id] is the key and the value is the count.
  } else {
      $_SESSION["cart"][$product_id] = intval($_POST["count"]);
  }

  if ($_SESSION["cart"][$product_id]<=0) {
    unset($_SESSION["cart"][$product_id]);
  }
}
?>
    
<h2>Your cart</h2>

<?php 
$grantotal=0;
if($_SESSION["cart"]==null) {
    echo "<h3>Your cart is empty...</h3>";
} else {
    echo"<h3>Products in your shopping cart</h3>";
    $statement = $conn->query('SELECT id, name, price FROM product');
    $statement or die("Connection to database failed:".$conn->connect_error); //if result is true, the second expression is not checked.
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
      $product_id= $row['id'];
      if (array_key_exists($row['id'], $_SESSION["cart"])) {
        $count = $_SESSION["cart"][$product_id];
        $grantotal =  $grantotal + $row['price']*$count;?>
        <li>
            <?php echo $count; ?> x <a href="description.php?id=<?php echo $product_id ?>">
            <?php echo $row['name'] ?></a>
            <?php echo $row['price'] ?>€ => 
            <?php echo $row['price']*$count, '€'; ?>
          <form method="post">
            <input type="hidden" name="id" value="<?php echo $product_id; ?>"/>
            <input type="hidden" name="count" value="-1"/>
            <input type="submit" value="Remove one"/>
          </form>
        </li>
      <?php    
      }
    }
    if($_SESSION["userid"]==null) {?>
        <p>You must log in or sign up to checkout</p>
        <form action="login.php" method="post">
            <input type="text" name="username/email" placeholder="username or email" required/>
            <input type="password" name="password" placeholder="password" required/>
            <input type="submit" value="Log in!"/>
        </form>
        
        <form action="registration.php" method="post">
            <input type="submit" value="Sign up!"/>
        </form> 

    <?php 
    } elseif ($_SESSION["userid"]!=null) { ?>
        <p>
        <form method="post" action="placeorder.php">
            <?php echo 'Total price: ', $grantotal, '€';?>
            <input type="submit" value="checkout"/>
            When clicking "Checkout" you will be redirected on the bank card payment page
        </form>
        </p>
    <?php
    }
}
?>

<br>
<a href="index.php">Go back to main page</a>

<?php
include "footer.php";
?>
