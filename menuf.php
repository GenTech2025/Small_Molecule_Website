<?php
echo <<<_MENU1
<style>
    
    .navigationmenu {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    
    .navigationmenu td {
        background-color: #363434;
        padding: 10px;
        text-align: center;
    }

    
    .navigationmenu a {
        color: white;
        text-decoration: none;
    }
</style>

<table class="navigationmenu">
    <tr>
        <td><a href="./p1.php">Select Suppliers</a></td>
        <td><a href="./p2.php">Search Compounds</a></td>
        <td><a href="./p3.php">Statistics</a></td>
        <td><a href="./interim_p4.php">Correlation</a></td>
        <td><a href="./props_in.php">Properties</a></td>
        <td><a href="./histogram.php">Histogram</a></td>
        <td><a href="./smiledrawfrontNIH.php">Smiles</a></td>
        <td><a href="./jsmol.php">Animated Smiles</a></td>
        <td><a href="./phelp.php">About this website</a></td>
        <td><a href="./p5.php">Leave the Page</a></td>
        
    </tr>
</table>
_MENU1;
?>
