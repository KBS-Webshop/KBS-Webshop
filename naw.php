<?php
include __DIR__ . "/components/header.php";
include __DIR__ . "/helpers/utils.php";
include __DIR__ . "/helpers/database.php";
include
$Cname = " ";
$phoneNumber = " ";
$DeliveryAddress = " ";
$DeliveryPostalCode = " ";
$DeliveryInstructions = "";
$betaald = true;
if (isset($_POST["naam"])) {
    $Cname = $_POST["naam"];
}
if (isset($_POST["telefoonnummer"])) {
    $phoneNumber = $_POST["telefoonnummer"];
}
if (isset($_POST["adress"])) {
    $DeliveryAddress = $_POST["adress"];
}
if (isset($_POST["postcode"])) {
    $DeliveryPostalCode = $_POST["postcode"];
}
if (isset($_POST["bezorgInstructies"])) {
    $DeliveryInstructions = $_POST["bezorgInstructies"];
}

function PlaceOrder(
    $Cname,
    $phoneNumber,
    $DeliveryAddress,
    $DeliveryPostalCode,
    $DeliveryInstructions,
    $databaseConnection,
    $betaald
) {

    $orderstatus = "Wordt verwerkt";

    if ($betaald == true) {

        $customerId = getCustomer($Cname, $phoneNumber, $DeliveryAddress, $DeliveryPostalCode, $databaseConnection);
        if ($customerId == null) {
            addCustomer($Cname, $phoneNumber, $DeliveryAddress, $DeliveryPostalCode, $databaseConnection);
            $customerStatus = getCustomer($Cname, $phoneNumber, $DeliveryAddress, $DeliveryPostalCode, $databaseConnection);
        }
        if ($row['quantityOnHand'] < $item['amount']) {
            $isInStock = 0;
        } else {
            $isInStock = 1;
        }
        $salesContactPersonID = 3262;
        $currentDate = date("Y-m-d");
        $estimatedDeliveryDate = date("Y-m-d", strtotime($currentDate . "+ 1 days"));
        addOrder($customerId, $DeliveryInstructions, $databaseConnection);

        $OrderID = getOrderID($customerId, $databaseConnection);

        $basket_contents = json_decode($_COOKIE["basket"], true);
        foreach ($basket_contents as $item) {
            addOrderline($OrderID, $item["id"], $databaseConnection);
        }

        $orderstatus = "Order is geplaatst";

    } else {

        $orderstatus = "Order is niet geplaatst";

    }

    return $orderstatus;
}
?>
<html>

<form method="POST" class="naw-form">
    <div class="naw-input">
        <label for="name">
          Naam <span class="required"></span>
        </label>
        <input type="text" name="naam" id="naam" required>
    </div>

    <div class="naw-input form-width-2">
        <div class="naw-input-inner">
            <label for="straatnaam" class="inline-label">
                Straatnaam <span class="required"></span>
            </label>
            <input type="text" name="straatnaam" id="straatnaam" required>
        </div>
        <div class="naw-input-inner2">
            <label for="name" class="inline-label">
                Huisnummer <span class="required"></span>
            </label>
            <input type="text" name="huisnummer" id="huisnummer" required>
        </div>
    </div>

    <div class="naw-input form-width-4">
            <div class="naw-input-inner">
                <label for="name" class="inline-label">
                    Postcode <span class="required"></span>
                </label>
            <input type="text" name="postcode" id="postcode" required>
        </div>
        <div class="naw-input-inner">
            <label for="name" class="inline-label">
                Land <span class="required"></span>
            </label>
            <input type="text" name="land" id="land">
        </div>
    </div>

    <div class="naw-input form-width-5">
        <div class="naw-input-inner">
            <label for="name">
                Telefoonnummer
            </label>
            <input type="text" name="telefoonnummer" id="telefoonnummer" >
        </div>
    </div>

    <div class="naw-input form-width-5">
        <div class="naw-input-inner">
            <label for="name">
                Email-adres <span class="required"></span>
            </label>
            <input type="text" name="email" id="email" required>
        </div>
    </div>
<?php
//print ($Cname); ?><!-- <BR> --><?php
//print ($phoneNumber); ?><!-- <BR> --><?php
//    print ($DeliveryAddress); ?><!-- <BR> --><?php
//    print ($DeliveryPostalCode); ?><!-- <BR> --><?php
//    print ($DeliveryInstructions); ?><!-- <BR> --><?php
//    ?>
    <div class="radio-container">

        <fieldset>
            <legend>Verzendopties</legend>
        <div class="radio-label-naw">
                <label>
                    <input type="radio" name="Verzending" id="standaardVerzending" required>
                    Standaard verzending
                </label>
                <label>
                    <input type="radio" name="Verzending" id="expressVerzending" required>
                    Express verzending
                </label>
        </div>
        </fieldset>

        <div class="radio-label">
                <label>
                    <input type="radio" name="betaalmethode" id="iDeal" required>
                    iDeal
                </label>
                <label>
                    <input type="radio" name="betaalmethode" id="Nerdygadgets Giftcard" required>
                    Nerdygadgets Gifcard
                </label>
        </div>
    </div>

    <div class="comments">
        <div>
            <label for="opmerkingen">Instructies voor de bezorger. (Optioneel)</label>
        </div>
        <div>
            <textarea id="bezorgInstructies" name="bezorgInstructies" rows="6" cols="50"></textarea>
        </div>
    </div>

    <div class="naw-submit-wrapper">
        <input type="submit" value="Afrekenen" class="button primary">
    </div>
</form>
</html>


<?php
if (isset($_POST["naam"]) && isset($_POST["telefoonnummer"]) && isset($_POST["adress"]) && isset($_POST["postcode"])) {
    $orderstatus = PlaceOrder($Cname, $phoneNumber, $DeliveryAddress, $DeliveryPostalCode, $DeliveryInstructions, $databaseConnection, $betaald);
    print ($orderstatus);

}
include __DIR__ . "/components/footer.php"
?>
