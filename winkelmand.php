<?php
include __DIR__ . "/components/header.php";
include __DIR__ . "/helpers/utils.php";
if(!isset($_SESSION['user']['PersonID'])){
    $_SESSION['user']['PersonID']=0;
}
if($_SESSION['user']['PersonID']){
?>
<p class="alert alert-primary m-3">
    Je hebt <?php print getPoints($_SESSION['user']['PersonID'], $databaseConnection) ?> punten gespaart. <a href="loyalty-list.php">Kijk hier wat voor acties je hiermee kan vrijspelen.</a>
</p>
    <?php } ?>
<h2>Winkelmandje</h2>
<?php
if(isset($_POST["mislukt"])){
    print ("<h3 style='color: red'>Betaling mislukt, probeer het opnieuw.</h3>");
}
if (isset($_GET["message"])){
    print ("<h3 style='color: red'>".$_SESSION["itemNietOpVoorraad"]." is niet meer op voorraad (nog maar ".$_SESSION["QuantityOnHand"]." op voorraad).</h3>");
}

?>
    <div class="winkelmand-wrapper">
        <ul class="winkelmand">
            <div id="ResultsArea" class="Winkelmand">
                <?php
                $totalprice = 0;
                if (isset($_COOKIE["basket"]) AND !cookieEmpty()) {
                    $basket_contents = json_decode($_COOKIE["basket"], true);
                    foreach ($basket_contents as $item) {
                        $StockItem = getStockItem($item["id"], $databaseConnection);
                        $StockItemImage = getStockItemImage($item['id'], $databaseConnection);
                        $currentDiscount = getDiscountByStockItemID($item["id"], $databaseConnection);

                        if ($currentDiscount)
                            $totalprice += calculateDiscountedPriceBTW($StockItem['SellPrice'], $currentDiscount['DiscountPercentage'], $StockItem['TaxRate'], $item['amount'], true);
                        else
                            $totalprice += calculatePriceBTW($StockItem['SellPrice'], $StockItem['TaxRate'], $item['amount'], true);
                        ?>

                        <div id="ProductFrame2">
                            <?php
                            if (isset($StockItemImage[0]["ImagePath"])) { ?>
                            <a class="ListItem" href='view.php?id=<?php print $item["id"]; ?>'>
                                <div class="ImgFrame"
                                     style="background-image: url('<?php print "Public/StockItemIMG/" . $StockItemImage[0]["ImagePath"]; ?>'); background-size: contain; background-repeat: no-repeat; background-position: center;">

                                </div>
                            </a>
                            <?php }
                            ?>
                            <div id="StockItemFrameRight" style="display: flex;flex-direction: column">
                                <div class="CenterPriceLeft">
                                    <h1 class="StockItemPriceText">
                                        <?php if ($currentDiscount) { ?>
                                            <h1><b><?php echo intval(-$currentDiscount['DiscountPercentage'], 10) ?>%</b></h1>
                                            <h4 id="clock<?php echo $StockItem['StockItemID'] ?>" style="font-weight: bold;"></h4>
                                            <h2 class="StockItemPriceText">
                                                <s class="strikedtext"><?php echo calculatePriceBTW($StockItem['SellPrice'], $StockItem['TaxRate']); ?></s>
                                                <?php echo calculateDiscountedPriceBTW($StockItem['SellPrice'], $currentDiscount['DiscountPercentage'], $StockItem['TaxRate']); ?>
                                            </h2>
                                        <?php } else { ?>
                                            <h2 class="StockItemPriceText"><?php echo calculatePriceBTW($StockItem['SellPrice'], $StockItem['TaxRate']); ?></h2>
                                        <?php } ?>
                                    </h1>
                                    <h6> Inclusief BTW </h6>
                                </div>
                            </div>

                            <h1 class="StockItemID"> <?php print ("artikelnummer: " . $item["id"]."<br>")?></h1>
                            <h1 class="StockItemID1"> <?php print($StockItem["StockItemName"]) ?>
                                <br><br>
                                <?php
                                $amtSoldLast72Hrs = getAmountOrderedLast72Hours($StockItem['StockItemID'], $databaseConnection);
                                if ($amtSoldLast72Hrs >= 5) { ?>
                                    <p><b>ERG GEWILD: dit product is afgelopen 72 uur <?php echo $amtSoldLast72Hrs ?> keer verkocht.</b></p>
                                <?php } ?>
                                <br>
                                Aantal
                                <div class="buttonAlignmentWinkelmand">
                                    <form method="post" class="buttonWinkelmand">
                                        <input type="hidden" name="action" value="decrement">
                                        <input type="hidden" name="StockItemID" value="<?php echo $item["id"] ?>">
                                        <input class="winkelmandInputSubmit winkelmandAantalKnop winkelmandMinKnop" type="submit" value="-">
                                    </form>
                                    <form method="post" class="buttonWinkelmand">
                                        <input class="winkelmandInputNumber" type="number" name="amount" value="<?php echo $item["amount"] ?>" min="1" max="<?php echo intval(preg_replace('/[^0-9]+/', '', $StockItem["QuantityOnHand"])); ?>" required>
                                        <input type="hidden" name="action" value="change_amt">
                                        <input type="hidden" name="StockItemID" value="<?php echo $item["id"] ?>">
                                    </form>
                                    <form method="post" class="buttonWinkelmand">
                                        <input type="hidden" name="action" value="increment">
                                        <input type="hidden" name="StockItemID" value="<?php echo $item["id"] ?>">
                                        <input class="winkelmandInputSubmit winkelmandAantalKnop winkelmandPlusKnop" type="submit"
                                            <?php if (preg_replace('/[^0-9]+/', '', $StockItem["QuantityOnHand"]) <= $item['amount'])
                                                echo  'disabled'?>
                                               value="+">
                                    </form>
                                    <form method="post" class="buttonWinkelmand">
                                        <input type="image" src="images/trashbin.svg" alt="Remove product" class="winkelmandBinImage">
                                        <input type="hidden" name="action" value="remove">
                                        <input type="hidden" name="StockItemID" value="<?php echo $item["id"] ?>">
                                    </form>
                                </div>
                            </h1>
                        </div>
                        <?php if ($currentDiscount AND strtotime($currentDiscount['StartDate']) < time()) { ?>
                            <script>
                                clockCountdown('clock<?php echo $item['id'] ?>', '<?php echo $currentDiscount['EndDate'] ?>');
                            </script>
                        <?php }
                    }
                }
                else{
                    echo "Winkelmandje is leeg.";
                }
                if($totalprice > 0){
                    $deal = getLoyaltyDeal(getDealInCart(), $databaseConnection);
                    if ($deal != null) {
                        $loyaltyDealDiscount = calculateDiscount($totalprice, $deal["discount"]);
                    } else {
                    $loyaltyDealDiscount = 0;
                    }
                ?>
                    <div>
                        <?php if ($loyaltyDealDiscount > 0) { ?>
                            <h1 class="StockItemPriceTextcart">Gespaarde korting: <?php echo formatPrice($loyaltyDealDiscount) ?> </h1>
                        <?php } ?>
                        <h1 class="StockItemPriceTextcart">Totaal prijs: <?php echo formatPrice($totalprice - $loyaltyDealDiscount) ?> </h1>
                    </div>
                <?php } ?>
            </div>
        </ul>
        <?php
        if (isset($_COOKIE["basket"]) AND !cookieEmpty()) {
            ?>
        <div class="bonnetje-wrapper">
            <?php
            $basket_contents = json_decode($_COOKIE["basket"], true);
            ?>
            <table>
                <th>Product</th>
                <th>Aantal</th>
                <th>Prijs</th>

                <?php
                $totalprice_discount = 0;
                $totalprice_normal = 0;

                foreach ($basket_contents as $item) {
                    $StockItem = getStockItem($item["id"], $databaseConnection);
                    $currentDiscount = getDiscountByStockItemID($item["id"], $databaseConnection);
                    echo ("<tr> <td>" . $StockItem['StockItemName'] . "</td>");
                    echo ("<td>" . $item['amount'] . "</td>");

                    if ($currentDiscount) {
                        echo "<td>" . calculateDiscountedPriceBTW($StockItem['SellPrice'], $currentDiscount['DiscountPercentage'], $StockItem['TaxRate'], $item['amount']) . "</td></tr>";
                        $totalprice_discount += calculateDiscountedPriceBTW($StockItem['SellPrice'], $currentDiscount['DiscountPercentage'], $StockItem['TaxRate'], $item['amount'], true);
                    } else {
                        echo "<td>" . calculatePriceBTW($StockItem['SellPrice'], $StockItem['TaxRate'], $item['amount']) . "</td></tr>";
                        $totalprice_discount += calculatePriceBTW($StockItem['SellPrice'], $StockItem['TaxRate'], $item['amount'], true);
                    }
                    $totalprice_normal += calculatePriceBTW($StockItem['SellPrice'], $StockItem['TaxRate'], $item['amount'], true);
                }

                if ($totalprice_discount < $totalprice_normal) {
                    $priceDifference = $totalprice_normal - $totalprice_discount;
                    echo '<tr><th colspan="3">Je bespaart ' . formatPrice($priceDifference) . ' door de korting!</th></tr>';
                }
                ?>

                <?php if (getDealInCart() != null) { ?>
                    <tr class='receivedTotalPrice'>
                        <td></td>
                        <th>Prijs</th>
                        <td><?php print formatPrice($totalprice_discount) ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <th>Korting</th>
                        <td><?php print "-" . formatPrice(calculateDiscount($totalprice_discount, getLoyaltyDeal(getDealInCart(), $databaseConnection)["discount"]) ) ?></td>
                    </tr>
                <?php } ?>
                <tr class='receivedTotalPrice'>
                    <td></td>
                    <th>Punten</th>
                    <td><?php print calculateLoyaltyPoints(calculatePriceWithDeals($totalprice_discount, $databaseConnection), $databaseConnection) ?></td>
                </tr>
                <tr>
                    <td></td>
                    <th>Totaalprijs</th>
                    <td><?php print formatPrice(calculatePriceWithDeals($totalprice_discount, $databaseConnection)); ?></td>
                </tr>

                </table>

                <form action="naw.php">
                    <input type="submit" value="Afrekenen">
                </form>
        </div>
    </div>
    

<?php
        }
include __DIR__ . "/components/footer.php"
?>