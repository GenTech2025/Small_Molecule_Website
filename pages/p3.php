<?php
session_start();
// Including necessary files
include '../includes/redir.php';
require_once '../config/login.php';
echo<<<_HEAD1
<html>
<body>
_HEAD1;
// Including the navigation menu
include '../includes/menuf.php';
$dbfs = array("natm","ncar","nnit","noxy","nsul","ncycl","nhdon","nhacc","nrotb","mw","TPSA","XLogP");
$nms = array("n atoms","n carbons","n nitrogens","n oxygens","n sulphurs","n cycles","n H donors","n H acceptors","n rot bonds","mol wt","TPSA","XLogP");
echo <<<_MAIN1
    <pre>
    <h3>This is the Statistics Page</h3>
    </pre>
_MAIN1;

// Checking user input for the parameter whose statistics needs to be calculated
if(isset($_POST['tgval'])) 
   {
     $chosen = 0;
     $tgval = $_POST['tgval'];
     for($j = 0 ; $j <sizeof($dbfs) ; ++$j) {
       if(strcmp($dbfs[$j],$tgval) == 0) $chosen = $j; 
     } 
     printf(" Statistics for %s (%s)<br />\n",$dbfs[$chosen],$nms[$chosen]);
 
     
     $dsn = "mysql:host=$hostname;dbname=$database";
     $pdo = new PDO($dsn, $username, $password);
     
     // Queying the database and calculating the statistics and standard deviation
     $query = "SELECT AVG($tgval), STD($tgval) FROM Compounds";
     $statement = $pdo->prepare($query);
     $statement->execute();
     
     // Fetching the information from the query and printing the results on the webpage
     $row = $statement->fetch(PDO::FETCH_NUM);
     printf(" Average %f  Standard Dev %f <br />\n",$row[0],$row[1]);
   }
   
   // Form for user input
echo '<form action="p3.php" method="post"><pre>';
for($j = 0 ; $j <sizeof($dbfs) ; ++$j) {
  if($j == 0) {
     printf(' %15s <input type="radio" name="tgval" value="%s" checked"/>',$nms[$j],$dbfs[$j]);
  } else {
     printf(' %15s <input type="radio" name="tgval" value="%s"/>',$nms[$j],$dbfs[$j]);
  }
  echo "\n";
} 
echo '<input type="submit" value="OK" />';
echo '</pre></form>';

echo <<<_TAIL1
</body>
</html>
_TAIL1;

?>

