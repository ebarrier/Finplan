<?php
include "header.php";
require_once "config.php";
include "dbconn.php";

var_dump($_POST);

$statement = $conn->prepare(
    "UPDATE `order`
    SET paid=now()
    WHERE id = :orderid");
if (!$statement) die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
$statement->bindParam(':orderid', $_POST["orderid"]);
if (!$statement->execute()) die("Execute failed: (" . $statement->errno . ") " . $statement->error);
?>

<h1>Payment successful</h1>

<br>
<a href="orders.php">View your orders</a>
<br>
<a href="index.php">Go back to main page</a>

<?php
include "footer.php";
?>
