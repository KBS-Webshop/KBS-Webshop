<?php
include "../components/beheer-header.php";
include "../helpers/utils.php";

$currentDiscounts = getCurrentDiscounts($databaseConnection);

if(isset($_POST["stockItemID"]) && isset($_POST["discountPercentage"]) &&
    isset($_POST["endDate"])) {
    if (isset($_GET["id"])) {
        if (!isset($_POST["discountPercentage"])) {$_POST["discountPercentage"] = '';}
        if (!isset($_POST["endDate"])) {$_POST["endDate"] = '';}

        updateDiscountOrEndDate($_GET["id"], $databaseConnection, $_POST["discountPercentage"], $_POST["endDate"]);
    } else {
        createDiscount($_POST['stockItemID'], $_POST['discountPercentage'], $_POST['startDate'], $_POST['endDate'], $databaseConnection);
    }
}
?>


<div id="CenteredContent" class="loyalty-wrapper">
    <h3>Korting toevoegen</h3>
    <form class="loyalty-form" method="POST">
        <div>
            <label for="title">Product ID <span class="required"></span></label>
            <input type="text" name="stockItemID" id="title" required>
        </div>
        <div>
            <label for="points">Korting percentage <span class="required"></span></label>
            <input type="number" name="discountPercentage" id="discount">
        </div>
        <div>
            <label for="discount">Actie van <span class="required"></span></label>
            <input type="date" name="startDate" id="startDate">
        </div>
        <div>
            <label for="discount">Actie tot <span class="required"></span></label>
            <input type="date" name="endDate" id="endDate">
        </div>
        <div>
            <input type="submit" value="Verstuur" class="button primary">
        </div>
    </form>
<!--    <a href="/beheer/loyalty.php"><i class="fa fa-arrow-left"></i> Ga terug</a>-->
</div>
<?php
if (!empty($currentDiscounts)) {
    foreach ($currentDiscounts as $discount) {
        ?>

        <div>
            <?php // print_r($discount) ?>
        </div>

        <?php
    }
}

include "../components/footer.php"
?>
