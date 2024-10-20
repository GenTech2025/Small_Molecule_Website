<?php
session_start();
include 'menuf.php';
echo <<<_HEAD
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>This is draw_my_smile_NIH.php</title>
    <script type="text/javascript">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </script>
</head>
_HEAD;

echo <<<_EOF
<body>
    <div style="padding-left:100px;">
_EOF;

if (isset($_POST['smile'])) {
    $smile = $_POST['smile'];
    $bigsmile = iconv_strlen($smile, 'utf8');
    echo "<h5>GIF for <br/><font style=\"font-size: 20px;\">$smile<br/>($bigsmile characters)</font></h5> </br>";
    
    echo '<h5>Learn More about this compound by searching the smiles string in Pubchem</h5></br>';
    
    echo '<a href="https://pubchem.ncbi.nlm.nih.gov/">Go to Pubchem</a> </br>';

    $convurl = 'https://cactus.nci.nih.gov/chemical/structure/' . rawurlencode($smile) . '/image';
    $convstr = base64_encode(file_get_contents($convurl));
    echo "<img src=\"data:image/gif;base64,$convstr\"></img>";
    unset($_POST['smile']);
}

echo <<<_TAIL
    </div>
</body>
</html>
_TAIL;
session_destroy();
?>
