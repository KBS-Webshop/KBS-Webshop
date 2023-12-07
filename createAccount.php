<?php
include __DIR__ . "/components/header.php";
include __DIR__ . "/helpers/utils.php";
?>
    <style>
        .loginBox {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .loginBox .login-input {
            width: 25%;
        }
        .informationBox{
            display: flex;
            justify-content: center;
            flex-direction: row;
        }
    </style>
    <form method="post" class="loginBox" action="CustomerLogin.php">
        <h2>Inloggen</h2>
        <div class="login-input">
            <label for="email">email</label>
            <input type="text" class="loginEmail" name="email" required>
        </div>
        <div class="login-input">
            <label for="password">wachtwoord</label>
            <input type="password" class="loginPassword" name="password" required>
        </div>
        <form method="POST" name="bevestig" class="naw-form" action="createAccount.php">
            <div class="naw-input">
                <label for="name">
                    Naam <span class="required"></span>
                </label>
                <input type="text" name="naam" id="naam" required>
            </div>

            <div class="naw-input form-width-2">
                <div class="naw-input-inner">
                    <label for="straatnaam" class="inline-label">
                        Straatnaam <span class="required"></span>
                    </label>
                    <input type="text" name="adress" id="adress" required>
                </div>
                <div class="naw-input-inner">
                    <label for="huisnummer" class="inline-label">
                        Huisnummer <span class="required"></span>
                    </label>
                    <input type="text" name="huisnummer" id="huisnummer" required>
                </div>
            </div>

            <div class="naw-input form-width-4">
                <div class="naw-input-inner">
                    <label for="name" class="inline-label">
                        Postcode <span class="required"></span>
                    </label>
                    <input type="text" name="postcode" id="postcode" required>
                </div>
                <div class="naw-input-inner">
                    <label for="name" class ="inline-label">
                        Stad <span class="required"></span>
                    </label>
                    <input type="text" name="stad" id="stad" required>
                </div>
            </div>

            <div class="naw-input form-width-5">
                <div class="naw-input-inner">
                    <label for="name" class="required">
                        Telefoonnummer
                    </label>
                    <input type="text" name="telefoonnummer" id="telefoonnummer">
                </div>
            </div>

            <div class="naw-input form-width-5">
                <div class="naw-input-inner">
                    <label for="name">
                        Email-adres <span class="required"></span>
                    </label>
                    <input type="text" name="email" id="email" required>
                </div>
            </div>




<?php
include __DIR__ . "/components/footer.php"
?>