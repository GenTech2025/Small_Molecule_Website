<?php
session_start();
// Includes the necessary files
include 'redir.php';
require_once 'login.php';

echo <<< _HEAD1
<html>
<body>
_HEAD1;
// Includes the navigation menu
include 'menuf.php';

 
$dbfs = array("natm", "ncar", "nnit", "noxy", "nsul", "ncycl", "nhdon", "nhacc", "nrotb", "mw", "TPSA", "XLogP");
$nms = array("n atoms", "n carbons", "n nitrogens", "n oxygens", "n sulphurs", "n cycles", "n H donors", "n H acceptors", "n rot bonds", "mol wt", "TPSA", "XLogP");

echo <<< _MAIN1
<pre>
<h3> This is the updated correlation Page </h3>
</pre>
_MAIN1;

// Checks if both the radio buttons are selected
if (isset($_POST['tgval']) && isset($_POST['tgvalb'])) {
    $chosen = 0;
    $chosenb = 0;
    $tgval = $_POST['tgval'];
    $tgvalb = $_POST['tgvalb'];
    
    
    foreach ($dbfs as $index => $value) {
        if ($value === $tgval) {
            $chosen = $index;
        }
        if ($value === $tgvalb) {
            $chosenb = $index;
        }
    }

    // Connecting to database using PDO
    try {
        $dsn = "mysql:host=$hostname;dbname=$database";
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Unable to connect to database: " . $e->getMessage());
    }

    
    $query = "SELECT * FROM Manufacturers";
    $stmt = $pdo->query($query);

    $smask = $_SESSION['supmask'];
    $firstmn = False;
    
    // Constructing the query
    $mansel = "(";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $sid = $row['ManuID'];
        $snm = $row['name'];
        $sact = 0;
        $tvl = 1 << ($sid - 1);
        if ($tvl == ($tvl & $smask)) {
            $sact = 1;
            if ($firstmn) $mansel .= " or ";
            $firstmn = True;
            $mansel .= " (ManuID = $sid)";
        }
    }
    $mansel .= ")";
    
    // Executing the python script which calculates the correlation
    $comtodo = "python ./correlate3.py {$dbfs[$chosen]} {$dbfs[$chosenb]} \"$mansel\"";
      echo "Correlation for {$nms[$chosen]} ({$dbfs[$chosen]}) vs {$nms[$chosenb]} ({$dbfs[$chosenb]})\n";
      $rescor = system($comtodo);
      echo "\n";
  }

    // Form for selecting correlation parameters
    echo '<form action="interim_p4.php" method="post"><pre>';
    foreach ($dbfs as $index => $field) {
        $checked = $index === 0 ? 'checked' : '';
        printf('%15s <input type="radio" name="tgval" value="%s" %s/> %15s <input type="radio" name="tgvalb" value="%s" %s/>', $nms[$index], $field, $checked, $nms[$index], $field, $checked);
        echo "\n";
    }
    echo '<input type="submit" value="OK" />';
    echo '</pre></form>';

echo <<< _TAIL1
</body>
</html>
_TAIL1;
?>
