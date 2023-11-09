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

                        <a class="ListItem" href='view.php?id=<?php print $item["id"]; ?>'>
                            <div id="ProductFrame">
                                <?php
                                if (isset($StockItemImage[0]["ImagePath"])) { ?>
                                    <div class="ImgFrame"
                                        style="background-image: url('<?php print "Public/StockItemIMG/" . $StockItemImage[0]["ImagePath"]; ?>'); background-size: contain; background-repeat: no-repeat; background-position: center;"></div>
                                <?php }
                                ?>
                                <div id="StockItemFrameRight">
                                    <div class="CenterPriceLeftChild">
                                        <h1 class="StockItemPriceText"> <?php print sprintf("€ %.2f", $StockItem['SellPrice'] * $item["amount"]); ?></h1>
                                        <h6> Inclusief BTW </h6>
                                    </div>
                                </div>
                                <h1 class="StockItemID"> <?php print ("artikelnummer: ".$item["id"]."<br>")?></h1>
                                <h1 class="StockItemID1"> <?php print($StockItem["StockItemName"]."<br><br>aantal in winkelwagen: " . $item["amount"]) ?></h1>

                                <style>
                                    .knoppen {
                                        display: flex;
                                        flex-direction: row;
                                    }
                                    .knop {
                                        margin-top: %2;
                                        margin-left: %5;
                                        margin-right: %5;
                                    }
                                    input[type="number"] {
                                        background-color: #ffffff; /* Changed background color for number input */
                                        border: %0.1 solid #00000; /* Added border for number input */
                                        color: #0000a4; /* Changed text color for number input */
                                        padding: %2 %3;
                                        text-align: center;
                                        font-size: 16px;
                                    }
                                    input[type="submit"] {
                                        background-color: #0000a4;
                                        border: %0.1 solid #00000;
                                        color: #ffffff;
                                        padding: %2 %4;
                                        text-align: center;
                                        font-size: 16px;
                                        cursor: pointer; /* Add cursor pointer for better usability */
                                    }
                                    .text {
                                        font-weight: bold; /* Added font weight for the 'aantal in winkelmand' text */
                                    }
                                </style>

                                <div class="knoppen">
                                    <form method="post" class="knop">
                                        <input type="hidden" name="action" value="decrement">
                                        <input type="hidden" name="StockItemID" value="<?php echo $item["id"] ?>">
                                        <input type="submit" value="-">
                                    </form>
                                    <form method="post" class="knop">
                                        <input type="number" name="knop+" value="1" min="1" max="999">
                                        <input type="hidden" name="action" value="change_amt">
                                        <input type="hidden" name="StockItemID" value="<?php echo $item["id"] ?>">

                                    </form>
                                    <form method="post" class="knop">
                                        <input type="hidden" name="action" value="increment">
                                        <input type="hidden" name="StockItemID" value="<?php echo $item["id"] ?>">
                                        <input type="submit" value="+">
                                    </form>
                                    <form method="post" class="knop">
                                        <input type="hidden" name="action" value="remove">
                                        <input type="hidden" name="StockItemID" value="<?php echo $item["id"] ?>">
                                        <input type="submit" value="Remove from basket">
                                    </form>
                                </div>
                            </div>
                        </a>

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