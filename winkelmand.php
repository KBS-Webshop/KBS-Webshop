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
                                <h1 class="StockItemID1"> <?php print($StockItem["StockItemName"]."<br><br>aantal: " . $item["amount"]) ?></h1>
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