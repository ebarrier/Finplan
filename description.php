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
?>

<?php 
$statement = $conn->prepare("SELECT name, description, price FROM product WHERE id = :productID");
$statement->bindParam(':productID', $_GET["id"]); //GET to extract it from the page's URL (?id=12)
$statement->execute();
$row = $statement->fetch(PDO::FETCH_ASSOC);
?>

<h1><?php print($row["name"]); ?></h1>
<h2><?php print($row["price"]); ?> eur</h2>

<p>
  <?php print($row["description"]); ?>
</p>

<form method="post" action="cart.php">
  <input type="hidden" name="id" value="<?=$_GET["id"];?>"/>
  <input type="hidden" name="count" value="1"/>
  <input type="submit" value="Add to cart"/>
<select name="count">
  <option value="one">1</option>
  <option value="two">2</option>
  <option value="three">3</option>
  <option value="four">4</option>
</select>
</form>


<a href="index.php">Go back to main page</a>
<?php
include 'footer.php';
?>
