<?php
include __DIR__ . "/components/header.php";
include __DIR__ . "/helpers/utils.php";
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
              Volledige Naam
            </label>
        <input type="text" name="naam" id="naam" required>
    </div>

    <div class="naw-input form-width-2">
        <div class="naw-input-inner">
                <label for="name">
                    Straatnaam & huisnummer
                </label>
            <input type="text" name="adress" id="adress" required>
        </div>
    </div>

    <div class="naw-input form-width-4">
            <div class="naw-input-inner">
                <label for="name">
                    Postcode
                </label>
            <input type="text" name="postcode" id="postcode" required>
        </div>
    </div>

    <div class="naw-input form-width-5">
        <div class="naw-input-inner">
            <label for="name">
                Telefoonnummer
            </label>
            <input type="text" name="telefoonnummer" id="telefoonnummer" required>
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
        <div class="radio-label-naw">
                <label>
                    <input type="radio" name="Verzending" id="standaardVerzending">
                    Standaard verzending
                </label>
                <label>
                    <input type="radio" name="Verzending" id="expressVerzending">
                    Express verzending
                </label>
        </div>
        <div class="radio-label">
                <label>
                    <input type="radio" name="betaalmethode" id="iDeal">
                    iDeal
                </label>
                <label>
                    <input type="radio" name="betaalmethode" id="Nerdygadgets Giftcard">
                    Nerdygadgets Gifcard
                </label>
        </div>
    </div>

    <div class="comments">
        <div>
            <label for="bezorgInstructies">bezorg instructies</label>
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
