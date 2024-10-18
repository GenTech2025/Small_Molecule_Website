<?php
session_start();
include 'redir.php';
require_once 'login.php';

// Heredoc string HTML
echo<<<_HEAD1
<html>
<body>
_HEAD1;

include 'menuf.php'; // Only contains HTML code so it will be shown on top of the page

$dbfs = array("natm", "ncar", "nnit", "noxy", "nsul", "ncycl", "nhdon", "nhacc", "nrotb", "mw", "TPSA", "XLogP");
$nms = array("n atoms", "n carbons", "n nitrogens", "n oxygens", "n sulphurs", "n cycles", "n H donors", "n H acceptors", "n rot bonds", "mol wt", "TPSA", "XLogP");

// Heredoc string HTML
echo <<<_MAIN1

<h3> This is the histogram page </h3>
_MAIN1;

// Checking if super global post variable tgval has been set or clicked 
if(isset($_POST['tgval'])) {
    $chosen = 0;
    $tgval = $_POST['tgval']; // assigning the value of post variable to a local variable

    for ($j = 0; $j < sizeof($dbfs); ++$j) {
        if (strcmp($dbfs[$j], $tgval) == 0) $chosen = $j; // strcmp function returns 0 if the two strings are equal
    }

    // PDO Database Connection
    try {
        $dsn = "mysql:host=$hostname;dbname=$database";
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Unable to connect to database: " . $e->getMessage());
     }

    // Query Execution
    $query = "SELECT * FROM Manufacturers";
    $stmt = $pdo->query($query);
    
    $rows = [];
    while ($row = $stmt->fetch()) {
        $rows[] = $row;
    }

    $smask = $_SESSION['supmask'];
    $firstmn = false;
    $mansel = "(";                  // Initializing the dynamic SQL query

    foreach ($rows as $j => $row) {
        $sid[$j] = $row['ManuID'];
        $snm[$j] = $row['name'];
        $sact[$j] = 0;
        $tvl = 1 << ($sid[$j] - 1);
        if ($tvl == ($tvl & $smask)) {
            $sact[$j] = 1;
            if ($firstmn) $mansel .= " OR ";
            $firstmn = true;
            $mansel .= " (ManuID = ".$sid[$j].")";
        }
    }
    $mansel .= ")";
    
    // Prepare command to run external program
    $comtodo = "/localdisk/home/s2599932/public_html/histog.py" . $dbfs[$chosen] . " \"" . $nms[$chosen] . "\" \"" . $mansel . "\"";
    // Run command and capture output. Check if output is null before encoding
    $outputRaw = system($comtodo);
    
    if ($outputRaw !== null) {
        $output = base64_encode($outputRaw);
        echo <<<_IMGPUT
        <pre>
        <img src="data:image/png;base64,$output" />
        </pre>
    _IMGPUT;
    } else {
        echo "<p>Failed to generate histogram. Please check the command or server configuration.</p>";
    }

}

echo '<form action="histogram.php" method="post"><pre>';
for ($j = 0; $j < sizeof($dbfs); ++$j) {
    printf(' %15s <input type="radio" name="tgval" value="%s" %s/>', $nms[$j], $dbfs[$j], $j == 0 ? 'checked' : '');
    echo "\n";
}
echo '<input type="submit" value="OK" />';
echo '</pre></form>';
echo <<<_TAIL1
</body>
</html>
_TAIL1;
?>
