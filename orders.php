<?php
include "header.php";
require_once "config.php";
include "dbconn.php";
include "headershop.php";

if($_SESSION["userid"]==null) {
    header("index.php");
}

//Function to display better unset dates
function clearUnsetDate($inputDate) {
    if($inputDate=="0000-00-00 00:00:00") {
        echo "-";
    } else {
        echo $inputDate;
    }
}

$statement = $conn->prepare("
    SELECT
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
    ORDER BY `order`.`created` DESC");
if (!$statement) die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
$statement->bindParam(':userid', $_SESSION["userid"]);
if (!$statement->execute()) die("Execute failed: (" . $statement->errorCode() . ") " . $statement->errorInfo()); ?>

<div class="content">
    <h2>My orders</h2>
    <ul>
    <?php
    $prevOrderId = null;
    while ($result = $statement->fetch(PDO::FETCH_ASSOC)) { 
        //We display the orders by order id
        if($prevOrderId != $result["orderid"]) { ?>
            <br>
            <li>Order #: <?php echo $result["orderid"]; ?></li>
            <?php
            //We calculate the total price of the order
            $statement2 = $conn->prepare("
                SELECT order_item.count*product.price AS subTotal
                FROM product
                JOIN order_item
                ON product.id = order_item.product_id
                WHERE order_item.order_id = :orderId"); 
            if (!$statement2) die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
            $statement2->bindParam(':orderId', $result["orderid"]);
            if (!$statement2->execute()) die("Execute failed: (" . $statement->errorCode() . ") " . $statement->errorInfo());
            $orderAmount=null;
            while ($result2 = $statement2->fetch(PDO::FETCH_ASSOC)) {
                $orderAmount += $result2["subTotal"];
            }?>
            <ul>
                <li>Total: <?php echo $orderAmount; ?>€</li>
                    <form method="post" action="paymentpage.php">
                        <input type="hidden" name="orderid" id="orderid" value="<?php echo $result["orderid"]; ?>"/>
                        <?php 
                        if ($result["paymentdate"] == "0000-00-00 00:00:00") {
                            echo "<input type=\"submit\" value=\"Pay now\"/>";
                        } ?>
                    </form>
                <li>Order date: <?php clearUnsetDate($result["ordercreated"]); ?></li>
                <li>Paid date: <?php clearUnsetDate($result["paymentdate"]); ?></li>
                <li>Shipment date: <?php clearUnsetDate($result["shipmentdate"]); ?></li>
        <?php 
        } else { ?> 
            <ul> <?php 
        } ?>
                <li>
                    <a href="description.php/?id=<?php echo $result["productid"]; ?>">
                        <?php echo $result["productname"]; ?></a>
                        (<?php echo $result["productprice"]; ?>€)
                        : x<?php echo $result["count"]; ?>
                </li>
            </ul>
        <?php
        $prevOrderId = $result["orderid"];
    }
    ?>

    </ul>
    <div id="backToMain">
        <a href="index.php">Go back to main page</a>
    </div>
</div>

<?php
include "footer.php";
?>
