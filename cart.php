<?php
include "header.php";
require_once "config.php";

try {
    $conn = new PDO('mysql:host='.DB_SERVER.';dbname='.DB_NAME.';charset=utf8', DB_USER, DB_PASS);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected successfully";
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//var_dump($_POST);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  // If method is POST, we update the cart. Else GET, just display the cart.
  $product_id = intval($_POST["id"]); //intval to make the value an integer
  if (array_key_exists($product_id, $_SESSION["cart"])) {
      $_SESSION["cart"][$product_id] += intval($_POST["count"]); //$_SESSION["cart"][$product_id] is a dictionnary.  [$product_id] is the key and the value is the count.
  } else {
      $_SESSION["cart"][$product_id] = intval($_POST["count"]);
  }

//var_dump($_SESSION);
  
  if ($_SESSION["cart"][$product_id]<=0) {
    unset($_SESSION["cart"][$product_id]);
  }
}
?>

<h2>Your cart</h2>
<h2>Products in your shopping cart</h2>

<?php 
//var_dump($_SESSION["cart"]);

$statement = $conn->query('SELECT id, name, price FROM product');
$statement or die("Connection to database failed:".$conn->connect_error); //if result is true, the second expression is not checked.

while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
  $product_id= $row['id'];
  if (array_key_exists($row['id'], $_SESSION["cart"])) {
  $count = $_SESSION["cart"][$product_id];?>
  <li>
    <?php echo $count; ?> x <a href="description.php?id=<?php echo $product_id ?>">
      <?php echo $row['name'] ?></a>
      <?php echo $row['price'] ?>€ => <?php echo $row['price']*$count, '€'; ?>
    <form method="post">
      <input type="hidden" name="id" value="<?php echo $product_id; ?>"/>
      <input type="hidden" name="count" value="-1"/>
      <input type="submit" value="Remove one"/>
    </form>
  </li>

  <?php    
  }
}
?>

<br>
<a href="index.php">Go back to main page</a>

<?php
include "footer.php";
?>
