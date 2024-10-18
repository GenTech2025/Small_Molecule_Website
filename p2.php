<?php
session_start();
// Includes necessary files
require_once 'login.php';
include 'redir.php';

// HTML defined as heredoc string with CSS code inside style tags to better represnt the output of query made
echo <<< _HEAD1
<html>
<head>
  <link rel="stylesheet" href="./style.css">
  <style>
    .centertable {
        width: 100%;
        margin-left: auto;
        margin-right: auto;
    }
    .centertable th,
    .centertable td {
        text-align: center;
    }
    .instruction {
        text-align: center;
    }
  </style>
</head>
<body>
_HEAD1;
include 'menuf.php'; 

// Connecting to database using PDO
try {
    $dsn = "mysql:host=$hostname;dbname=$database";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Querying the database
    $query = "SELECT * FROM Manufacturers";
    $stmt = $pdo->query($query);
    $rows = $stmt->rowCount();

    $smask = $_SESSION['supmask'];
    $firstmn = False;

    $mansel = "(";
    // Fetching the output 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $sid[] = $row['ManuID'];
        $snm[] = $row['name'];
        $sact[] = 0;
        $tvl = 1 << ($row['ManuID'] - 1);

        if ($tvl == ($tvl & $smask)) {
            $sact[] = 1;
            if ($firstmn) $mansel = $mansel." or ";
            $firstmn = True;
            $mansel = $mansel." (ManuID = ".$row['ManuID'].")";
        }
    }

    $mansel = $mansel.")";
    $setpar = isset($_POST['natmax']);
    
    // Information displayed about possible inputs
    echo <<<_MAIN1
    <pre>
    <h2 style="text-align: center"> This is the catalogue retrieval Page </h3>  
    </pre>
    <div class='instruction'>
    <pre>
    Number of Atoms range : 81 - 65
    Number of Carbons range: 37 - 27
    Number of Nitrogen range: 4 - 2
    Number of Oxygens range: 4 - 2
    </pre>
    </div>
    _MAIN1;
    
    // Input form 
    echo <<<_TAIL1
    <div class='SearchCompounds' style="background-color: #363434; color: white; padding: 20px;">
    <form action="p2.php" method="post" style="text-align: center;">
      <table style="margin: auto; color: white; font-family: Arial, sans-serif;">
        <tr>
          <td>Max Atoms</td>
          <td><input type="text" name="natmax" style="margin-bottom: 10px;"/></td>
          <td>Min Atoms</td>
          <td><input type="text" name="natmin" style="margin-bottom: 10px;"/></td>
        </tr>
        <tr>
          <td>Max Carbons</td>
          <td><input type="text" name="ncrmax" style="margin-bottom: 10px;"/></td>
          <td>Min Carbons</td>
          <td><input type="text" name="ncrmin" style="margin-bottom: 10px;"/></td>
        </tr>
        <tr>
          <td>Max Nitrogens</td>
          <td><input type="text" name="nntmax" style="margin-bottom: 10px;"/></td>
          <td>Min Nitrogens</td>
          <td><input type="text" name="nntmin" style="margin-bottom: 10px;"/></td>
        </tr>
        <tr>
          <td>Max Oxygens</td>
          <td><input type="text" name="noxmax" style="margin-bottom: 10px;"/></td>
          <td>Min Oxygens</td>
          <td><input type="text" name="noxmin" style="margin-bottom: 10px;"/></td>
        </tr>
      </table>
      <input type="submit" value="Submit" style="color: black; background-color: white;"/>
    </form>
    </div>
    </body>
    </html>
_TAIL1;

    // Constructing the SQL query dynamically for different conditions based on user input
    if ($setpar) {
        $firstsl = False;
        $compsel = "SELECT * FROM Compounds WHERE (";

        if (($_POST['natmax'] != "") && ($_POST['natmin'] != "")) {
            $compsel = $compsel."(natm > ".$_POST['natmin']." AND natm < ".$_POST['natmax'].")";
            $firstsl = True;
        }

        if (($_POST['ncrmax'] != "") && ($_POST['ncrmin'] != "")) {
            if ($firstsl) $compsel = $compsel." AND ";
            $compsel = $compsel."(ncar > ".$_POST['ncrmin']." AND ncar < ".$_POST['ncrmax'].")";
            $firstsl = True;
        }

        if (($_POST['nntmax'] != "") && ($_POST['nntmin'] != "")) {
            if ($firstsl) $compsel = $compsel." AND ";
            $compsel = $compsel."(nnit > ".$_POST['nntmin']." AND nnit < ".$_POST['nntmax'].")";
            $firstsl = True;
        }

        if (($_POST['noxmax'] != "") && ($_POST['noxmin'] != "")) {
            if ($firstsl) $compsel = $compsel." AND ";
            $compsel = $compsel."(noxy > ".$_POST['noxmin']." AND noxy < ".$_POST['noxmax'].")";
            $firstsl = True;
        }


        if ($firstsl) {
            $compsel = $compsel.") AND ".$mansel;
            //echo $compsel; // Printing out the SQL query statement
            echo "\n";

            $stmt = $pdo->query($compsel);
            $rows = $stmt->rowCount();
            // Check if the number of records retrieved by the query is too large
            if ($rows > 100) {
    echo "Too many results ", $rows, " Max is 100\n";
} else {
    // Showing the query results as a table
    echo '<table border="1" class="centertable">';
    echo '<tr><th>CATN</th><th>Manufacturer ID</th><th>Compound ID</th></tr>';
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" .$row['catn']. "</td>";
        echo "<td>" .$row['ManuID']. "</td>";
        echo "<td>" .$row['id']. "</td>";
        echo "</tr>";
    }
    echo '</table>';
}
        } else {
            echo "No Query Given\n";
        }


    }
} catch (PDOException $e) {
    die("Unable to connect to database: " . $e->getMessage());
}
?>
