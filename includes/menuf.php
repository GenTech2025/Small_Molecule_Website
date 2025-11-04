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
        <td><a href="/pages/p1.php">Select Suppliers</a></td>
        <td><a href="/pages/p2.php">Search Compounds</a></td>
        <td><a href="/pages/p3.php">Statistics</a></td>
        <td><a href="/utils/interim_p4.php">Correlation</a></td>
        <td><a href="/utils/props_in.php">Properties</a></td>
        <td><a href="/pages/histogram.php">Histogram</a></td>
        <td><a href="/pages/smiledrawfrontNIH.php">Smiles</a></td>
        <td><a href="/pages/jsmol.php">Animated Smiles</a></td>
        <td><a href="/utils/phelp.php">About this website</a></td>
        <td><a href="/pages/p5.php">Leave the Page</a></td>
        
    </tr>
</table>
_MENU1;
?>
