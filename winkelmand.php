<?php
include __DIR__ . "/helpers/cookie.php";
include __DIR__ . "/components/header.php";
include __DIR__ . "/helpers/utils.php"



?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Winkelmandje</title>
    </head>
    <body>


    <h2>Winkelmandje</h2>
    <ul>
        <div id="ResultsArea" class="Browse">

            <?php
            if (isset($_COOKIE["basket"])) {
                $basket_contents = json_decode($_COOKIE["basket"], true);

                foreach ($basket_contents as $item) {
                    $id = $item["product"]["StockItemID"];
                    $name = $item["product"]["StockItemName"];
                    $price1 = $item["product"]["StockItemPrice"];
                    $price = round($price1, 2);
                    $imagePath = $item["product"]["StockItemImage"];
                    $amount =  $item["amount"]; ?>

                    <a class="ListItem" href='view.php?id=<?php print $item['product']['StockItemID']; ?>'>
                        <div id="ProductFrame">
                            <?php
                            if (isset($item["product"]["StockItemImage"])) { ?>
                                <div class="ImgFrame"
                                     style="background-image: url('<?php print "Public/StockItemIMG/" . $item["product"]["StockItemImage"]; ?>'); background-size: contain; background-repeat: no-repeat; background-position: center;"></div>
                            <?php }
                            ?>
                            <div id="StockItemFrameRight">
                                <div class="CenterPriceLeftChild">
                                    <h1 class="StockItemPriceText"> <?php print ("€ ".$price*$amount) ?></h1>
                                    <h6> Inclusief BTW </h6>
                                </div>
                            </div>
                            <h1 class="StockItemID"> <?php print ("artikelnummer: ".$id."<br>")?></h1>
                            <h1 class="StockItemID1"> <?php print($name."<br><br>aantal: ".$amount) ?></h1>
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
    </body>
    </html>

<?php
include __DIR__ . "/components/footer.php"
?>