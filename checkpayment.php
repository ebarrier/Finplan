<?php
include "header.php";
require_once "config.php";
include "dbconn.php";
include "headershop.php";

if($_SESSION["orderid"] == null || $_SERVER['REQUEST_METHOD'] != "POST") {
    header("index.php");
}

$statement = $conn->prepare("
    UPDATE `order`
    SET paid=now()
    WHERE id = :orderid");
if (!$statement) die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
$statement->bindParam(':orderid', $_POST["orderid"]);
if (!$statement->execute()) die("Execute failed: (" . $statement->errno . ") " . $statement->error);
?>

<div class="content">
    <h2>Payment successful</h2>
    <h3>Thank you for shopping with us!</h3>

    <div id="backToMain">
        <a href="orders.php">View your orders</a><br>
        <a href="index.php">Go back to main page</a>
    </div>
</div>

<?php
include "footer.php";
?>
