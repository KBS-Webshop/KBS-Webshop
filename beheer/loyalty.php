<?php
include "../components/beheer-header.php";
include "../helpers/utils.php";

$loyaltyItems = getAllLoyaltyDeals($databaseConnection);
?>

<div id="CenteredContent">
    <h3>Loyaliteits kortingen</h3>
    <div>
        <a href="deal.php" class="button primary btn-small">Maak nieuwe aan</a>
        <a href="loyalty-configuration.php" class="button secondary btn-small">Configuratie</a>
    </div>
    <table class="loyalty-table">
        <tr>
            <th>Title</th>
            <th>Price in points</th>
        </tr>
            <?php 
            foreach($loyaltyItems as $item) { 
            ?>

                <tr onClick="window.location.href='deal.php?id=<?php print $item["id"] ?>'">
                    <td><?php print $item["title"] ?></td>
                    <td><?php print $item["points"] ?></td>
                </tr>

            <?php
            }
            ?>
    </table>
</div>

<?php
include "../components/footer.php"
?>
