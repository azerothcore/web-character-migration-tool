<meta http-equiv = "Content-Type" content = "text/html; charset=UTF-8" />
<?php


    include_once("t_config.php");
    include_once("t_functions.php");
    include_once("t_dbfunctions.php");

    $file       = "chardump.lua";
    $fileopen   = fopen($file, 'r') or die("<b><font color=\"red\">Unable to open file or your fire not right!</font></b>");
    $buffer     = '';

    while (!feof($fileopen)) {
        $buffer2 = fgets($fileopen);
        $buffer .= $buffer2;
    }

    $part       = explode('"', $buffer);
    $DUMP       = _DECRYPT($part[1]);
//    $DUMP               = LoadDump($AccountDBHost, $DBUser, $DBPassword, $AccountDB, /*DUMP_ID*/);
    $json               = json_decode(stripslashes($DUMP), true);
    echo "<br> --- DEBUGER --- <br>\n\n\n\n";


//    RemoteCommandWithSOAP($SOAPUser, $SOAPPassword, 7878, "127.0.0.1", _SOAPURISwitch(1), ".server info");
    print_r($json);
?>