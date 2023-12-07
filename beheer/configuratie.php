<?php
include "../components/beheer-header.php";
include "../helpers/utils.php";

if (isset($_POST["points"]) && isset($_POST["price"])) {
    updateLoyaltyConfiguration($_POST["points"], $_POST["price"], $databaseConnection);
}

$configuration = getLoyaltyConfiguration($databaseConnection);
?>

<div id="CenteredContent" class="loyalty-wrapper">
    <h3>Configuratie loyaliteiten programma</h3>
    <form class="sentence-form" method="POST">
        <div class="flex-inline-form">
            <p>Je krijgt</p>
            <input type="number" name="points" id="points" value="<?php print $configuration["points_per_price"] ?>">
            <p>punten per</p>
            <input type="number" name="price" id="price" value="<?php print $configuration["price_per_points"] ?>">
            <p>euro.</p>
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
