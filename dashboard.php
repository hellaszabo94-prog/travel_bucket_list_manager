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
    <body class="bg-travel-50 text-travel-950 min-h-screen">
        <header class="bg-white border-b border-travel-100 shadow-sm">
            <nav class="max-w-7xl mx-auto px-4 py-4 flex flex-col md:flex-row md:items-center gap-4 md:gap-8">
                <img src="logo/logo.png" alt="Travel Bucket List Manager logo" class="w-36 md:w-auto md:h-12 self-center md:self-auto">
                <ul class="flex flex-col md:flex-row gap-3 md:gap-6">
                    <li><a href="dashboard.php" class="font-medium text-travel-600 hover:text-travel-500 transition">Home</a></li>
                    <li><a href="add_destination.php" class="font-medium text-travel-600 hover:text-travel-500 transition">Add a new destination</a></li>
                    <li><a href="my_destinations.php" class="font-medium text-travel-600 hover:text-travel-500 transition">My Destinations</a></li>
                </ul>
                <a href="logout.php" class="md:ml-auto font-medium text-travel-700 hover:text-travel-500 transition">Log out</a>
            </nav>
        </header>
        <main class="max-w-5xl mx-auto px-4 py-8">  
            <section class="bg-white rounded-xl shadow-sm border border-travel-100 p-6">
                <h1 class="text-4xl font-bold text-travel-900">Travel Bucket List Manager</h1>
                <h2 class="mt-3 text-lg text-travel-700" >Welcome to your personal Travel Bucket List Manager, <?= htmlspecialchars($_SESSION["Firstname"], ENT_QUOTES, "UTF-8") ?>!</h2>
                <div class="mt-8 max-w-3xl">
                    <h3 class="text-2xl font-semibold text-travel-900">About the application</h3>
                    <p class="mt-3 text-travel-800 leading-relaxed">The Travel Bucket List Manager helps you organize the places you would like to visit and keep track of your travel plans.</p>
                    <p class="mt-3 text-travel-800 leading-relaxed">You can add destinations, assign a travel status, upload an image and manage your saved destinations from your personal list.</p>
                </div>
                <div class="mt-10 border-t border-travel-100 pt-8">
                    <h3 class="text-2xl font-semibold text-travel-900">How does it work?</h3>
                        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="border border-travel-100 rounded-lg p-4 bg-gradient-to-r from-travel-100 to-travel-200">
                                <h4 class="text-lg font-semibold text-travel-900">1. Add a destination</h4>
                                <p class="mt-2 text-travel-800">Add a place you would like to visit.</p>
                            </div>
                            <div class="border border-travel-100 rounded-lg p-4 bg-gradient-to-r from-travel-200 to-travel-300">
                                <h4 class="text-lg font-semibold text-travel-900">2. Choose a status</h4>
                                <p class="mt-2 text-travel-800">Mark your destination according to your current travel plans.</p>
                            </div>
                            <div class="border border-travel-100 rounded-lg p-4 bg-gradient-to-r from-travel-300 to-travel-400">
                                <h4 class="text-lg font-semibold text-travel-900">3. Manage your destination</h4>
                                <p class="mt-2 text-travel-800">Upload an image and update or remove your saved destination.</p>
                            </div>
                        </div>
                </div>
            </section>
        </main>
        <footer class="mt-10 border-t border-travel-200">
            <div class="max-w-7xl mx-auto px-4 py-6">
                <p class="text-sm text-travel-700">© 2026 Hella Haraszti-Szabo. All rights reserved.</p>
            </div>
        </footer>
    </body>
</html>