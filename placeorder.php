<?php
include "header.php";
require_once "config.php";
include "dbconn.php";

if($_SESSION["userid"]==null) {
    header("index.php");
}

$statement = $conn->prepare("INSERT INTO `order` (user_id) VALUES (:userid)");
if (!$statement) die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
$statement->bindParam(':userid', $_SESSION["userid"]);
if (!$statement->execute()) die("Execute failed: (" . $statement->errno . ") " . $statement->error);

$order_id = $conn->lastInsertId(); // This contains the ID for the inserted order

// This inserts rows to order_item table
$statement = $conn->prepare(
    "INSERT INTO order_item (order_id, product_id, count) VALUES (:orderid,:productid,:productcount)");
if (!$statement) die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
foreach ($_SESSION["cart"] as $product_id => $count) {
    $statement->bindParam(':orderid', $order_id);
    $statement->bindParam(':productid', $product_id);
    $statement->bindParam(':productcount', $count);
	if (!$statement->execute()) {
		die("Execute failed: (" . $statement->errno . ") " . $statement->error);
	}
}

// We reset shopping cart:
$_SESSION["cart"] = array();
header('Location: orders.php');
?>


