<?php
require("includes/config.inc.php");
require("includes/common.inc.php");
require("includes/db.inc.php");

?>
<!doctype html>
<html lang="de">
    <head>
        <title>Registrirung</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="css/stylesheet.css">
    </head>
    <body>
    <h1>Meinen Reisemanager</h1>
    <h2>Registrierung</h2>
    
    <form method="post">
        <fieldset>
            <legend>Pflichtdaten</legend>
            <label>
                Emailadresse:
                <input type="email" name="E">
            </label>
            <br>
            <br>
            <label>
                Password (bitte mindestens acht Zeihnen):
                <input type="password" name="PWD">
            </label>
            <br>
            <br>
            <label>
                Password 2:
                <input type="password" name="PWD2">
            </label>
        </fieldset>
        <br>
        <fieldset>
            <legend>Weitere Daten</legend>
            <label>
                Vorname:
                <input type="text" name="VorN">
            </label>
            <br>
            <br>
            <label>
                Nachname:
                <input type="text" name="NachN" >
            </label>
            <br>
            <br>
            <label>
                Geburtsdatum:
                <input type="date" name="GebD">
            </label>
        </fieldset>
        <br>
        <input type="submit" value="Registrierung" name="regButton">
        <input type="submit" value="Login" name="logButton">
    </form>
    </body>
</html>