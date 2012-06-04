<HTML>
    <body>
<FORM ACTION="checkbox.php" METHOD="POST">

<b>sexo:</b><br>
<table>
    <tr><td><input type=checkbox name=mas >masculino</td>
        <tr><td><input type=checkbox name=fem >femenino<td/></tr>
        <tr><td><input type=checkbox name=neutro >neutro<br></td></tr>
        <tr><td><INPUT TYPE=submit NAME=OK VALUE="evento1"></td></tr>
<?php

$OK = "";
$fem = "off";
$mas = "off";
$neutro = "off";

$_POST["OK"];

if ($OK == "evento1") {

if ( $fem == "on" ){ echo "<tr><td><B>femenino checado</B></tr> "; };

if ( $mas == "on" ){ echo "<tr><td><B>masculino checado</B> </tr>"; };

if ( $neutro == "on" ){ echo "<tr><td><B>neutro checado</B> </tr>"; };

};

?>
</table>
</FORM>
    </body>
</HTML>


