<?php
include "../components/beheer-header.php";
include "../helpers/utils.php";

$currentDiscounts = getCurrentDiscounts($databaseConnection);

$errors = array(
        "error" => false,
        "message" => ""
);

function set_error($message) {
    global $errors;
    $errors["error"] = true;
    $errors["message"] = $message;
}

if(isset($_POST["stockItemID"]) && isset($_POST["discountPercentage"]) && isset($_POST['startDate']) && isset($_POST["endDate"])) {
    $startDate = Date($_POST['startDate']);
    $endDate = Date($_POST['endDate']);

    if ($endDate < $startDate) {
        set_error("De einddatum moet na de startdatum zijn.");
    } else if (intval($_POST["discountPercentage"], 10) > 100 || intval($_POST["discountPercentage"], 10) < 0) {
        set_error("De korting moet tussen de 0 en 100 procent zijn.");
        if (productHasDiscount($_POST["stockItemID"], $databaseConnection)) {
            if (!$_POST["discountPercentage"]) {$_POST["discountPercentage"] = '';}
            if (!$_POST["startDate"]) {$_POST["startDate"] = '';}
            if (!$_POST["endDate"]) {$_POST["endDate"] = '';}

            updateDiscount($_POST['stockItemID'], $databaseConnection, $_POST["discountPercentage"], $_POST['startDate'], $_POST["endDate"]);
        } else {
            createDiscount($_POST['stockItemID'], $_POST['discountPercentage'], $_POST['startDate'], $_POST['endDate'], $databaseConnection);
        }
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
                <input type="text" name="stockItemID" id="title" required>
            </div>
            <div>
                <label for="points">(Nieuwe) korting percentage <span class="required"></span></label>
                <input type="number" name="discountPercentage" id="discount">
            </div>
            <div>
                <label for="discount">(Nieuwe) actie van <span class="required"></span></label>
                <input type="date" name="startDate" id="startDate">
            </div>
            <div>
                <label for="discount">(Nieuwe) actie tot <span class="required"></span></label>
                <input type="date" name="endDate" id="endDate">
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
            <table class="loyalty-wrapper">
                <tr>
                    <td>Product ID</td>
                    <td>Korting percentage</td>
                    <td>Actie van</td>
                    <td>Actie tot</td>
                </tr>

                <?php

                foreach ($currentDiscounts as $discount) {
                    ?>
                    <tr>
                        <td><?php print $discount['StockItemID'] ?></td>
                        <td><?php print $discount['DiscountPercentage'] ?></td>
                        <td><?php print $discount['StartDate'] ?></td>
                        <td><?php print $discount['EndDate'] ?></td>
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

<?php
include "../components/footer.php"
?>
