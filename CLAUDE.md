# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a PHP-based web application for searching and analyzing small molecule compound data from the Edinburgh University Ligand Selection System database. The database contains information on compounds from five manufacturers: Asinex, Maybridge, Nanosyn, Oai4000, and Keyorganics.

The codebase was migrated from an older PHP version to PHP 8, requiring updates to use PDO (PHP Data Objects) instead of deprecated MySQL functions.

## Database Configuration

Database credentials are stored in `login.php`:
- Host: 127.0.0.1
- Database: s2599932_website
- Tables: `Manufacturers`, `Compounds`

**Security Note**: The login.php file contains hardcoded database credentials. When making changes, never commit actual production credentials to version control.

## Directory Structure

The project is organized into the following directories:

```
/
├── public/              # Entry points & public-facing pages
│   ├── complib.php      # Landing page with login form
│   ├── indexp.php       # Welcome page after authentication
│   └── css/
│       └── style.css    # Main stylesheet
│
├── pages/               # Feature pages
│   ├── p1.php          # Manufacturer selection
│   ├── p2.php          # Compound search
│   ├── p3.php          # Statistics
│   ├── p4.php          # Correlation (placeholder)
│   ├── p5.php          # Leave/logout page
│   ├── histogram.php   # Histogram generation
│   ├── jsmol.php       # 3D molecular visualization
│   ├── smiledrawfrontNIH.php  # SMILES input form
│   └── smiledrawbackNIH.php   # SMILES rendering
│
├── includes/            # Shared components
│   ├── menuf.php       # Navigation menu
│   └── redir.php       # Session validation
│
├── config/              # Configuration
│   └── login.php       # Database credentials
│
├── scripts/             # Python analytics scripts
│   ├── histog.py       # Histogram generation
│   └── correlate3.py   # Correlation calculation
│
├── utils/               # Utility/helper pages
│   ├── interim_p4.php  # Correlation interface
│   ├── props_in.php    # Property input form
│   ├── props_out.php   # Property results
│   ├── phelp.php       # About/help page
│   └── pdeb.php        # Debug page
│
└── jsmol/              # JSmol library (external)
    └── [JSmol files]
```

## Architecture

### Session Management
The application uses PHP sessions to track:
- User identity (forename, surname)
- `supmask`: A bitmask representing selected manufacturers (stored in `$_SESSION['supmask']`)

### Page Flow
1. `public/complib.php` - Landing page with name entry form
2. `public/indexp.php` - Welcome page after login validation
3. Navigation menu (`includes/menuf.php`) provides access to all features

### Core Features & Files

**Manufacturer Selection (`pages/p1.php`)**
- Uses bitmask logic to track selected manufacturers
- Bitmask formula: Each manufacturer ID maps to bit position `1 << (ManuID - 1)`
- Session stores active selections across pages

**Compound Search (`pages/p2.php`)**
- Multi-parameter search (atoms, carbons, nitrogens, oxygens)
- Builds dynamic SQL queries based on user input and manufacturer mask
- Query structure: combines manufacturer filter with compound property ranges

**Statistics (`pages/p3.php`)**
- Calculates AVG() and STD() for compound properties
- Properties: natm, ncar, nnit, noxy, nsul, ncycl, nhdon, nhacc, nrotb, mw, TPSA, XLogP

**Python Analytics**
- `scripts/histog.py`: Generates histogram images using matplotlib
  - Usage: `./scripts/histog.py <column> <name> <where_clause>`
  - Returns PNG image to stdout
  - Python interpreter: `/localdisk/anaconda3/bin/python`

- `scripts/correlate3.py`: Calculates Pearson correlation between two columns
  - Usage: `./scripts/correlate3.py <col1> <col2> <selection>`
  - Returns correlation coefficient

Both Python scripts connect to the same MySQL database using PyMySQL.

**SMILES & Visualization**
- `pages/smiledrawfrontNIH.php` / `pages/smiledrawbackNIH.php`: SMILES structure drawing
- `pages/jsmol.php`: 3D molecular visualization using JSmol library
- JSmol library located in `/jsmol` directory (extensive JavaScript molecular viewer)

### Shared Components

**includes/menuf.php** - Navigation menu with links to all features:
- Uses root-relative paths (e.g., `/pages/p1.php`, `/utils/phelp.php`)
- Links to: Select Suppliers, Search Compounds, Statistics, Correlation, Properties, Histogram, Smiles, Animated Smiles, About, Leave

**includes/redir.php** - Session validation (redirects to `/public/complib.php` if session invalid)

**config/login.php** - Database configuration (credentials for MySQL connection)

## Development Commands

### Running the Application
This is a PHP web application designed to run on a web server with MySQL:
```bash
# Requires PHP 8+ with PDO MySQL extension
# Run from the project root directory
php -S localhost:8000  # Development server

# Access the application at:
# http://localhost:8000/public/complib.php
```

**Note**: The application expects to be served from the project root, with navigation using root-relative paths (e.g., `/pages/p1.php`).

### Testing Python Scripts
```bash
# Test histogram generation
./scripts/histog.py mw "Molecular Weight" "ManuID=1"

# Test correlation
./scripts/correlate3.py mw TPSA "ManuID=1"
```

### Database Access
Connect using credentials from `config/login.php`:
```bash
mysql -h 127.0.0.1 -u s2599932 -p s2599932_website
```

## Code Patterns

### PDO Connection Pattern
All PHP files use this pattern:
```php
$dsn = "mysql:host=$hostname;dbname=$database";
$pdo = new PDO($dsn, $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
```

### Session Validation
Most pages include:
```php
session_start();
include '../includes/redir.php';  // Validates session (path varies by location)
```

### File Includes
Files use relative paths based on their location:
- From `public/`: `require_once '../config/login.php'`, `include '../includes/menuf.php'`
- From `pages/`: `require_once '../config/login.php'`, `include '../includes/redir.php'`, `include '../includes/menuf.php'`
- From `utils/`: Same as `pages/`

### HTML Output
Pages use heredoc syntax for HTML blocks:
```php
echo <<<_HEAD1
<html>
...
_HEAD1;
```

## Important Notes

- All pages use inline CSS (style tags within heredoc blocks) rather than relying solely on external stylesheets
- The manufacturer selection uses bitwise operations - be careful when modifying this logic
- Python scripts expect specific Anaconda Python path - adjust shebang if deploying elsewhere
- Database queries are constructed dynamically - ensure proper validation to prevent SQL injection when adding features
