<?php
include __DIR__ . "/components/header.php";
include __DIR__ . "/helpers/utils.php";
?>
<p class="alert alert-primary m-3">
    Je hebt <?php print getPoints(1, $databaseConnection) ?> punten gespaart. <a href="loyalty-list.php">Kijk hier wat voor acties je hiermee kan vrijspelen.</a>
</p>
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

                        $totalprice += round($item['amount'] * $StockItem['SellPrice'], 2);

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
                                    <h1 class="StockItemPriceText"> <?php $price = sprintf("€ %.2f", $StockItem['SellPrice'] * $item["amount"]); $pricecoma= str_replace(".",",",$price);  print $pricecoma;?></h1>
                                    <h6> Inclusief BTW </h6>
                                </div>
                            </div>

                            <h1 class="StockItemID"> <?php print ("artikelnummer: " . $item["id"]."<br>")?></h1>
                            <h1 class="StockItemID1"> <?php print($StockItem["StockItemName"]."<br><br>aantal ") ?>
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
                        
                        <?php
                    }
                }
                else{
                    echo "Winkelmandje is leeg.";
                }
                if($totalprice > 0){
                    $totalpriceFormatted = formatPrice($totalprice);
                    ?>
                <div>
                    <h1 class="StockItemPriceTextcart">Totaal prijs: <?php echo $totalpriceFormatted ?> </h1>
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
                foreach ($basket_contents as $item) {
                    $StockItem = getStockItem($item["id"], $databaseConnection);
                    echo ("<tr> <td>" . $StockItem['StockItemName'] . "</td>");
                    echo ("<td>" . $item['amount'] . "</td>");
                    echo "<td>".sprintf("€%.2f", $StockItem['SellPrice'] * $item["amount"]);
                }
                ?>

                <tr class='receivedTotalPrice'>
                    <td></td>
                    <th>Punten</th>
                    <td><?php print calculateLoyaltyPoints($totalprice, $databaseConnection) ?></td>
                </tr>
                <tr class='receivedTotalPrice'>
                    <td></td>
                    <th>Prijs</th>
                    <td><?php print $totalpriceFormatted ?></td>
                </tr>
                <tr>
                    <td></td>
                    <th>Korting</th>
                    <td><?php print "-" . formatPrice( calculateDiscount($totalprice, getLoyaltyDeal(getDealInCart(), $databaseConnection)["discount"]) ) ?></td>
                </tr>
                <tr class='receivedTotalPrice'>
                    <td></td>
                    <th>totaalprijs</th>
                    <td><?php print formatPrice(calculatePriceWithDeals($totalprice, $databaseConnection)); ?></td>
                </tr>
            </table>


                <form action="naw.php">
                    <input type="submit" value="Afrekenen">
                </form>
            <?php } ?>
        </div>
    </div>
    

<?php
include __DIR__ . "/components/footer.php"
?>