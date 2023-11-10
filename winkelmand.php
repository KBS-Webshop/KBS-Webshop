<?php
include __DIR__ . "/helpers/cookie.php";
include __DIR__ . "/components/header.php";
include __DIR__ . "/helpers/utils.php";
?>
    <div class="winkelmand-wrapper">
        <h2>Winkelmandje</h2>
        <ul>
            <div id="ResultsArea" class="Winkelmand">
                <?php
                if (isset($_COOKIE["basket"])) {
                    $basket_contents = json_decode($_COOKIE["basket"], true);
                    foreach ($basket_contents as $item) {
                        $StockItem = getStockItem($item["id"], $databaseConnection);
                        $StockItemImage = getStockItemImage($item['id'], $databaseConnection); ?>

                        <style>
                            .buttonAlignmentWinkelmand {
                                display: flex;
                                flex-direction: row;
                            }
                            .buttonWinkelmand {
                                margin-top: %2;
                                margin-left: %5;
                                margin-right: %5;
                            }
                            .winkelmandInputNumber {
                                background-color: #ffffff; /* Changed background color for number input */
                                border: %0.1 solid #00000; /* Added border for number input */
                                color: #0000a4; /* Changed text color for number input */
                                padding: %2 %3;
                                text-align: center;
                                font-size: 16px;
                            }
                            .winkelmandInputSubmit {
                                background-color: #0000a4;
                                border: %0.1 solid #00000;
                                color: #ffffff;
                                padding: %2 %4;
                                text-align: center;
                                font-size: 16px;
                                cursor: pointer; /* Add cursor pointer for better usability */
                            }
                        </style>

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
                                    <h1 class="StockItemPriceText"> <?php print sprintf("€ %.2f", $StockItem['SellPrice'] * $item["amount"]); ?></h1>
                                    <h6> Inclusief BTW </h6>
                                </div>
                            </div>

                            <h1 class="StockItemID"> <?php print ("artikelnummer: ".$item["id"]."<br>")?></h1>
                            <h1 class="StockItemID1"> <?php print($StockItem["StockItemName"]."<br><br>aantal ") ?>
                                <div class="buttonAlignmentWinkelmand">
                                    <form method="post" class="buttonWinkelmand">
                                        <input type="hidden" name="action" value="decrement">
                                        <input type="hidden" name="StockItemID" value="<?php echo $item["id"] ?>">
                                        <input class="winkelmandInputSubmit" type="submit" value="-">
                                    </form>
                                    <form method="post" class="buttonWinkelmand">
                                        <input class="winkelmandInputNumber" type="number" name="amount" value="<?php echo $item["amount"] ?>" min="1" max="999">
                                        <input type="hidden" name="action" value="change_amt">
                                        <input type="hidden" name="StockItemID" value="<?php echo $item["id"] ?>">
                                    </form>
                                    <form method="post" class="buttonWinkelmand">
                                        <input type="hidden" name="action" value="increment">
                                        <input type="hidden" name="StockItemID" value="<?php echo $item["id"] ?>">
                                        <input class="winkelmandInputSubmit" type="submit" value="+">
                                    </form>
                                    <form method="post" class="buttonWinkelmand">
                                        <input type="image" src="images/trashbin.png" alt="Remove product">
                                        <input type="hidden" name="action" value="remove">
                                        <input type="hidden" name="StockItemID" value="<?php echo $item["id"] ?>">
                                    </form>
                                </div>
                            </h1>

                        </div>

                        <?php

                    }
                } else {
                    echo "Winkelmandje is leeg.";
                }
                ?>
            </div>
        </ul>
    </div>
    

<?php
include __DIR__ . "/components/footer.php"
?>