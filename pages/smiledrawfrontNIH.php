<?php
session_start();
include '../includes/menuf.php';
require_once '../config/login.php';
echo <<<_HEAD
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>This is smile_draw_NIH.php</title>
    <script type="text/javascript">
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta http-equiv="refresh" content="0; url=./smiledrawbackNIH.php">;
    </script>
    <script type="text/javascript">
        function validate(form) {
            fail = validatefield(form.smile.value)
            if (fail == "") {
                return true;
            } else {
                alert(fail); 
                return false;
            }
        }
        
        function validatefield(field) {
            if (field == "") {
                return "No smile string entered ";
            } else {
                return "";
            }
        }
    </script>
</head>
_HEAD;

echo <<<_EOF
<body>
<div style="padding-left:100px;">
    <h3>Please Enter a SMILES STRING</h3>
    <br/> <font style="font-size: 20px;">An example for you: [H]OC2=C([H])C([H])=C([H])C([H])=C2(C([H])=NN([H])C(=O)C1=C([H])C([H])=NC([H])=C1([H]))</font>
    <form action="smiledrawbackNIH.php" method="post" onSubmit="return validate(this)">
        <p>Smile string <input type="text" size="100" name="smile" /> </p>
        <p><input type="Submit" value="Retrieve NIH structure!" /></p>
    </form>
</div>
_EOF;

echo '<h3> Dont Know What SMILES to put in the form? </h3>';
echo '<p> Try searching the list below using Ctrl + F and the Compound ID of the SMILES you want <p>'; 

try {
    $dsn = "mysql:host=$hostname;dbname=$database";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Querying the database
    $query = "SELECT * FROM Smiles";
    $stmt = $pdo->query($query);
    // Displaying the output
    echo '<table border="1" class="centertable">';
    echo '<tr><th>ID</th><th>Compound ID</th><th>SMILES</th></tr>';
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" .$row['id']. "</td>";
        echo "<td>" .$row['cid']. "</td>";
        echo "<td>" .$row['smiles']. "</td>";
        echo "</tr>";
    }
    echo '</table>';
    } catch (PDOException $e) {
    die("Unable to connect to database: " . $e->getMessage());
}

echo <<<_TAIL
</body>
</html>
_TAIL;
session_destroy();
?>
