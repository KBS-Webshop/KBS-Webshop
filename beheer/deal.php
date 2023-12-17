<?php
include "../components/beheer-header.php";
include "../helpers/utils.php";

if(isset($_GET["id"])) {
    $loyaltyItem = getLoyaltyDeal($_GET["id"], $databaseConnection);
}

if(isset($_POST["title"])) {
    if (isset($_GET["id"])) {
        updateLoyaltyDeal($_GET["id"], $_POST, $databaseConnection);
    } else {
        createLoyaltyDeal($_POST, $databaseConnection);
    }
    header("Location: loyalty.php");
}
?>


<div id="CenteredContent" class="loyalty-wrapper">
    <h3>Loyaliteits deal</h3>
    <form class="loyalty-form" method="POST">
        <div>
            <label for="title">Title <span class="required"></span></label>
            <input type="text" name="title" id="title" <?php if(isset($_GET["id"])) print "value='" . $loyaltyItem["title"] . "'" ?>>
        </div>
        <div>
            <label for="description">Beschrijving <span class="required"></span></label>
            <input type="text" name="description" id="description" <?php if(isset($_GET["id"])) print "value='" . $loyaltyItem["description"] . "'" ?>>
        </div>
        <div>
            <label for="points">Benodigde punten <span class="required"></span></label>
            <input type="number" name="points" id="points" <?php if(isset($_GET["id"])) print "value='" . $loyaltyItem["points"] . "'" ?>>
        </div>
        <div>
            <label for="discount">Korting percentage <span class="required"></span></label>
            <input type="number" name="discount" id="discount" <?php if(isset($_GET["id"])) print "value='" . $loyaltyItem["discount"] . "'" ?>>
        </div>
        <div>
            <input type="checkbox" name="free_shipping" id="free_shipping" value="1" <?php if(isset($_GET["id"]) && $loyaltyItem["free_shipping"] == 1) print("checked") ?>>
            <label for="free_shipping">Gratis verzending</span></label>
        </div>
        <div>
            <input type="submit" value="Verstuur" class="button primary">
        </div>
    </form>
    <a href="loyalty.php"><i class="fa fa-arrow-left"></i> Ga terug</a>
</div>
<?php
include "../components/footer.php"
?>
