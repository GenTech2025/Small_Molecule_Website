<?php
session_start();
require_once 'login.php';
echo<<<_HEAD1
<html>
<body>
_HEAD1;
include 'menuf.php';
echo<<<_BODY1
<h1> About this website</h1>
<h2> Motivation </h2>
<p> This website is an webinterface to the prototype small molecule database which can help researchers in finding compounds with desired chemical composition, properties and compounds manufactured by particular manufacturers. The aim of this database is to accelerate the preliminary research in drug discovery mostly in the screening stage.
</p>
<h2> Functionalities of the Webpage </h2>
<pre>
This website offers different functionality mainly with respect to searching compounds. The website is divided into several webpages, each of which serve a different functionality as mentioned below:
<h3> Select Manufacturers </h3>
This page lets the user select the manufacturer or manufacturers which the user wants to find compounds from, by default all the manufacturers are selected.
<h3> Select Compounds </h3>
This page lets users search for compounds with particular chemical composition and the output is displayed as a text.
<h3> Statistics </h3>
This page lets the user claculate the statistics for parameter that they have chosen. It is worth noting that it calculates the statistics based on the entire data stored.
<h3> Correlation </h3>
This page enables the calculation of pearson correlation and p-value of the parameters selected for over the entire record of the database.
<h3> Properties </h3>
This page allows the user to retrive list of compounds within the range of value specified for a selected parameter.
<h3> Histogram </h3>
This page aims to draw histogram of the statistics of the parameter selected.
<h3> Smiles </h3>
This page allows users to generate a 2D smiles image by pasting a smiles string in the search box
</body>
</html>
_BODY1;
?>
