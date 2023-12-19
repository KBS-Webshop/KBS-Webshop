<?php
include __DIR__ . "/components/header.php";
include __DIR__ . "/helpers/utils.php";

if (isset($_COOKIE["basket"]) AND !cookieEmpty()) {
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
                $totalprice = 0;
                foreach ($basket_contents as $item) {
                    $StockItem = getStockItem($item["id"], $databaseConnection);
                    $currentDiscount = getDiscountByStockItemID($item["id"], $databaseConnection);

                    if ($currentDiscount) {
                        $totalprice += calculateDiscountedPriceBTW($StockItem["SellPrice"], $currentDiscount["DiscountPercentage"], $StockItem['TaxRate'], $item["amount"], true);
                    } else {
                        $totalprice += calculatePriceBTW($StockItem["SellPrice"], $StockItem['TaxRate'], $item["amount"], true);
                    }

                    echo ("<tr> <td>" . $StockItem['StockItemName'] . "</td>");
                    echo ("<td>" . $item['amount'] . "</td>");

                    if ($currentDiscount) {
                        echo "<td>". calculateDiscountedPriceBTW($StockItem["SellPrice"], $currentDiscount["DiscountPercentage"], $StockItem['TaxRate'], $item["amount"]);
                    } else {
                        echo "<td>". calculatePriceBTW($StockItem["SellPrice"], $StockItem['TaxRate'], $item["amount"]);
                    }
                }
                ?>

                <?php if (getDealInCart() != null) { ?>
                <tr class='receivedTotalPrice'>
                    <td></td>
                    <th>Prijs</th>
                    <td><?php print formatPrice($totalprice) ?></td>
                </tr>
                <tr>
                    <td></td>
                    <th>Korting</th>
                    <td><?php print "-" . formatPrice($totalprice / 100 * getLoyaltyDeal(getDealInCart(), $databaseConnection)["discount"]) ?></td>
                </tr>
                <?php } ?>
                <tr class='receivedTotalPrice'>
                    <td></td>
                    <th>Punten</th>
                    <td><?php print calculateLoyaltyPoints(calculatePriceWithDeals($totalprice, $databaseConnection), $databaseConnection) ?></td>
                </tr>
                <tr>
                    <td></td>
                    <th>Totaalprijs</th>
                    <td><?php print formatPrice(calculatePriceWithDeals($totalprice, $databaseConnection)); ?></td>
                </tr>
            </table>

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
        <input type="text" name="naam" id="naam" required>
    </div>

    <div class="naw-input form-width-2">
        <div class="naw-input-inner">
            <label for="straatnaam" class="inline-label">
                Straatnaam <span class="required"></span>
            </label>
            <input type="text" name="adress" id="adress" required>
        </div>
        <div class="naw-input-inner">
            <label for="huisnummer" class="inline-label">
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
            <label for="name" class ="inline-label">
                Stad <span class="required"></span>
            </label>
            <input type="text" name="stad" id="stad" required>
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
            <input type="text" name="telefoonnummer" id="telefoonnummer">
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
    <div class="comments">
        <div>
            <label for="opmerkingen">Instructies voor de bezorger. (Optioneel)</label>
        </div>
        <div>
            <textarea id="bezorgInstructies" name="bezorgInstructies" rows="6" cols="50"></textarea>
        </div>
    </div>

    <div class="naw-submit-wrapper">
        <input type="hidden" name="price" id="price" value="<?php print str_replace("€", "", $totalprice) ?>">
        <input type="submit" name="bevestig" value="Afrekenen" class="button primary">
    </div>
</form>
</html>



<?php

include __DIR__ . "/components/footer.php"
?>
