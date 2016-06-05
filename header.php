<?php
// Set session variables
session_start();
if (!array_key_exists("cart", $_SESSION)) {
    $_SESSION["cart"] = array();
    // Here we store product -> count mapping
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<meta name="description" content="Etienne's webshop">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="css/main.css">
<title>Etienne's webshop</title>
</head>


<body>
<div id="content">
