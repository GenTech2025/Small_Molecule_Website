<?php
if(!(isset($_SESSION['forname']) &&
     isset($_SESSION['surname'])))
  {
  header('location: https://bioinfmsc8.bio.ed.ac.uk/~s2599932/complib.php');
  }
?>
