<?php
include __DIR__ . "/components/header.php";
include __DIR__ . "/helpers/utils.php";


?>
<html>
<form method="post" name="mislukt" action="winkelmand.php">
    <input type="submit" name="mislukt" value="betaling annuleren">
</form>
<form method="post" name="gelukt" action="orderbevestiging.php">
    <input type="submit" name="gelukt" value="betaling geslaagd ">
</form>
</html>
<?php
include __DIR__ . "/components/footer.php"
?>
