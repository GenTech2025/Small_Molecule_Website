<?php
session_start();
require_once '../config/login.php';
include '../includes/redir.php';

echo <<<_HEAD1
<html>
<body>
_HEAD1;

include '../includes/menuf.php';

// Refactored PDO connection setup
try {
    $pdo = new PDO('mysql:host=' . $hostname . ';dbname=' . $database, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Unable to connect to database: " . $e->getMessage());
}

// Refactored query execution using PDO
$query = "SELECT * FROM Manufacturers";
$manarray = array();
foreach ($pdo->query($query) as $row) {
    $manarray[] = $row['name'];
}

echo <<<_MAIN1

<h3>This is the final property retrieval page</h3>

_MAIN1;

if (!empty($_POST['tgval']) && !empty($_POST['cval'])) {
    $mychoice = $_POST['tgval'];
    $myvalue = $_POST['cval'];
    $compsel = "SELECT * FROM Compounds WHERE ";
    $bindings = [];

    if ($mychoice == "mw") {
        $compsel .= "(mw > ? AND mw < ?)";
        $bindings = [($myvalue - 1.0), ($myvalue + 1.0)];
    } elseif ($mychoice == "TPSA") {
        $compsel .= "(TPSA > ? AND TPSA < ?)";
        $bindings = [($myvalue - 0.1), ($myvalue + 0.1)];
    } elseif ($mychoice == "XlogP") {
        $compsel .= "(XlogP > ? AND XlogP < ?)";
        $bindings = [($myvalue - 0.1), ($myvalue + 0.1)];
    }

    $stmt = $pdo->prepare($compsel);
    $stmt->execute($bindings);
    $rows = $stmt->rowCount();

    if ($rows > 10000) {
        echo "Too many results ", $rows, " Max is 10000\n";
    } else {
        echo <<<TABLESET_
<table border="1">
  <tr>
    <td>CAT Number</td>
    <td>Manufacturer</td>
    <td>Property</td>
  </tr>
TABLESET_;

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            printf("<td>%s</td> <td>%s</td>", $row['catn'], $manarray[$row['ManuID'] - 1]);
            if ($mychoice == "mw") {
                printf("<td>%s</td> ", $row['mw']);
            } elseif ($mychoice == "TPSA") {
                printf("<td>%s</td> ", $row['TPSA']);
            } elseif ($mychoice == "XlogP") {
                printf("<td>%s</td> ", $row['XlogP']);
            }
            echo "</tr>";
        }
        echo "</table>";
    }
} else {
    echo "No Query Given\n";
}
echo "</pre>"; 
echo <<<_TAIL1
</body>
</html>
_TAIL1;
?>
