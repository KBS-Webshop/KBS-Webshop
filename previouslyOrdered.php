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
        <div class="previouslyOrdered">
            <h2> Order ID: <?php print $orderID; ?> </h2>
            <?php
            foreach ($orderlines as $orderline) {
                $orderlineID = $orderline["OrderlineID"];
                getPreviouslyBought($databaseConnection, $orderlineID);
                $bestelDatum = explode(" ", $_SESSION["user"]["currentOrder"]["LastEditedWhen"]);
                $bestelDatum = $bestelDatum[0];
                getStockItemInfo($databaseConnection, $_SESSION["user"]["currentOrder"]["StockItemID"]);
                $StockItemImage = getStockItemImage($_SESSION["user"]["currentOrder"]["StockItemID"], $databaseConnection);
                ?>
                <div class="previouslyOrderedItem">
                    <div class="ImgFrame1" style="background-image: url('<?php print "Public/StockItemIMG/" . $StockItemImage[0]["ImagePath"]; ?>');"></div>
                    <div class="details">
                        <h2> Naam: <?php print ($_SESSION["user"]["currentStockItem"]["StockItemName"]); ?> </h2>
                        <p> Quantity: <?php print ($_SESSION["user"]["currentOrder"]["Quantity"]); ?> </p>
                        <p> Besteldatum: <?php print ($bestelDatum); ?> </p>
                    </div>
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
include __DIR__ . "/components/footer.php";
?>