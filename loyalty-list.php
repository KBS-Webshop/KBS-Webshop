<?php
include __DIR__ . "/components/header.php";
include __DIR__ . "/helpers/utils.php";


if (isset($_POST["addId"]) && isset($_POST["points"])) {
    if (getDealInCart() == $_POST["addId"]) {
        removeDealFromCart();
    } else {
        addDealToCart($_POST["addId"], $_POST["points"]);
    }
    header("Refresh: 0; url=loyalty-list.php");

}

$loyaltyDeals = getAllLoyaltyDeals($databaseConnection);
$currentPoints = getPoints($_SESSION['user']['PersonID'], $databaseConnection);
?>

    <div class="container">
    <p class="alert alert-primary">Let op: er kan maar 1 deal gelijktijdig actief zijn!</p>
    <h3>Punten: <?php print $currentPoints ?></h3>
    <?php foreach($loyaltyDeals as $deal) { ?>
        <div class="row">
            <div class="card col my-3" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title"><?php print $deal["title"] ?></h5>
                    <h6 class="card-subtitle mb-2 text-muted"><?php print $deal["points"] ?></h6>
                    <p class="card-text"><?php print $deal["description"] ?></p>
                    <form method="POST">
                        <input type="hidden" name="addId" id="addId" value="<?php print $deal["id"] ?>">
                        <input type="hidden" name="points" id="points" value="<?php print $deal["points"] ?>">

                        <?php if ($currentPoints < $deal["points"]) {?>
                            <input class="button secondary" type="submit" value="Korting activeren" disabled>
                        <?php } else if (getDealInCart() == $deal["id"]) { ?>
                            <input class="button danger" type="submit" value="Korting deactiveren">
                        <?php } else { ?>
                            <input class="button primary" type="submit" value="Korting activeren">
                        <?php } ?>
                    </form>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>

<?php
include __DIR__ . "/components/footer.php"
?>