<?php
include __DIR__ . "/components/header.php";
include __DIR__ . "/helpers/utils.php";
?>
<h1>Eerder gekochte producten</h1>
<?php
$previousOrders = getPreviouslyOrderedID($databaseConnection, $_SESSION["user"]["customer"]["CustomerID"]);
if ($previousOrders != null) {
    foreach ($previousOrders as $orderID) {
        $orderlines = getPreviousOrderLines($databaseConnection, $orderID);
        ?>
        <div class="previouslyOrderedItem">
        <h2> order id: <?php print $orderID; ?> </h2>
        <?php
        foreach ($orderlines as $orderline) {
            ?>
            <?php
            $orderlineID = $orderline["OrderlineID"];
            getPreviouslyBought($databaseConnection, $orderlineID);
            $bestelDatum = explode(" ", $_SESSION["user"]["currentOrder"]["LastEditedWhen"]);
            $bestelDatum = $bestelDatum[0];
            getStockItemInfo($databaseConnection, $_SESSION["user"]["currentOrder"]["StockItemID"]);
            $StockItemImage = getStockItemImage($_SESSION["user"]["currentOrder"]["StockItemID"], $databaseConnection);
    ?>
                <div class="previouslyOrderedItem">
                    <div class="ImgFrame"
                         style="background-image: url('<?php print "Public/StockItemIMG/" . $StockItemImage[0]["ImagePath"]; ?>'); background-size: contain; background-repeat: no-repeat; background-position: center;">
                    </div>
                </div>
                <div class="previouslyOrderedItem">
        <h2> naam: <?php print ($_SESSION["user"]["currentStockItem"]["StockItemName"]); ?> </h2>
            </div>
            <div class="previouslyOrderedItem">
        <h2> quantity: <?php print ($_SESSION["user"]["currentOrder"]["Quantity"]); ?> </h2>
            </div>
            <div class="previouslyOrderedItem">
        <h2> besteldatum: <?php print ($bestelDatum); ?> </h2>
            </div>


    <?php
    }
?>
        </div>
        <?php
    }
} else {
    print("U heeft nog geen bestellingen geplaatst.");
}
?>

<?php
include __DIR__ . "/components/footer.php"
?>