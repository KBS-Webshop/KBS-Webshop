<?php
include __DIR__ . "/components/header.php";
include __DIR__ . "/helpers/utils.php";
$_SESSION["user"]["customer"]["CustomerName"] = "";
$_SESSION["user"]["customer"]["DeliveryAddressLine1"] = null;
$_SESSION["user"]["customer"]["PhoneNumber"] = "";
$_SESSION["user"]["customer"]["EmailAddress"] = "";
$_SESSION["user"]["customer"]["cityName"] = "";
$_SESSION["user"]["customer"]["straatnaam"] = "";
$_SESSION["user"]["customer"]["huisnummer"] = "";
$_SESSION["user"]["customer"]["PostalPostalCode"] = "";
if (isset($_COOKIE["basket"]) AND !cookieEmpty()) {
    if (isset($_SESSION["userEmail"]) && isset($_SESSION["password"])) {
        if ($_SESSION["userEmail"] !== null && $_SESSION["password"] !== null) {
            getUserCustomerInfo($databaseConnection, $_SESSION["userEmail"], $_SESSION["hashedPassword"]);
        }
    }
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
        $totalprice=0;
        foreach ($basket_contents as $item) {
            $StockItem = getStockItem($item["id"], $databaseConnection);
            $totalprice += round($item['amount'] * $StockItem['SellPrice'], 2);
            echo ("<tr> <td>" . $StockItem['StockItemName'] . "</td>");
            echo ("<td>" . $item['amount'] . "</td>");
            echo "<td>".str_replace(".",",",sprintf("€%.2f", $StockItem['SellPrice'] * $item["amount"]));
        }
        $totalprice = sprintf("€%.2f", $totalprice);
        echo ("<tr class='receivedTotalPrice'> <td></td> <th>totaalprijs</th>");
        $totalprice1=str_replace(".",",",$totalprice);
        echo("<td>$totalprice1</td></tr>");
        echo '</table>';

        ?>

        <?php } ?>
</div>
<html>

<h2>Bestelgegevens</h2>

<p id="errorMsg" class="error-text"></p>

<form method="POST" name="bevestig" class="naw-form" action="afrekenen.php">
    <div class="naw-input">
        <label for="name">
          Naam <span class="required"></span>
        </label>
        <input type="text" name="naam" id="naam" value="<?php if(isset($_SESSION["user"]["customer"]["CustomerName"])) { print($_SESSION["user"]["customer"]["CustomerName"]); } else { print ""; } ?>" required>
    </div>
    <?php
    if (isset($_SESSION["user"]["customer"]["DeliveryAddressLine1"])) {
        if ($_SESSION["user"]["customer"]["DeliveryAddressLine1"] != null) {
            $adress = explode(" ", $_SESSION["user"]["customer"]["DeliveryAddressLine1"]);
            $_SESSION["user"]["customer"]["straatnaam"] = $adress[0];
            $_SESSION["user"]["customer"]["huisnummer"] = $adress[1];
        }
    }
    ?>

    <div class="naw-input form-width-2">
        <div class="naw-input-inner">
            <label for="straatnaam" class="inline-label">
                Straatnaam <span class="required"></span>
            </label>
            <input type="text" name="adress" id="adress" value="<?php if(isset($_SESSION["user"]["customer"]["straatnaam"])) { print($_SESSION["user"]["customer"]["straatnaam"]); } else { print ""; } ?>" required>
        </div>
        <div class="naw-input-inner">
            <label for="huisnummer" class="inline-label">
                Huisnummer <span class="required"></span>
            </label>
            <input type="text" name="huisnummer" id="huisnummer" value="<?php if(isset($_SESSION["user"]["customer"]["huisnummer"])) { print($_SESSION["user"]["customer"]["huisnummer"]); } else { print ""; } ?>" required>
        </div>
    </div>

    <div class="naw-input form-width-4">
            <div class="naw-input-inner">
                <label for="name" class="inline-label">
                    Postcode <span class="required"></span>
                </label>
            <input type="text" name="postcode" id="postcode" value="<?php if(isset($_SESSION["user"]["customer"]["PostalPostalCode"])) { print($_SESSION["user"]["customer"]["PostalPostalCode"]); } else { print ""; } ?>" required>
        </div>
        <div class="naw-input-inner">
            <label for="name" class ="inline-label">
                Stad <span class="required"></span>
            </label>
            <input type="text" name="stad" id="stad" value="<?php if(isset($_SESSION["user"]["customer"]["cityName"])) { print($_SESSION["user"]["customer"]["cityName"]); } else { print ""; } ?>" required>
        </div>
        <div class="naw-input-inner">
            <label for="name" class ="inline-label">
                Provincie <span class="required"></span>
            </label>
            <select name="provincie" id="provincie" required>
                <option value="Overijssel">Overijssel</option>
                <option value="Groningen">Groningen</option>
                <option value="Noord-Holland">Noord-Holland</option>
                <option value="Zuid-Holland">Zuid-Holland</option>
                <option value="Drenthe">Drenthe</option>
                <option value="Gelderland">Gelderland</option>
                <option value="Zeeland">Zeeland</option>
                <option value="Utrecht">Utrecht</option>
                <option value="Friesland">Friesland</option>
                <option value="Limburg">Limburg</option>
                <option value="Limburg">Brabant</option>
                <option value="Flevoland">Flevoland</option>
            </select>
            <!-- <input type="text" name="provincie" id="provincie" required> -->
        </div>
    </div>

    <div class="naw-input form-width-5">
        <div class="naw-input-inner">
            <label for="name" class="required">
                Telefoonnummer
            </label>
            <input type="text" name="telefoonnummer" id="telefoonnummer" value="<?php if(isset($_SESSION["user"]["customer"]["PhoneNumber"])) { print($_SESSION["user"]["customer"]["PhoneNumber"]); } else { print ""; } ?>" required >
        </div>
    </div>

    <div class="naw-input form-width-5">
        <div class="naw-input-inner">
            <label for="name">
                Email-adres <span class="required"></span>
            </label>
            <input type="text" name="email" id="email" value="<?php if(isset($_SESSION["user"]["EmailAddress"])) { print($_SESSION["user"]["EmailAddress"]); } else { print ""; } ?>" required>
        </div>
    </div>
    <div class="naw-input form-width-5">
        <div class="naw-input-inner">
            <label for="name">
                akkoord met de terms of service <span class="required"></span>
                <input type="radio" name="options" value="option1" required>
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
        <input type="submit" name="bevestig" value="Afrekenen" class="button primary">
    </div>
</form>
</html>



<?php

include __DIR__ . "/components/footer.php"
?>
