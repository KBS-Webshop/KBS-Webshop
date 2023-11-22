<?php
include __DIR__ . "/components/header.php";
include __DIR__ . "/helpers/utils.php";
?>
    <h2>Winkelmandje</h2>

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

                        <div id="ProductFrame">
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
                                        <input class="winkelmandInputSubmit winkelmandAantalKnop winkelmandPlusKnop" type="submit" value="+">
                                    </form>
                                    <form method="post" class="buttonWinkelmand">
                                        <input type="image" src="images/trashbin.svg" alt="Remove product" class="winkelmandBinImage">
                                        <input type="hidden" name="action" value="remove">
                                        <input type="hidden" name="StockItemID" value="<?php echo $item["id"] ?>">
                                    </form>
                                </div>
                            </h1>
                        </div>
                        <div class="buttonAfrekenen">
                            <a href="afrekenen.php">
                                <button>Afrekenen</button>
                            </a>
                        </div>
                        <?php
                    }
                }
                else{
                    echo "Winkelmandje is leeg.";
                }
                if($totalprice > 0){
                    $totalprice = str_replace('.', ',', sprintf("€%.2f", $totalprice));
                    ?>
                <div>
                    <h1 class="StockItemPriceTextcart">Totaal prijs: <?php echo $totalprice ?> </h1>
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
                echo ("<tr class='receivedTotalPrice'> <td></td> <th>totaalprijs</th>");
                echo("<td>$totalprice</td></tr>");
                echo '</table>';

                ?>

                <form action="naw.php">
                    <input type="submit" value="Afrekenen">
                </form>
            <?php } ?>
        </div>
    </div>
    

<?php
include __DIR__ . "/components/footer.php"
?>