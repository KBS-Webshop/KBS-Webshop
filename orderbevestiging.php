<?php

include __DIR__ . "/components/header.php";
include __DIR__ . "/helpers/utils.php";

$naam=$_SESSION["naam"];
$telefoonnummer=$_SESSION["telefoonnummer"];
$adress=$_SESSION["adress"];
$postcode=$_SESSION["postcode"];
$stad=$_SESSION["stad"];
$betaald = TRUE;
$Cname = " ";
$phoneNumber = " ";
$DeliveryAddress = " ";
$DeliveryPostalCode = " ";
$DeliveryInstructions = "";
$amountOfProductsInOrder = 0;
$quantityOnHand = 0;
$Cname = $_SESSION["naam"];
$phoneNumber = $_SESSION["telefoonnummer"];
$DeliveryAddress = $_SESSION["adress"];
$DeliveryPostalCode = $_SESSION["postcode"];
$DeliveryInstructions = $_SESSION["bezorgInstructies"];
$cityName = $_SESSION["stad"];
$DeliveryProvince = $_SESSION["provincie"];

if (isset($_SESSION["naam"]) && isset($_SESSION["telefoonnummer"]) && isset($_SESSION["adress"]) && isset($_SESSION["postcode"]) && isset($_SESSION["provincie"]) && isset($_SESSION["stad"])) {
    $orderID = PlaceOrder(
        $databaseConnection,
        $Cname,
        $phoneNumber,
        $DeliveryAddress,
        $DeliveryPostalCode,
        $DeliveryInstructions,
        $betaald,
        $amountOfProductsInOrder,
        $quantityOnHand,
        $DeliveryProvince,
        $cityName,
        $_SESSION["price"]
    );
}
?>

<h1> orderbevestiging</h1><br>
<h4><?php print $naam?>, bedankt voor uw bestelling bij NerdyGadgets! Uw bestel nummer is: <?php print $orderID?></h4><br>
<h1>Bestel overzicht</h1>
    <div class="winkelmand-wrapper">
    <ul class="winkelmand">
    <div id="ResultsArea" class="Winkelmand">
<?php
$totalprice = 0;
if ((isset($_COOKIE["basket"]) AND !cookieEmpty()) OR TRUE) {
    $basket_contents = json_decode($_SESSION["basket"], true);
    foreach ($basket_contents as $item) {
        $StockItem = getStockItem($item["id"], $databaseConnection);
        $currentDiscount = getDiscountByStockItemID($item["id"], $databaseConnection);
        $StockItemImage = getStockItemImage($item['id'], $databaseConnection);

        if ($currentDiscount) {
            $totalprice += calculateDiscountedPriceBTW($StockItem["SellPrice"], $currentDiscount["DiscountPercentage"], $StockItem['TaxRate'], $item["amount"], true);
        } else {
            $totalprice += calculatePriceBTW($StockItem["SellPrice"], $StockItem['TaxRate'], $item["amount"], true);
        }

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
            <h1 class="StockItemPriceText">
                <?php if ($currentDiscount) { ?><h1><b>-<?php echo intval($currentDiscount['DiscountPercentage'], 10) ?>%</b></h1>
                    <h2 class="StockItemPriceText">
                        <s class="strikedtext">
                            <?php echo calculatePriceBTW($StockItem['SellPrice'], $StockItem['TaxRate']); ?>
                        </s>
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
    <h1 class="StockItemID1"> <?php print($StockItem["StockItemName"]."<br><br>aantal: ". $item['amount']) ?>
    <div class="buttonAlignmentWinkelmand">
                                </div>
                            </h1>
                        </div>
<?php
    }
}
?>
<br>
<?php if(getDealInCart() != null) { ?>
<h3 class="StockItemPriceTextbevestiging">Korting: <?php print(formatPrice(calculateDiscount($totalprice, getLoyaltyDeal(getDealInCart(), $databaseConnection)["discount"]))) ?></h3>
<?php } ?>
<h3 class="StockItemPriceTextbevestiging">Totaalprijs: <?php print formatPrice(calculatePriceWithDeals($totalprice, $databaseConnection))?></h3><br>
<h3 class="verzendadres">uw Gegevens: </h3>
<h4 class="verzendgegevens">
    naam: <?php print $naam?><br>
    adres: <?php print $adress." in ". $stad?><br>
    postcode: <?php print $postcode?><br>
    telefoonnummer: <?php print $telefoonnummer?><br>
</h4>

<?php
include __DIR__ . "/components/footer.php"
?>