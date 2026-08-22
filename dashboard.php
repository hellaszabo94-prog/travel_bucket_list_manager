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
        <header class="bg-white border-b border-sky-100 shadow-sm">
            <nav class="max-w-7xl mx-auto px-4 py-4 flex items-center gap-8">
                <img src="logo/logo.png" alt="Travel Bucket List Manager logo" class="h-14 w-auto">
                <ul class="flex gap-6">
                    <li><a href="dashboard.php" class="font-medium text-sky-700 hover:text-sky-400 transition">Home</a></li>
                    <li><a href="add_destination.php" class="font-medium text-sky-700 hover:text-sky-400 transition">Add a new destination</a></li>
                    <li><a href="my_destinations.php" class="font-medium text-sky-700 hover:text-sky-400 transition">My Destinations</a></li>
                </ul>
                <a href="logout.php" class="ml-auto font-medium text-sky-700 hover:text-sky-400 transition">Log out</a>
            </nav>
        </header>
        <main class="max-w-5xl mx-auto px-4 py-8">  
            <h1 class="text-4xl font-bold text-sky-950">Travel Bucket List Manager</h1>
            <h2 class="mt-3 text-lg text-sky-600" >Welcome to your personal Travel Bucket List Manager, <?= htmlspecialchars($_SESSION["Firstname"], ENT_QUOTES, "UTF-8") ?>!</h2>
            
        </main>
    </body>
</html>