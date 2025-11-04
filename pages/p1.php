<?php
session_start();
// Includes necessary files
require_once '../config/login.php';
include '../includes/redir.php';
echo<<<_HEAD1
<html>
<body>
_HEAD1;
// Includes the navigational menu
include '../includes/menuf.php';

// Connecting using PDO
try {
    $dsn = "mysql:host=$hostname;dbname=$database";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
    $query = "SELECT * FROM Manufacturers";
    $stmt = $pdo->query($query);
    $rows = $stmt->rowCount();
  
    $smask = $_SESSION['supmask'];
    $sact = array();
    $sid = array();
    $snm = array();
  
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $sid[] = $row['ManuID'];
        $snm[] = $row['name'];
        $sact[] = 0;
        $tvl = 1 << ($row['ManuID'] - 1);
        if ($tvl == ($tvl & $smask)) {
            $sact[count($sact) - 1] = 1;
        }
    }
  
    if (isset($_POST['supplier'])) {
        $supplier = $_POST['supplier'];
        $nele = sizeof($supplier);
        for ($k = 0; $k < $rows; ++$k) {
            $sact[$k] = 0;
            for ($j = 0; $j < $nele; ++$j) {
                if (strcmp($supplier[$j], $snm[$k]) == 0) {
                    $sact[$k] = 1;
                }
            }
        }
        $smask = 0;
        for ($j = 0; $j < $rows; ++$j) {
            if ($sact[$j] == 1) {
                $smask = $smask + (1 << ($sid[$j] - 1));
            }
        }
        $_SESSION['supmask'] = $smask;
    }
    
    // Internal CSS enclosed in style tags to align the div elements  
    echo <<<_CSS
    <style>
    .manutable {
        display: flex;
        justify-content: center;
        gap: 20px;
    }
    .manui {
        display: inline-block;
        text-align: left;
        margin-top: 50px;
        padding: 10px;
        border: 1px solid;
        flex-grow: 1; 
        width: calc(50% - 10px);
    }
    </style>
    _CSS;

    echo '<div class="manutable">';

    // Input Table with in line CSS for better aesthetics
    echo '<div class="manui" style="background-color: black; color: white;">';
    echo '<form action="p1.php" method="post">';
    echo '<table border="1" style="border-collapse: collapse; margin: auto; color: white; width: 100%;">';
    for ($j = 0; $j < $rows; ++$j) {
        echo '<tr style="background-color: black; color: white;">';
        echo '<td style="padding: 5px;">';
        echo $snm[$j];
        echo '</td><td style="padding: 5px;"> <input type="checkbox" name="supplier[]" value="';
        echo $snm[$j];
        echo '" style="accent-color: white; background-color: black; color: white;"/></td></tr>';
    }
    echo '</table>';
    echo '<div style="text-align: center;"><input type="submit" value="Submit" style="margin-top: 20px; background-color: white; color: black;"/></div>';
    echo '</form>';
    echo '</div>';

    // Shows currently selected suppliers with added inline CSS
    echo '<div class="manui" style="background-color: black; color: white;">';
    echo '<h3>Currently selected Suppliers:</h3>';
    for ($j = 0; $j < $rows; ++$j) {
        if ($sact[$j] == 1) {
            echo '<div style="background-color: white; color: red; padding: 5px; margin-bottom: 5px;">';
            echo $snm[$j];
            echo '</div>';
        }
    }
    echo '</div>';
    echo '</div>';

    echo <<<_TAIL1
    </form>
    </body>
    </html>
    _TAIL1;
} catch (PDOException $e) {
    die("Unable to connect to database: " . $e->getMessage());
}
?>
