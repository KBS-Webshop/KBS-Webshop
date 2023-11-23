<?php

include __DIR__ . "/components/header.php";
include __DIR__ . "/helpers/utils.php";
?>
<h1> orderbevestiging</h1><br>
<h4>$naam"bedankt voor uw bestelling bij nerdygatgets! uw bestel nummer is $bestelnummer</h4><br>
<h1>Bestel overzicht</h1>
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
    <div id="ProductFrame1">
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
           </form>
                                </div>
                            </h1>
                        </div>
<?php
    }
}
?>
<br>
<h3 class="StockItemPriceTextbevestiging">Totaalprijs: € <?php print str_replace(".",",",$totalprice)?></h3><br>
    <h3 class="verzendadres">verzendadres: </h3><br>
<h4 class="verzendgegevens">
    naam: $naam<br>
    adres: $adres<br>
    woonplaats en postcode: $woonplaats en $postcode<br>
    land: $land
</h4>


        <?php
include __DIR__ . "/components/footer.php"
?>