<?php
include "../components/beheer-header.php";
include "../helpers/utils.php";

# SQL SCRIPTJE
// ALTER TABLE stockitems ADD IsHighlighted BOOLEAN DEFAULT 0

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
    removeSpotlight($_POST['delete'], $databaseConnection);
    header("Location: ./productOnIndex.php");
    die();
}

if (isset($_POST["stockItemID"])) {
    if (!getStockItem($_POST["stockItemID"], $databaseConnection)) {
        set_error("Het gegeven product bestaat niet.", "product");
    } else {

        setSpotlight($_POST['stockItemID'], $databaseConnection);

        header("Location: ./productOnIndex.php");
        die();
    }
}
?>

<div class="form-container">
    <h3>Product Uitlichten</h3>
    <p class="error-text"><?php if ($errors['error']) {print($errors['message']);} ?></p>
    <form class="loyalty-form" method="POST">
        <div>
            <label for="title">Product ID <span class="required"></span></label>
            <input type="number" name="stockItemID" id="title" required <?php if ($errors['error']) {print( 'value="' . $_POST['stockItemID'] . '"' . ($errors['identity'] == 'product' ? 'class="error-input"' : ''));}?>>
        </div>
        <br>
        <div>
            <input type="submit" value="Verstuur" class="button primary">
        </div>
    </form>

    <br>
    <p>Uitgelicht:</p>
    <table>
        <tr>
            <th>StockItemID</th>
            <th>StockItemName</th>
            <th></th>
        </tr>
        <?php foreach (getSpotlight($databaseConnection) as $spotlight) { ?>
            <tr>
                <td><?php echo $spotlight['StockItemID'] ?></td>
                <td><?php echo $spotlight['StockItemName'] ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="delete" value="<?php echo $spotlight['StockItemID'] ?>">
                        <input type="submit" value="Verwijder" class="button primary">
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
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
include "../components/footer.php";
?>
