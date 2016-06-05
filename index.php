<?php
include "header.php";
require_once "config.php";
include "dbconn.php";
include "headershop.php";
?>

<div class="content">
    <h2>The best smartphones of the year</h2>

    <?php 
    $statement = $conn->prepare("
        SELECT id, name, price, hash 
        FROM product");
    //$result = $conn->query("SELECT id, name, price FROM product");
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) { ?>
        <div class="responsive">
            <div class="img">
                <a target="_blank" href="uploads/<?=$row['hash']?>">
                  <img src="small/<?=$row['hash']?>" alt="<?=$row["name"]?>" width="300" height="200">
                </a>
                <div class="desc">
                    <a href="description.php?id=<?=$row["id"]?>"><?=$row["name"]?></a><span> - <?=$row["price"]?>eur</span>
                </div>
            </div>
        </div>
    <?php
    }
    ?>
</div>
<div class="clearfix"></div> <!--Clears formating of the gallery-->

<?php
include "footer.php";
?>
