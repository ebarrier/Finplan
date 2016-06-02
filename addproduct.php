<?php
include "header.php";
require_once "config.php";
include "dbconn.php";

if (array_key_exists("uploaded_image", $_FILES)) {
    if ($_FILES["uploaded_image"]["error"] == 1) die("Too big image!"); // File size check
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimetype = finfo_file($finfo, $_FILES["uploaded_image"]["tmp_name"]);
    if (strpos($mimetype, "image/") != 0) // This is basically mimetype.startswith("image/")
        die("Go away! Only images allowed!");
    $checksum = sha1(file_get_contents(
        $_FILES["uploaded_image"]["tmp_name"])) . "." . 
        pathinfo($_FILES["uploaded_image"]["name"], PATHINFO_EXTENSION);

    // Keep the original image in uploads/ folder
    if (!file_exists("uploads/" . $checksum)) {
        copy(
          $_FILES["uploaded_image"]["tmp_name"],
          "uploads/" . $checksum);
    }
	
	// Generate thumbnail, this assumes you have created thumbnails/ folder and set permissions to 777
	if (!file_exists("thumbnails/" . $checksum)) {
		$im = new Imagick("uploads/" . $checksum);
		$im->thumbnailImage(128, 0); // Width of 128px and automatically determine height based on aspect ratio
		$im->writeImage("thumbnails/" . $checksum);
	}
	
	// Generate smaller version of the image
	if (!file_exists("small/" . $checksum)) {
		$im = new Imagick("uploads/" . $checksum);
		$im->thumbnailImage(960, 0); // Width of 960px and automatically determined height
		$im->writeImage("small/" . $checksum);
	}

    //DB query
    $statement = $conn->prepare("INSERT INTO product (name, description, type, price, hash) VALUES (:name, :desc, :type, :price, :hash)");
    $statement->bindParam(':name', $_POST["product_name"]);
    $statement->bindParam(':desc', $_POST["product_desc"]);
    $statement->bindParam(':type', $_POST["product_type"]);
    $statement->bindParam(':price', $_POST["product_price"]);
    $statement->bindParam(':hash', $checksum);
    if (!$statement->execute()) die("Execute failed: (" . $statement->errno . ") " . $statement->error);

    ?>
    <p>The product <?=$_POST["product_name"]?> has been added successfully!</p>
    
<?php
}
?>
<h1>Add a new product</h1>
<ul>
</ul>
<form method="post" enctype="multipart/form-data">
    <label for="product_name">Product name: </label>
    <input type="text" name="product_name" placeholder="Product name" required/><br>
    
    <label for="product_type">Product type: </label>
    <input type="text" name="product_type" placeholder="Product type" required/><br>
    
    <label for="product_price">Product price: </label>
    <input type="number" name="product_price" placeholder="Product price" min="0" required/><br>
    
    <label for="product_desc">Product description: </label><br>
    <textarea name="product_desc" rows="10" cols="50"></textarea><br>
    
    <input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
    Select product picture (max 2M): <input id="file" type="file" name="uploaded_image" accept="image/*"/><br>
    <input type="submit" value="Add product"/>
</form><br>

<?php
$statement = $conn->prepare("SELECT id, name, price FROM product");
//$result = $conn->query("SELECT id, name, price FROM product");
$statement->execute();

while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
    echo "<li><a href=\"description.php?id=" . $row["id"] . "\">" .  $row["name"] . "</a> " . $row["price"] . "eur</li>";
}
?>

<br>
<a href="index.php">Go back to main page</a>

<?php
include "footer.php";
?>
