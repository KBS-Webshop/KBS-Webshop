<?php
include __DIR__ . "/components/header.php";
include __DIR__ . "/helpers/utils.php";
?>

<div>
    Verzendadres
</div>

<form action="POST">
    <div class="naw-input">
            <label for="name">
              Naam
            </label>
        <input type="text" name="naam" id="naam">
    </div>

    <div class="naw-input form-width-2">
        <div class="naw-input-inner">
                <label for="name">
                    Straatnaam
                </label>
            <input type="text" name="straatnaam" id="straatnaam">
        </div>
        <div class="naw-input-inner2">
                <label for="name">
                    Huisnummer
                </label>
            <input type="text" name="huisnummer" id="huisnummer">
        </div>
    </div>

    <div class="naw-input form-width-4">
            <div class="naw-input-inner">
                <label for="name">
                    Postcode
                </label>
            <input type="text" name="postcode" id="postcode">
        </div>
        <div class="naw-input-inner">
            <label for="name">
                Land
            </label>
            <input type="text" name="land" id="land">
        </div>
        <div class="naw-input-inner">
            <label for="name">
                Provincie
            </label>
            <input type="text" name="provincie" id="provincie">
        </div>
        <div class="naw-input-inner">
            <label for="name">
                Plaats
            </label>
            <input type="text" name="plaats" id="plaats">
        </div>
    </div>

    <div class="naw-input form-width-5">
        <div class="naw-input-inner">
            <label for="name">
                Telefoonnummer
            </label>
            <input type="text" name="telefoonnummer" id="telefoonnummer">
        </div>
    </div>

    <div>
            <label for="name">
                <input type="radio" name="standaardVerzending" id="standaardVerzending">
                Standaard verzending
            </label>
            <label for="name">
                <input type="radio" name="expressVerzending" id="expressVerzending">
                Express verzending
            </label>
    </div>

    <div>
        <div>
            <label for="opmerkingen">Opmerkingen:</label>
        </div>
        <div>
            <textarea id="opmerkingen" name="opmerkingen" rows="4" cols="50"></textarea>
        </div>
    </div>


</form>

<div>
    <button onclick="window.location.href='afrekenen.php';">
        Afrekenen
    </button>
</div>

<?php
include __DIR__ . "/components/footer.php"
?>
