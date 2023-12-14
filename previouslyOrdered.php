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
        <h2> order id: <?php print $orderID ?> </h2>
                </div>
        <?php
        foreach ($orderlines as $orderline) {
            ?>
            <div class="previouslyOrdered">
            <?php
            $orderlineID = $orderline["OrderlineID"];
            $orders =  getPreviouslyBought($databaseConnection, $orderlineID);
    ?>
            <div class="previouslyOrderedItem">
        <h2> quantity: <?php print ($_SESSION["user"]["currentOrder"]["Quantity"]) ?> </h2>
            </div>
            <div class="previouslyOrderedItem">
        <h2> besteldatum: <?php print ($_SESSION["user"]["currentOrder"]["LastEditedWhen"]) ?> </h2>
            </div>
        </div>

    <?php
    }
    }
} else {
    print("U heeft nog geen bestellingen geplaatst.");
}
?>

<?php
include __DIR__ . "/components/footer.php"
?>