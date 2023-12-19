<?php

include __DIR__ . "/components/header.php";
include __DIR__ . "/helpers/utils.php";

clearCookie();

$naam = $_SESSION["user"]["NAW"]["FullName"];
$telefoonnummer = $_SESSION["user"]["NAW"]["PhoneNumber"];
$adress = $_SESSION["user"]["NAW"]["DeliveryAddressLine1"];
$postcode = $_SESSION["user"]["NAW"]["DeliveryPostalCode"];
$stad = $_SESSION["user"]["NAW"]["CityName"];
$betaald = TRUE;
$Cname = " ";
$phoneNumber = " ";
$DeliveryAddress = " ";
$DeliveryPostalCode = " ";
$DeliveryInstructions = "";
$amountOfProductsInOrder = 0;
$quantityOnHand = 0;
$Cname = $_SESSION["user"]["NAW"]["FullName"];
$phoneNumber = $_SESSION["user"]["NAW"]["PhoneNumber"];
$DeliveryAddress = $_SESSION["user"]["NAW"]["DeliveryAddressLine1"];
$DeliveryPostalCode = $_SESSION["user"]["NAW"]["DeliveryPostalCode"];
$DeliveryInstructions = $_SESSION["user"]["NAW"]["DeliveryInstructions"];
$cityName = $_SESSION["user"]["NAW"]["CityName"];
$DeliveryProvince = $_SESSION["user"]["NAW"]["DeliveryProvince"];

if (isset($_SESSION["user"]["NAW"]["FullName"]) && isset($_SESSION["user"]["NAW"]["PhoneNumber"]) && isset($_SESSION["user"]["NAW"]["DeliveryAddressLine1"]) && isset($_SESSION["user"]["NAW"]["DeliveryPostalCode"]) && isset($_SESSION["user"]["NAW"]["CityName"]) && $_SESSION["order"]["placeOrder"] == TRUE) {
$_SESSION["order"]["orderID"] = PlaceOrder(
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
        $cityName
    );
    $placeOrder = 0;
    $_SESSION["order"]["placeOrder"] = FALSE;
    if ($_SESSION["user"]["isLoggedIn"]) {
        $personID = $_SESSION['user']['PersonID'];
        $recipient = $_SESSION["userEmail"];
        $subject = 'Orderbevestiging';
        $subject1 = 'reclame';
        $Naam = $_SESSION["user"]["NAW"]["FullName"];
        $customerID1 = getCustomerIDbypersonID($databaseConnection, $personID);
        $customerID = $customerID1[0]['CustomerID'];
        $gegevens = getUserInfo($databaseConnection, $personID);
        $customerDetails = $gegevens[0];
        $ordergegevens = getOrder($databaseConnection, $_SESSION["order"]["orderID"]);
        $bezorgAdres = $customerDetails['DeliveryAddressLine1'];
        $linkUserInfo = '<a href="http://localhost/KBS-webshop/userInfoAanpassen.php" style="max-width: 10rem; border-radius: 10px; border: none; text-decoration: none; display: inline-block; text-align: center; padding: 0.5rem; background-color: #0000a4; color: white; font-size: 14px;">pas gebruikersinfo aan</a>';
        $logo = "<div style='background-color: #f5f5f5; padding: 20px;'>
        
        <a href='http://localhost/KBS-webshop/'><img src='cid:logo' alt='Logo' width='300' height='300'></a>
    </div>
";


        $editorContent1 = getEmailTemplate($databaseConnection, 'orderbevesteging');
        $editorContent2 = getEmailTemplate($databaseConnection, 'reclame');
        $editorContent = $editorContent1[0]['content'];
        $editorContent = str_replace('$(naam)', $naam, $editorContent);
        $editorContent = str_replace('$(customerID)', $customerID, $editorContent);
        $editorContent = str_replace('$(telefoonnummer)', $telefoonnummer, $editorContent);
        $editorContent = str_replace('$(bezorg-adres)', $bezorgAdres, $editorContent);
        $editorContent = str_replace('$(postcode)', $postcode, $editorContent);
        $editorContent = str_replace('$(linkUserInfo)', $linkUserInfo, $editorContent);
        $editorContent = str_replace('$(logo)', $logo, $editorContent);
        $editorContent3 = $editorContent2[0]['content'];

        $editorContent3 = str_replace('$(naam)', $naam, $editorContent3);
        $editorContent3 = str_replace('$(customerID)', $customerID, $editorContent3);
        $editorContent3 = str_replace('$(telefoonnummer)', $telefoonnummer, $editorContent3);
        $editorContent3 = str_replace('$(bezorg-adres)', $bezorgAdres, $editorContent3);
        $editorContent3 = str_replace('$(postcode)', $postcode, $editorContent3);
        $editorContent3 = str_replace('$(linkUserInfo)', $linkUserInfo, $editorContent3);
        $editorContent3 = str_replace('$(logo)', $logo, $editorContent3);
        $productenText = '';
        foreach ($ordergegevens as $ordergegeven) {
            $productenText .= 'Item Name: ' . $ordergegeven["StockItemName"] . ' aantal: ' . $ordergegeven['Quantity'] . '<br>';
        }
        $alsobought1 = getAlsoBought($ordergegevens[0]['StockItemID'], $databaseConnection);
        $productText = '';

        foreach ($alsobought1 as $item) {
            $productText .= 'Item Name: ' . $item['StockItemName'] . '<br>';
        }

        $editorContent = str_replace('$(producten)', $productenText, $editorContent);
        $editorContent = str_replace('$(alsobought)', $productText, $editorContent);
        $editorContent3 = str_replace('$(producten)', $productenText, $editorContent3);
        $editorContent3 = str_replace('$(alsobought)', $productText, $editorContent3);

        $textBody = 'orderbevestiging';
        $textBody1 = 'reclame';

        sendEmail($recipient, $subject, $editorContent, $textBody, __DIR__ . '\Public\ProductIMGHighRes\NerdyGadgetsLogo.png');
        sendEmail($recipient, $subject1, $editorContent3, $textBody1, __DIR__ . '\Public\ProductIMGHighRes\NerdyGadgetsLogo.png');
    }
}
?>
<h1> orderbevestiging</h1><br>
<h4><?php print $naam?>, bedankt voor uw bestelling bij NerdyGadgets! Uw bestel nummer is: <?php print $_SESSION["order"]["orderID"] ?></h4><br>
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
<h3 class="StockItemPriceTextbevestiging">Totaalprijs: € <?php print str_replace(".",",",$totalprice)?></h3><br>
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