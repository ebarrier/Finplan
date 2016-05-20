<?php
include "header.php";
require_once "config.php";
include "dbconn.php";


if($_SESSION["userid"]==null) {
    header("index.php");
}



$statement = $conn->prepare("SELECT
  `order`.`id` AS `orderid`,
  `order`.`created` AS `ordercreated`,
  `order`.`paid` AS `paymentdate`,
  `order`.`shipped` AS `shipmentdate`,
  `product`.`id` AS `productid`,
  `product`.`name` AS `productname`,
  `product`.`price` AS `productprice`,
  `order_item`.`count` AS `count`
FROM `order_item`
    JOIN `order`
        ON `order_item`.`order_id` = `order`.`id`
    JOIN `product`
        ON `order_item`.`product_id` = `product`.`id`
WHERE `order`.`user_id` = :userid
ORDER BY `order`.`created` DESC
");

if (!$statement) die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
$statement->bindParam(':userid', $_SESSION["userid"]);
if (!$statement->execute()) die("Execute failed: (" . $statement->errno . ") " . $statement->error); ?>

<h1>Your orders</h1>
<ul>
<?php
$prevOrderId = null;
while ($result = $statement->fetch(PDO::FETCH_ASSOC)) { 
    if($prevOrderId != $result["orderid"]) { ?>
        <br>
        <li>Order #: <?php echo $result["orderid"]; ?>
            <ul>
                <li>Order date: <?php echo $result["ordercreated"]; ?></li>
                <li>Paid date: <?php echo $result["paymentdate"]; ?></li>
                <li>Shipment date: <?php echo $result["shipmentdate"]; ?></li>
    <?php } else { ?> <ul> <?php } ?>
                <li>
                    <a href="description.php/?id=<?php echo $result["productid"]; ?>">
                    <?php echo $result["productname"]; ?></a>
                    (<?php echo $result["productprice"]; ?>€)
                    : x<?php echo $result["count"]; ?>
                </li>
            </ul>
        </li>
        <?php
        $prevOrderId = $result["orderid"];
}
?>
</ul>
<br>
<a href="index.php">Go back to main page</a>

<?php
include "footer.php";
?>
