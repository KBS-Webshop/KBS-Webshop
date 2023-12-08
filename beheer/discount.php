<?php
include "../components/beheer-header.php";
include "../helpers/utils.php";

// KORTINGEN EINDIGEN 6 UUR VOOR DE GEGEVEN DATUM
$currentDiscounts = getCurrentDiscounts($databaseConnection);

$errors = array(
        "error" => false,
        "message" => "",
        "identity" => ""
);

function set_error($message, $identity) {
    global $errors;
    $errors["error"] = true;
    $errors["message"] = $message;
    $errors["identity"] = $identity;
}

if (isset($_POST['delete'])) {
    deleteDiscount($_POST['delete'], $databaseConnection);
    header("Location: /KBS-Webshop/beheer/discount.php");
    die();
} else if (isset($_POST["stockItemID"]) && isset($_POST["discountPercentage"]) && isset($_POST['startDate']) && isset($_POST["endDate"])) {
    $startDate = strtotime($_POST['startDate']);
    $endDate = strtotime($_POST['endDate']);

    if (!getStockItem($_POST["stockItemID"], $databaseConnection)) {
        set_error("Het gegeven product bestaat niet.", "product");
    } else if (intval($_POST["discountPercentage"], 10) > 100 || intval($_POST["discountPercentage"], 10) < 1) {
        set_error("De korting moet tussen de 1 en 100 procent zijn.", "discount");
    } else if ($endDate < $startDate) {
        set_error("De einddatum moet na de startdatum zijn.", "date");
    } else if ($endDate < time()) {
        set_error("De einddatum is in het verleden.", "expired");
    } else {
        if (productHasDiscount($_POST["stockItemID"], $databaseConnection)) {
            updateDiscount($_POST['stockItemID'], $databaseConnection, $_POST["discountPercentage"], $_POST['startDate'], $_POST["endDate"]);
        } else {
            createDiscount($_POST['stockItemID'], $_POST['discountPercentage'], $_POST['startDate'], $_POST['endDate'], $databaseConnection);
        }
        header("Location: /KBS-Webshop/beheer/discount.php");
        die();
    }
}
?>

<div class="discount-container">
    <div class="half left-half">
        <h3>Korting toevoegen/aanpassen</h3>
        <p class="error-text"><?php if ($errors['error']) {print($errors['message']);} ?></p>
        <form class="loyalty-form" method="POST">
            <div>
                <label for="title">Product ID <span class="required"></span></label>
                <input type="number" name="stockItemID" id="title" required <?php if ($errors['error']) {print( 'value="' . $_POST['stockItemID'] . '"' . ($errors['identity'] == 'product' ? 'class="error-input"' : ''));}?>>
            </div>
            <div>
                <label for="points">(Nieuwe) korting percentage <span class="required"></span></label>
                <input type="number" name="discountPercentage" id="discount" <?php if ($errors['error']) {print( 'value="' . $_POST['discountPercentage'] . '"' . ($errors['identity'] == 'discount' ? 'class="error-input"' : ''));}?>>
            </div>
            <div>
                <label for="discount">(Nieuwe) actie van <span class="required"></span></label>
                <input type="date" name="startDate" id="startDate" <?php if ($errors['error']) {print( 'value="' . $_POST['startDate'] . '"' . ($errors['identity'] == 'date' ? 'class="error-input"' : ''));}?>>
            </div>
            <div>
                <label for="discount">(Nieuwe) actie tot <span class="required"></span></label>
                <input type="date" name="endDate" id="endDate" <?php if ($errors['error']) {print( 'value="' . $_POST['endDate'] . '"' . ($errors['identity'] == 'date' || $errors['identity'] == 'expired' ? 'class="error-input"' : ''));}?>>
            </div>
            <br>
            <div>
                <input type="submit" value="Verstuur" class="button primary">
            </div>
        </form>
    </div>

    <div class="half right-half">
        <?php
        if (!empty($currentDiscounts)) {
            ?>
            <br>
            <h3>Huidige kortingen</h3>
            <table class="discount-table">
                <tr>
                    <td>Actie ID</td>
                    <td>Product ID</td>
                    <td>Productnaam</td>
                    <td>Korting percentage</td>
                    <td>Actie van</td>
                    <td>Actie tot</td>
                    <td></td>
                </tr>

                <?php

                foreach ($currentDiscounts as $discount) {
                    ?>
                    <tr>
                        <td class="anti-overflow"><?php print $discount['SpecialDealID'] ?></td>
                        <td class="anti-overflow"><?php print $discount['StockItemID'] ?></td>
                        <td class="anti-overflow"><?php print $discount['StockItemName'] ?></td>
                        <td class="anti-overflow"><?php print intval($discount['DiscountPercentage']) . '%' ?></td>
                        <td class="anti-overflow"><?php print $discount['StartDate'] ?></td>
                        <td class="anti-overflow"><?php print $discount['EndDate'] ?></td>
                        <td class="anti-overflow">
                            <form method="POST">
                                <input type="hidden" name="delete" value="<?php print $discount['SpecialDealID'] ?>">
                                <input type="submit" value="Verwijder" class="button secondary" onclick="return confirm('Weet je zeker dat je de actie met ID <?php echo $discount['SpecialDealID'] ?> wilt verwijderen?')">
                            </form>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <?php
        }
        ?>
    </div>
</div>

<script type="application/javascript">
    document.addEventListener("DOMContentLoaded", function () {
        for (let el of document.getElementsByClassName('error-input')) {
            el.addEventListener("input", function () {
                this.classList.remove("error-input");
            });
        }
    });
</script>

<?php
include "../components/footer.php"
?>
