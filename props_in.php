<?php
session_start();
include 'redir.php';
require_once 'login.php';
echo <<<_HEAD1
<html>
<body>
_HEAD1;
include 'menuf.php';
echo <<<_MAIN1
  <h3> This is the propeties Page </h3>
  <div style="text-align: center;"> 
  <pre>
  Molecular Weight (MW) range                 : 104 - 744
  Topological Polar Surface Area (TPSA) range : 25 - 210
  XlogP range                                 :  -4 - 9
  </pre>
  </div>
    </pre><form action="props_out.php" method="post">
  <pre>
   MW <input type="radio" name="tgval" value="mw" checked/>
 TPSA <input type="radio" name="tgval" value="TPSA"/>
XlogP <input type="radio" name="tgval" value="XlogP"/>
Value <input type="text" name="cval"/>

<input type="submit" value="Submit my request" />
</pre></form>
</body>
</html>
_MAIN1;
?>