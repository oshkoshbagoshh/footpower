# Footpower 2025 Redux
This project is a basic minimal CRUD (Create, Read, Update, Delete) app built with:

- **PHP** (server-side, for processing requests and interacting with the database)
- **jQuery** (client-side, for AJAX requests and DOM manipulation)
- **jQuery UI** (for UI elements/modal dialogs/controls)

**No frontend build system or dependencies are required outside of jQuery and jQuery UI.**

## Features

- Simple data management via a web interface
- AJAX-driven operations (no page reloads)
- Clean separation: PHP handles backend; HTML+JS handles frontend

## How it works

1. The client-side UI uses jQuery and jQuery UI to display and manage data.
2. User actions (create, read, update, delete) trigger AJAX POST requests to PHP handler scripts.
3. Server-side PHP receives these requests, interacts with MySQL, and responds with JSON (status, data, error messages).
4. The frontend updates the UI live based on the AJAX responses.

## Quick Start

1. Place the project files on a PHP-capable server with MySQL.
2. Edit your database connection settings in the PHP files as needed.
3. Open `index.html` (or the main client entry file) in your browser.

## Directory structure

```
public/
  index.html         // Demo UI (calls AJAX to handlers)
  handlers/
    data_handler.php // PHP endpoint for data CRUD
src/
  classes/
    Data.php         // PHP class for CRUD logic
```

## Requirements

- PHP 7+
- MySQL
- jQuery (included via CDN or downloaded)
- jQuery UI (included via CDN or downloaded)

## Notes

- All communication is via AJAX POST.
- Data validation & error reporting handled PHP-side.
- Designed to be easy to follow and extend.
- No external frameworks/dependencies beyond jQuery/jQuery UI.
- Security basics (simple token system) in place, but not production-hardened.

---


