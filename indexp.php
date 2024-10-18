<?php
session_start();

// Checking if the forename and surname has been sumitted through post method
if(isset($_POST['fn']) &&
   isset($_POST['sn']))
  {
  // If the condition is satisfied the user is prompeted further to the website
    echo<<<_HEAD1
    <html>
    <head>
    _HEAD1;
    
    include 'menuf.php';
    $_SESSION['forname'] = $_POST['fn'];
    $_SESSION['surname'] = $_POST['sn'];
    $smask =  $_SESSION['supmask'];
     
    echo <<<_HEAD2
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Small Molecule Database</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                color: black;
                background-color: white;
                margin: 0;
                padding: 0;
            }
            .header {
                background: #f8f9fa;
                border-bottom: 1px solid #e9ecef;
                padding: 20px 0;
            }
            .header h1 {
                text-align: center;
            }
            .content {
                padding: 20px;
            }
            .footer {
                background: #f8f9fa;
                border-top: 1px solid #e9ecef;
                text-align: center;
                padding: 10px 0;
                position: fixed;
                bottom: 0;
                width: 100%;
            }
        </style>
    </head>
    <div class="header">
        <h1>Prototype Small Molecule Database</h1>
    </div>
    <div class="content">
        <h2>Welcome to the Small Molecule Database</h2>
        <p>This database provides comprehensive information on small molecules and their properties. Use our search features to explore the database. The data stored in this database comes from the EDinburgh University Ligand Selection System database. It contains information on small molecules particularly from five manufacturers, Asinex, Maybridge, Nanosyn, Oai4000 and Keyorganics. </p>
    </div>
    <div class="footer">
        &copy; 2024 Small Molecule Database. All rights reserved.
    </div>
</body>
</html>
_HEAD2;
    } // If the condition is not satisfied the user is redirected to the login page
    else { 
  header('./complib.php');
  }
?>
