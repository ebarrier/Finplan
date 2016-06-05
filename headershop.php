<div class="header row">
    <div class="col-2">&nbsp;</div>
    <h1 class="col-8"><img src="css/webshoplogo.png" alt="Etienne's Webshop" height="115"></h1>

    <?php
    if (array_key_exists("userid", $_SESSION) && $_SESSION["userid"] != NULL) {
        //If the $_SESSION["userid"] is set we say hello with his name
        //var_dump($_SESSION["userid"]); //This is just to show the content of $_SESSION variable
        $results = $conn->query("
            SELECT * 
            FROM user 
            WHERE id = " . $_SESSION["userid"]);
        $row = $results->fetch(PDO::FETCH_ASSOC);?>
        <div class="greeting col-1">
            <p>Hello <?=$row["fname"]?></p>
        </div>
        <div class="logOut col-1">
            <p><a href="logout.php">Sign out</a></p>
        </div>
    </div> <!--header row finishes here-->
    <div class="menu row">
        <ul class="menu">
            <li class="menu col-2"><a href=#></a>&nbsp;</li>
            <li class="menu col-2"><a href="profile.php">My profile</a></li>
            <li class="menu col-2"><a href="orders.php">My orders</a></li>
            <li class="menu col-2"><a href="cart.php">My cart</a></li>
            <li class="menu col-2"><a href="contact.php">Contact</a></li>
            <li class="menu col-2"><a href=#></a>&nbsp;</li>
        </ul>
    </div>
    <?php
    } else { //else we display the login page
        ?>
        <div class="loginButton col-1">
            <a href=# onclick="showLogin(); return false;">Log in</a>
            <div class="loginPanel" id="loginPanel" style="display:none">
                <form action="login.php" method="post">
                    <input class="loginField" type="text" name="username/email" placeholder="username or email" required/>
                    <input class="loginField" type="password" name="password" placeholder="password" required/>
                    <input id="loginPanelSubmit" type="submit" value="Log in!"/>
                </form>
            </div>
        </div>
        <div class="registration col-1">
            <p><a href="registration.php"> Sign up</a></p>
        </div>
    </div> <!--header row finishes here-->
    
    <div class="menu row">
        <ul class="menu">
            <li class="menu col-4"><a href=#></a>&nbsp;</li>
            <li class="menu col-2"><a href="cart.php">My cart</a></li>
            <li class="menu col-2"><a href="contact.php">Contact</a></li>
            <li class="menu col-4"><a href=#></a>&nbsp;</li>
        </ul>
    </div>
    <?php 
} ?>
