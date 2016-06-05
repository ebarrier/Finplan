<?php
include "header.php";
require_once "config.php";
include "dbconn.php";
include "headershop.php";

$statement = $conn->prepare("
    SELECT name, description, price 
    FROM product 
    WHERE id = :productID");
if (!$statement) die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
$statement->bindParam(':productID', $_GET["id"]); //GET to extract it from the page's URL (?id=12)
if (!$statement->execute()) die("Execute failed: (" . $statement->errno . ") " . $statement->error);
$row = $statement->fetch(PDO::FETCH_ASSOC);
?>
<div class="content">

    <h3 class="row col-12"><?php print($row["name"]); ?></h3>
    <h4 class="row col-12"><?php print($row["price"]); ?> eur</h4>

    <div class="description row">
        <p>
          <?php print($row["description"]); ?>
        </p>
    </div>

    <div class="row addToCart">
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
    <p>
    <a href="index.php">Go back to main page</a>
    </p>
    </div>

</div>

<?php
include 'footer.php';
?>
