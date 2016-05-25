<?php
include "header.php";

if($_SESSION["userid"]==null) {
    header("index.php");
}
?>

<h3>Your password has been successfully modified</h3>

<br>
<a href="profile.php">Go back to profile</a>
<br>
<a href="index.php">Go back to main page</a>
<?php
include 'footer.php';
?>
