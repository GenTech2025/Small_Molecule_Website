<?php
session_start();
include '../includes/redir.php';
echo<<<_HEAD1
<html>
<body>
_HEAD1;
include '../includes/menuf.php';
echo <<<_MAIN1
    <pre>
This is the Correlation Page  (not Complete)
    </pre>
_MAIN1;
echo <<<_TAIL1
</body>
</html>
_TAIL1;

?>
