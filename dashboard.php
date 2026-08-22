<?php
// load configuration, helper functions and authentication
require ("includes/common.inc.php");
require ("includes/config.inc.php");
require ("includes/auth.inc.php");

//test($_SESSION);


?>

<!doctype html>
<html lang="en">
    <head>
        <title>Travel Bucket List Manager</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/tailwind.css">
        <link rel="stylesheet" href="css/stylesheet.css">
    </head>
    <body class="bg-sky-100 text-sky-950 min-h-screen">
        <main class="max-w-6xl mx-auto px-4 py-8">  
            <h1 class="text-4xl font-bold text-sky-950">Travel Bucket List Manager</h1>
            <h2 class="mt-3 text-lg text-sky-600" >Welcome to your personal Travel Bucket List Manager, <?= htmlspecialchars($_SESSION["Firstname"], ENT_QUOTES, "UTF-8") ?>!</h2>
            <nav>
                <ul>
                    <li><a href="dashboard.php">Home</a></li>
                    <li><a href="add_destination.php">Add a new destination</a></li>
                    <li><a href="my_destinations.php">My Destinations</a></li>
                    <li><a href="logout.php">Log out</a></li>
                </ul>
            </nav>
        </main>
    </body>
</html>