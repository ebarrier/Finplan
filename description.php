<?php
include "header.php";
require_once "config.php";
include "dbconn.php";

$statement = $conn->prepare("
    SELECT name, description, price 
    FROM product 
    WHERE id = :productID");
if (!$statement) die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
$statement->bindParam(':productID', $_GET["id"]); //GET to extract it from the page's URL (?id=12)
if (!$statement->execute()) die("Execute failed: (" . $statement->errno . ") " . $statement->error);
$row = $statement->fetch(PDO::FETCH_ASSOC);
?>

<h1><?php print($row["name"]); ?></h1>
<h2><?php print($row["price"]); ?> eur</h2>

<p>
  <?php print($row["description"]); ?>
</p>

<form method="post" action="cart.php">
  <input type="hidden" name="id" value="<?=$_GET["id"];?>"/>
<!--
  <input type="hidden" name="count" value="1"/>
-->
  <input type="submit" value="Add to cart"/>
	<select name="count">
	  <option value="1">1</option>
	  <option value="2">2</option>
	  <option value="3">3</option>
	  <option value="4">4</option>
	</select>
</form>


<a href="index.php">Go back to main page</a>
<?php
include 'footer.php';
?>
