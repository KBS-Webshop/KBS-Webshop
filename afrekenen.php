<?php
include __DIR__ . "/components/header.php";
include __DIR__ . "/helpers/utils.php";

$_SESSION["naam"]=$_POST["naam"];
$_SESSION["telefoonnummer"]=$_POST["telefoonnummer"];
$_SESSION["adress"]=$_POST["adress"] . " " . $_POST["huisnummer"];
$_SESSION["postcode"]=$_POST["postcode"];
$_SESSION["stad"]=$_POST["stad"];
$_SESSION["bezorgInstructies"] = $_POST["bezorgInstructies"];
$_SESSION["provincie"] = $_POST["provincie"];
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
