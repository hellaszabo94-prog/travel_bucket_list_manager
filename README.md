# Travel Bucket List Manager

🌍 **Bilingual README / Zweisprachige README-Datei**

🇬🇧 [English](#english) | 🇩🇪 [Deutsch](#deutsch)

**Live Demo:**
https://travelmanager.infinityfreeapp.com

---

# English

## About the Project

**Travel Bucket List Manager** is a responsive full-stack web application for creating and managing a personal travel bucket list.

Users can register and log in, add destinations they would like to visit, organize them by travel status, search their saved destinations and upload an image for each destination.

The project was created as a practice project for my **Full-Stack Web Development portfolio**. Its main purpose was to deepen my knowledge of PHP, MySQL, authentication, relational databases, file uploads and responsive frontend development with Tailwind CSS.

## Features

* User registration and login
* Session-based authentication
* User-specific destination management
* Add new travel destinations
* Store country, city, destination name and description
* Assign a travel status to each destination
* Change the status of existing destinations
* Search by destination name, city or country
* Upload an image for a destination
* Replace an existing destination image
* Delete destination images
* Delete destinations
* Automatic cleanup of associated image data
* Image MIME type validation
* Maximum image upload size validation
* Responsive destination card layout
* Mobile-friendly navigation with burger menu
* Responsive forms and authentication pages
* Success, error and empty-state messages
* Custom Tailwind CSS color palette
* Custom Nunito typography

<img width="1378" height="1105" alt="Képernyőkép 2026-09-02 173520" src="https://github.com/user-attachments/assets/f6283273-85d5-428c-a499-13e11eac3f30" />


## Technologies

### Backend

* PHP
* MySQL / MariaDB
* PHP Sessions

### Frontend

* HTML5
* Tailwind CSS
* CSS3
* JavaScript

### Development Tools

* XAMPP
* phpMyAdmin
* npm
* Tailwind CSS CLI
* Git
* GitHub
* Visual Studio Code

## Main Functionality

### Authentication

Users can create their own account and log in using session-based authentication.

Each user only sees and manages their own saved destinations.

### Destination Management

Authenticated users can create destinations containing:

* destination name
* country
* city
* description
* travel status

Existing destinations can later be updated or deleted.

### Travel Status

Destinations can be assigned different statuses such as:

* Wishlist
* Planning
* Visited

The available statuses are stored in the database and loaded dynamically by the application.

### Search

The **My Destinations** page includes a search function.

Users can search their saved destinations by:

* destination name
* city
* country

### Image Management

Each destination can currently have one uploaded image.

The application supports:

* image upload
* image replacement
* image deletion
* file size validation
* MIME type validation
* randomized filenames
* physical file cleanup when an image or destination is deleted

The one-image-per-destination limitation is intentional for the current portfolio version.

## Responsive Design

The interface was redesigned using **Tailwind CSS** with a mobile-first approach.

The design includes:

* custom `travel` color palette
* Nunito font
* responsive grid layouts
* responsive forms
* destination cards
* hover and focus effects
* image placeholders
* mobile burger navigation
* styled success and error messages

The application is designed to work on both desktop and mobile devices.

## Database

The application uses a relational MySQL/MariaDB database.

The main tables are:

* `tbl_user`
* `tbl_country`
* `tbl_city`
* `tbl_status`
* `tbl_destination`
* `tbl_image`

A clean database structure is included in:

```text
database.sql
```

The database file contains the required table structure and application status data, but no personal user data or development test accounts.

## Local Installation

### Requirements

* PHP
* Apache or another PHP-compatible web server
* MySQL or MariaDB
* Node.js and npm
* Tailwind CSS CLI

### 1. Clone the repository

```bash
git clone https://github.com/hellaszabo94-prog/travel_bucket_list_manager.git
```

### 2. Import the database

Create a MySQL/MariaDB database and import:

```text
database.sql
```

### 3. Configure the database connection

Create:

```text
includes/config.inc.php
```

using the database connection structure from the example configuration file.

Enter your own:

* database hostname
* database username
* database password
* database name

The real configuration file is excluded from Git to prevent database credentials from being published.

### 4. Install frontend dependencies

```bash
npm install
```

### 5. Start Tailwind development mode

```bash
npm run dev
```

Tailwind will watch the project files and automatically rebuild the generated CSS when classes are changed.

### 6. Start the application

Run Apache and MySQL/MariaDB and open the application through your local web server.

For example with XAMPP:

```text
http://localhost/travel_bucket_list_manager/
```

## Live Demo

A deployed version of the application is available here:

**https://travelmanager.infinityfreeapp.com**

The live version uses a separate production database configuration.

## Portfolio Scope

This project was created as a learning and portfolio application rather than as a production travel platform.

The current version focuses on demonstrating:

* PHP backend development
* MySQL database operations
* relational database design
* user authentication
* session management
* CRUD-style operations
* search functionality
* file uploads
* file validation
* responsive frontend development
* Tailwind CSS
* deployment of a PHP/MySQL application

Some functionality is intentionally simplified. For example, each destination currently supports one uploaded image.

## Project Status

**Core functionality completed and deployed as a live portfolio project.**

---

# Deutsch

## Über das Projekt

Der **Travel Bucket List Manager** ist eine responsive Full-Stack-Webanwendung zur Erstellung und Verwaltung einer persönlichen Reise-Wunschliste.

Benutzer können sich registrieren und anmelden, Reiseziele speichern, ihren aktuellen Reisestatus verwalten, gespeicherte Ziele durchsuchen und für jedes Reiseziel ein Bild hochladen.

Das Projekt wurde als Übungsprojekt für mein **Full-Stack-Web-Development-Portfolio** entwickelt. Ziel war es insbesondere, meine Kenntnisse in PHP, MySQL, Authentifizierung, relationalen Datenbanken, Datei-Uploads sowie in der responsiven Frontend-Entwicklung mit Tailwind CSS zu vertiefen.

## Funktionen

* Benutzerregistrierung und Login
* Session-basierte Authentifizierung
* Benutzerspezifische Verwaltung von Reisezielen
* Neue Reiseziele hinzufügen
* Land, Stadt, Reiseziel und Beschreibung speichern
* Reisestatus zuweisen
* Status bestehender Reiseziele ändern
* Suche nach Reiseziel, Stadt oder Land
* Bild zu einem Reiseziel hochladen
* Vorhandenes Bild ersetzen
* Bilder löschen
* Reiseziele löschen
* Automatisches Löschen zugehöriger Bilddaten
* Validierung des MIME-Typs von Bildern
* Kontrolle der maximalen Upload-Größe
* Responsives Kartenlayout
* Mobile Navigation mit Burger-Menü
* Responsive Formulare und Authentifizierungsseiten
* Erfolgs-, Fehler- und Leerzustandsmeldungen
* Eigene Tailwind-CSS-Farbpalette
* Nunito als individuelle Schriftart

## Technologien

### Backend

* PHP
* MySQL / MariaDB
* PHP Sessions

### Frontend

* HTML5
* Tailwind CSS
* CSS3
* JavaScript

### Entwicklungswerkzeuge

* XAMPP
* phpMyAdmin
* npm
* Tailwind CSS CLI
* Git
* GitHub
* Visual Studio Code

## Zentrale Funktionen

### Authentifizierung

Benutzer können ein eigenes Konto erstellen und sich über eine Session-basierte Authentifizierung anmelden.

Jeder Benutzer sieht und verwaltet ausschließlich seine eigenen gespeicherten Reiseziele.

### Verwaltung von Reisezielen

Angemeldete Benutzer können neue Reiseziele mit folgenden Informationen erstellen:

* Name des Reiseziels
* Land
* Stadt
* Beschreibung
* Reisestatus

Bereits gespeicherte Reiseziele können anschließend aktualisiert oder gelöscht werden.

### Reisestatus

Reiseziele können verschiedenen Status zugeordnet werden, zum Beispiel:

* Wishlist
* Planning
* Visited

Die verfügbaren Status werden in der Datenbank gespeichert und dynamisch von der Anwendung geladen.

### Suche

Auf der Seite **My Destinations** steht eine Suchfunktion zur Verfügung.

Gespeicherte Reiseziele können durchsucht werden nach:

* Name des Reiseziels
* Stadt
* Land

### Bildverwaltung

Für jedes Reiseziel kann in der aktuellen Version ein Bild gespeichert werden.

Die Anwendung unterstützt:

* Bild-Upload
* Ersetzen vorhandener Bilder
* Löschen von Bildern
* Validierung der Dateigröße
* MIME-Type-Validierung
* zufällig generierte Dateinamen
* Löschen physischer Dateien beim Entfernen eines Bildes oder Reiseziels

Die Begrenzung auf ein Bild pro Reiseziel ist für die aktuelle Portfolio-Version bewusst gewählt.

## Responsives Design

Die Benutzeroberfläche wurde mit **Tailwind CSS** nach dem Mobile-First-Prinzip gestaltet.

Das Design umfasst:

* eigene `travel`-Farbpalette
* Nunito-Schriftart
* responsive Grid-Layouts
* responsive Formulare
* Karten für Reiseziele
* Hover- und Focus-Effekte
* Platzhalter für fehlende Bilder
* mobile Burger-Navigation
* gestaltete Erfolgs- und Fehlermeldungen

Die Anwendung ist sowohl für Desktop- als auch für Mobilgeräte optimiert.

## Datenbank

Die Anwendung verwendet eine relationale MySQL-/MariaDB-Datenbank.

Die wichtigsten Tabellen sind:

* `tbl_user`
* `tbl_country`
* `tbl_city`
* `tbl_status`
* `tbl_destination`
* `tbl_image`

Eine bereinigte Datenbankstruktur befindet sich in:

```text
database.sql
```

Diese Datei enthält die benötigte Tabellenstruktur und die grundlegenden Statusdaten, jedoch keine persönlichen Benutzerdaten oder Entwicklungs-Testkonten.

## Lokale Installation

### Voraussetzungen

* PHP
* Apache oder ein anderer PHP-kompatibler Webserver
* MySQL oder MariaDB
* Node.js und npm
* Tailwind CSS CLI

### 1. Repository klonen

```bash
git clone https://github.com/hellaszabo94-prog/travel_bucket_list_manager.git
```

### 2. Datenbank importieren

Eine MySQL-/MariaDB-Datenbank erstellen und anschließend folgende Datei importieren:

```text
database.sql
```

### 3. Datenbankverbindung konfigurieren

Folgende Datei erstellen:

```text
includes/config.inc.php
```

Dafür kann die Struktur der Beispiel-Konfigurationsdatei verwendet werden.

Anschließend die eigenen Daten eintragen:

* Datenbank-Hostname
* Datenbank-Benutzername
* Datenbank-Passwort
* Datenbankname

Die tatsächliche Konfigurationsdatei wird von Git ausgeschlossen, damit keine Zugangsdaten veröffentlicht werden.

### 4. Frontend-Abhängigkeiten installieren

```bash
npm install
```

### 5. Tailwind-Entwicklungsmodus starten

```bash
npm run dev
```

Tailwind überwacht anschließend die Projektdateien und generiert die CSS-Datei bei Änderungen automatisch neu.

### 6. Anwendung starten

Apache und MySQL/MariaDB starten und die Anwendung über den lokalen Webserver öffnen.

Zum Beispiel mit XAMPP:

```text
http://localhost/travel_bucket_list_manager/
```

## Live Demo

Eine bereitgestellte Version der Anwendung ist hier verfügbar:

**https://travelmanager.infinityfreeapp.com**

Die Live-Version verwendet eine separate Datenbankkonfiguration für die Produktionsumgebung.

## Portfolio-Umfang

Dieses Projekt wurde als Lern- und Portfolio-Projekt und nicht als produktive Reiseplattform entwickelt.

Der Schwerpunkt der aktuellen Version liegt auf:

* PHP-Backend-Entwicklung
* MySQL-Datenbankoperationen
* relationalem Datenbankdesign
* Benutzerauthentifizierung
* Session-Management
* CRUD-ähnlichen Operationen
* Suchfunktion
* Datei-Uploads
* Dateivalidierung
* responsiver Frontend-Entwicklung
* Tailwind CSS
* Deployment einer PHP-/MySQL-Anwendung

Einige Funktionen wurden bewusst vereinfacht. Beispielsweise unterstützt jedes Reiseziel aktuell ein hochgeladenes Bild.

## Projektstatus

**Die Kernfunktionen sind fertiggestellt und das Projekt ist als Live-Portfolio-Anwendung veröffentlicht.**
