<?php
session_start();
require_once 'login.php';
echo<<<_HEAD1
<html>
<body>
<head>
   <link rel="stylesheet" type="text/css" href="style.css" />
   <title>Landing Page</title>
   
</head>   
_HEAD1;

// Establishing connection to the database using PHP data Objects
try {
   $dsn = "mysql:host=$hostname;dbname=$database";
   $pdo = new PDO($dsn, $username, $password);
   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Setting errormode
   
   // Querying the database
   $query = "SELECT * FROM Manufacturers";
   $statement = $pdo->query($query);
   $rows = $statement->rowCount();
   
   $mask = 0;
   for ($j = 0; $j < $rows; ++$j) {
      $mask = (2 * $mask) + 1;
   }
   
   $_SESSION['supmask'] = $mask;
   
   $pdo = null; // Closing the PDO connection
} catch (PDOException $e) {
   die("Unable to connect to database: " . $e->getMessage());
}

  // Javascript code to validate the form input given by user and HTML form to accept the user input
   echo <<<_EOP
<script>
   function validate(form) {
   fail = ""
   if(form.fn.value =="") fail = "Must Give Forname "
   if(form.sn.value == "") fail += "Must Give Surname"
   if(fail =="") return true
       else {alert(fail); return false}
   }
</script>


<div class="form">
<form action="indexp.php" method="post" onSubmit="return validate(this)">
   <pre>
      <label for="fn">First Name:</label>
      <input type="text" name="fn" id="fn" />
      
      <label for="sn">Second Name:</label>
      <input type="text" name="sn" id="sn" />
      
      <input type="submit" value="Go" />
   </pre>
</form>
</div>
_EOP;

echo <<<_TAIL1
</pre>
</body>
</html>
_TAIL1;

?>
