<?php
include "header.php";
require_once "config.php";
include "dbconn.php";

//if($_SESSION["orderid"]==null) {
    //header("index.php");
//}

var_dump($_POST);
echo '<pre>';
var_dump($_SESSION);
echo '</pre>';

if (array_key_exists("orderid", $_POST)) {
    $orderid = $_POST["orderid"];
    echo "Post orderid: ", $_POST["orderid"];
} 
elseif (array_key_exists("orderid", $_SESSION)) {
    $orderid = $_SESSION["orderid"];
    echo "Session orderid: ", $_SESSION["orderid"];
} 
else {
    header('index.php');
}

$statement = $conn->prepare(
                "SELECT order_item.count*product.price AS subTotal
                FROM product
                JOIN order_item
                ON product.id = order_item.product_id
                WHERE order_item.order_id = :orderId"); 
if (!$statement) die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
$statement->bindParam(':orderId', $orderid);
if (!$statement->execute()) die("Execute failed: (" . $statement->errno . ") " . $statement->error);
$orderAmount=null;
while ($result = $statement->fetch(PDO::FETCH_ASSOC)) {
    $orderAmount += $result["subTotal"];
}?>

<h1>Amount to pay</h1>
<h3><?php echo $orderAmount; ?></h3>

<form method="post" action="checkpayment.php">
    <div>
    <label for="cbnum">Bank card number</label>
    <input type="text" name="cbnum" id="cbnum" required/>
    </div> 

    <div>
    <label for="CVV">Verification digits</label>
    <input type="text" name="CVV" id="CVV" required/>
    </div>

    <div>
    <label for="expiredate">Date of expiry</label>
        <select name="expiremonth" id="expiremonth" onchange="" size="1">
            <option value="null">-</option>
            <option value="01">January</option>
            <option value="02">February</option>
            <option value="03">March</option>
            <option value="04">April</option>
            <option value="05">May</option>
            <option value="06">June</option>
            <option value="07">July</option>
            <option value="08">August</option>
            <option value="09">September</option>
            <option value="10">October</option>
            <option value="11">November</option>
            <option value="12">December</option>
        </select>
        <select name="expireyear" id="expireyear" onchange="" size="1">
            <option value="null">-</option>
            <?php 
                $thisyear = intval(Date('Y'));
                $stop=$thisyear+8;
                for($i=$thisyear; $i<=$stop; $i++) {
                    echo "<option value=".$i.">".$i."</option>";
                }
            ?>
        </select>
    </div>

    <div>
        <label for="namecard">Name on the card</label>
        <input type="text" name="namecard" id="namecard" required/>
    </div>

    <div>
        <input type="hidden" name="orderid" id="orderid" value="<?php echo $orderid; ?>"/>
    </div>

    <div>
        <input type="submit" value="Pay"/>
    </div>
</form>

<br>
<a href="index.php">Go back to main page</a>

<?php
include "footer.php";
?>


