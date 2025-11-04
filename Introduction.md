# Introduction to the Small Molecule Database Website

## What is This Project?

This is a web-based application that helps researchers search and analyze chemical compounds (small molecules) from a database. Think of it as a specialized search engine for scientists working in drug discovery - they can filter compounds by manufacturer, chemical properties (like molecular weight), and view statistics about the data.

The project was originally built with older PHP code and has been updated to work with modern PHP 8 standards. It currently runs on a Linux server and connects to a MySQL database containing information about thousands of small molecules from five different manufacturers.

## Technologies Used

### Backend
- **PHP 8**: The main programming language that handles all the web server logic
- **MySQL Database**: Stores all the compound data, manufacturer information, and chemical properties
- **PDO (PHP Data Objects)**: A modern way to connect PHP to databases safely
- **Sessions**: PHP's built-in way to remember user information as they navigate between pages

### Frontend
- **HTML**: The structure of each webpage
- **CSS**: Styling for the pages (mostly inline styles within PHP files)
- **JavaScript**: Basic form validation on the login page

### Data Analysis
- **Python 3**: Used for generating charts and calculating statistics
  - **PyMySQL**: Connects Python to the MySQL database
  - **NumPy**: Handles numerical data
  - **Matplotlib**: Creates histogram charts
  - **SciPy**: Calculates statistical correlations

### Visualization
- **JSmol**: A JavaScript library for viewing 3D molecular structures in the browser
- **NIH Structure Identifier**: External service for drawing 2D molecular structures from SMILES strings

## How the Application Works

### User Flow
1. **Login Page** (`complib.php`): User enters their first and last name
2. **Welcome Page** (`indexp.php`): Brief introduction to the database
3. **Navigation Menu** (`menuf.php`): Appears on all pages, provides links to different features
4. **Feature Pages**: Each page serves a specific purpose

### Key Features Explained

**1. Select Manufacturers** (`p1.php`)
- Lets users choose which chemical suppliers they want to search
- Uses a clever "bitmask" system to remember selections
  - Think of it like checkboxes that get converted to a binary number
  - This number is stored in the user's session and filters results on other pages

**2. Search Compounds** (`p2.php`)
- The main search page where users can filter by:
  - Number of atoms (carbon, nitrogen, oxygen)
  - Molecular properties
- Builds a SQL query based on the filters and shows matching compounds

**3. Statistics** (`p3.php`)
- Calculates average and standard deviation for any property
- Example: "What's the average molecular weight of all compounds?"

**4. Correlation** (`interim_p4.php` → calls `correlate3.py`)
- Uses Python to determine if two properties are related
- Example: "Is there a relationship between molecular weight and polar surface area?"

**5. Properties** (`props_in.php` → `props_out.php`)
- Find compounds within a specific range for a property
- Example: "Show all compounds with molecular weight between 200-400"

**6. Histogram** (`histogram.php` → calls `histog.py`)
- Creates a visual bar chart showing the distribution of a property
- Python generates the image and PHP displays it

**7. SMILES Visualization** (`smiledrawfrontNIH.php`, `jsmol.php`)
- SMILES is a text format for representing molecular structures
- These pages convert SMILES text into 2D or 3D visual representations

## Project Architecture

### Database Structure
```
Manufacturers Table:
- ManuID (unique ID for each manufacturer)
- name (manufacturer name)

Compounds Table:
- CompoundID
- ManuID (links to Manufacturers table)
- Chemical properties: natm, ncar, nnit, noxy, mw, TPSA, XLogP, etc.
- SMILES string (molecular structure representation)
```

### How Pages Communicate

1. **Sessions**: When a user logs in, their name and selected manufacturers are stored in `$_SESSION`
2. **Bitmask Logic**: The manufacturer selection is stored as a single number
   - If manufacturers have IDs 1, 2, 3, 4, 5
   - Selecting manufacturers 1, 3, and 5 creates the bitmask: 10101 (in binary) = 21
   - This number travels with the user and filters all searches
3. **Form Submissions**: Pages use HTML forms with POST method to send data
4. **Python Integration**: PHP executes Python scripts and captures their output

## Current Limitations & Challenges

### Security Concerns
- **Hardcoded credentials**: Database password is in `login.php` (should use environment variables)
- **No real authentication**: Just asks for a name, anyone can access
- **SQL injection risk**: Some queries are built with string concatenation
- **No input validation**: User input isn't thoroughly checked before database queries

### User Experience
- **No responsive design**: May not work well on mobile devices
- **Inline CSS**: Styles are scattered throughout PHP files instead of centralized
- **Limited error handling**: If something breaks, users don't get helpful messages
- **No password protection**: The "login" just asks for a name

### Code Organization
- **Mixed concerns**: HTML, CSS, JavaScript, and PHP are all mixed together
- **Repeated code**: Database connection code appears in multiple files
- **No framework**: Everything is built from scratch

## Suggested Next Steps for Learning & Improvement

### Beginner-Friendly Improvements

1. **Centralize Styling**
   - Move all inline CSS to `style.css`
   - Learn about CSS specificity and organization
   - *Skill to learn*: CSS organization, external stylesheets

2. **Improve Error Messages**
   - Add try-catch blocks with user-friendly error pages
   - Validate form inputs before submitting
   - *Skill to learn*: Input validation, error handling

3. **Create a Database Configuration File**
   - Move database connection code to a single file
   - Use it across all pages (it's already partially done with `login.php`)
   - *Skill to learn*: Code reusability, DRY principle

4. **Add Basic Security**
   - Use environment variables for database credentials
   - Implement prepared statements everywhere (already done in some places)
   - Add password protection for login
   - *Skill to learn*: Web security basics, password hashing

### Intermediate Improvements

5. **Make it Responsive**
   - Use CSS media queries for mobile-friendly layouts
   - Consider a CSS framework like Bootstrap
   - *Skill to learn*: Responsive web design

6. **Separate HTML from PHP**
   - Use template files or a simple templating system
   - Keep business logic separate from presentation
   - *Skill to learn*: MVC pattern, separation of concerns

7. **Add User Accounts**
   - Create a real user authentication system
   - Store user preferences (favorite searches, history)
   - *Skill to learn*: User authentication, session security

8. **AJAX for Better UX**
   - Load search results without page refresh
   - Add live search suggestions
   - *Skill to learn*: JavaScript, AJAX, JSON

### Advanced Improvements

9. **Migrate to a Framework**
   - Consider Laravel (PHP) or Django (Python)
   - Gain structure, security, and built-in features
   - *Skill to learn*: Modern web frameworks

10. **API Development**
    - Create a REST API that returns JSON
    - Separate frontend from backend
    - *Skill to learn*: API design, RESTful principles

11. **Modern Frontend**
    - Rebuild the frontend with React, Vue, or similar
    - Create a single-page application
    - *Skill to learn*: Modern JavaScript frameworks

12. **Enhanced Visualization**
    - Add interactive charts (Chart.js, D3.js)
    - Real-time data updates
    - *Skill to learn*: Data visualization libraries

## Learning Resources

### PHP & MySQL
- PHP.net official documentation
- MySQL official documentation
- "PHP & MySQL: Novice to Ninja" book

### Web Security
- OWASP Top 10 (web security risks)
- "The Web Application Hacker's Handbook"

### Modern Web Development
- MDN Web Docs (HTML, CSS, JavaScript)
- freeCodeCamp.org courses
- The Odin Project

## Getting Started with Development

### Prerequisites
- PHP 8+ installed
- MySQL database server
- Python 3 with required packages (pymysql, numpy, matplotlib, scipy)
- Web server (Apache or use PHP's built-in server)

### Running Locally
```bash
# Start PHP development server
php -S localhost:8000

# Open in browser
# http://localhost:8000/complib.php
```

### Making Your First Change
1. Start with something simple: change the text on the welcome page
2. Edit `indexp.php` line 58 to customize the welcome message
3. Refresh your browser to see the change
4. Gradually work up to more complex modifications

## Conclusion

This project is a great foundation for learning full-stack web development. It touches on:
- Server-side programming (PHP)
- Database design and queries (MySQL)
- Frontend development (HTML/CSS/JavaScript)
- Data analysis and visualization (Python)
- Integration between different technologies

The code is functional but has room for improvement, which makes it perfect for learning. Each enhancement you make will teach you important concepts used in modern web development.

Start small, test frequently, and don't be afraid to break things - that's how you learn!
