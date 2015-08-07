<?php

    ob_start();
    session_start();

    if     (isset($_POST['load']))      $step = 1;
    else if(isset($_POST['rename']))    $step = 2;
    else                                $step = 3;
    if(!isset($_SESSION['loged']))
        Header('Location: index.php');

    include('template/t_header.php');
    include('_transfer/language.php');
    include_once("_transfer/t_dbfunctions.php");
    include_once("_transfer/t_config.php");

    $ID         = $_SESSION['id'];
?>
<table width = "800" align = "center" cellpadding = "0" cellspacing = "0">
    <tr><td align = "right"><?php echo $write[5]."<strong> ". mb_strtoupper($_SESSION['user'], 'UTF-8') ."</strong>! || <a href=\"playerside.php\" class='generallink'>$write[6]</a> || <a href=\"logout.php\" class='generallink'>$write[7]</a>"; ?></td></tr>
</table><br>
<script type = "text/javascript" src = "template/jquery-1.7.2.min.js"></script>
<script type = "text/javascript">
<?php
    if(!_CheckGMAccess($AccountDBHost, $DBUser, $DBPassword, $AccountDB, $ID, $GMLevel)) {
echo "
    function DoCancel( id, Realm, Guid ) {
        $.ajax({
        type : 'POST',
        url : '_transfer/b_cancel.php',
        data : {
            cancel : id,
            RealmlistList : Realm,
            GUID : Guid
        },
        success : function( data ) {
            $( '#' + id ).hide( );
            location.reload( true );
            alert( data );
        },
        error : function( XMLHttpRequest, textStatus, errorThrown ) {
            alert( textStatus + \" -- \" + errorThrown );
        }
        });
    }";
    } else {
echo "
    function DoApprove( id, Realm, Guid ) {
        $.ajax({
        type : 'POST',
        url : '_transfer/b_approve.php',
        data : {
            Approve : id,
            RealmlistList: Realm,
            GUID : Guid
        },
        success : function( data ) {
            $( '#' + id ).hide( );
            location.reload( true );
        },
        error : function( XMLHttpRequest, textStatus, errorThrown ) {
            alert( textStatus + \" -- \" + errorThrown );
        }
        });
    }
    function DoDeny( id, Realm, Guid ) {
        var Reason = prompt( \"Reason:\", \"\" );
        $.ajax({
        type : 'POST',
        url : '_transfer/b_deny.php',
        data : {
            Deny : id,
            RealmlistList : Realm,
            GUID : Guid,
            REALSON : Reason,
        },
        success : function( data ) {
            $( '#' + id ).hide( );
            location.reload( true );

            alert( data );
        },
        error : function( XMLHttpRequest, textStatus, errorThrown ) {
            alert( textStatus + \" -- \" + errorThrown );
        }
        });
    }
    function DoResend( id, Realm, Guid ) {
        $.ajax({
        type : 'POST',
        url : '_transfer/b_resend.php',
        data : {
            Resend : id,
            RealmlistList : Realm,
            GUID : Guid,
        },
        success : function( data ) {
            $( '#' + id ).hide( );
            location.reload( true );
            alert ( data );
        },
        error : function( XMLHttpRequest, textStatus, errorThrown ) {
            alert( textStatus + \" -- \" + errorThrown );
        }
        });
    }";
    } ?>
</script>
<table width = "700" cellpadding = "0" cellspacing = "0" border = "0" rules = "none" align = "center">
    <tr><td align = "center">
        <table align = "center">
            <tr><td align = "left">
<?php
    switch($step) {
        case 1: include("_transfer/step1.php"); break;
        case 2: include("_transfer/step2.php"); break;
        case 3: FlushStatisticTable($AccountDBHost, $DBUser, $DBPassword, $AccountDB, $ID, $GMLevel, $write[78], $write[75], $write[60], $write[65], $write[61],
        $write[85], $write[86], $write[30], $write[31], $write[32], $write[33], $write[34], $write[84]);
            break;
    }
?>
            </td></tr>
        </table>
    </td></tr>
</table>
<?php include ('template/t_footer.php');
    ob_end_flush();

    function FlushStatisticTable($AccountDBHost, $DBUser, $DBPassword, $AccountDB, $ID, $GMLevel,
        $TEXT1, $TEXT2, $TEXT3, $TEXT4, $TEXT5, $TEXT6, $TEXT7, $TEXT8, $TEXT9, $TEXT10, $TEXT11, $TEXT12, $TEXT13)
    {
        if(_CheckGMAccess($AccountDBHost, $DBUser, $DBPassword, $AccountDB, $ID, $GMLevel)) {
            echo "<div align = right class = \"MythTable\" style = \"width: 100%; padding-right: 2px;font-family: 'Tahoma';\">". $TEXT1 ."</div>
            <br>";
            $connection = mysql_connect($AccountDBHost, $DBUser, $DBPassword);
            _SelectDB($AccountDB, $connection);
            $query = mysql_query("SELECT * FROM `account_transfer` WHERE `gmAccount` = ". $ID ." ORDER BY `id` DESC LIMIT 25;", $connection);
            mysql_close($connection);
        } else {
            echo "
            <div align = right style = \"width: 100%; padding-right: 2px;font-family: 'Tahoma'; \">". $TEXT2 ."</div>
            <br>
            <div style = \"font-size:17px\">". $TEXT3 ."</div>
            <div class = \"MythInput\">
                <form action=\"". $_SERVER["SCRIPT_NAME"] ."\" method=\"post\" enctype=\"multipart/form-data\">
                    <input type=\"submit\" name = \"load\" value=\"". $TEXT4 ."\"/>
                </form>
            </div>
            <div align = right class = \"MythTable\" style = \"width: 100%; padding-right: 2px;font-family: 'Tahoma';\">". $TEXT5 ."</div>";

            $connection = mysql_connect($AccountDBHost, $DBUser, $DBPassword);
            _SelectDB($AccountDB, $connection);
            $query = mysql_query("SELECT * FROM `account_transfer` WHERE `cAccount` = ". $ID ." ORDER BY `id` DESC LIMIT 25;", $connection);
            mysql_close($connection);
        }
        echo "
            <div style = \"white-space: nowrap; border-top-width: 1px; border-top-style: solid; padding-top: 8px; margin-top: 8px;\">
            <table width = 100% align = center >
                <tr bgcolor = #FFEAC7>";
        if(_CheckGMAccess($AccountDBHost, $DBUser, $DBPassword, $AccountDB, $ID, $GMLevel)) {
            echo "
                <td>\"OUR\" & \"OLD\" Name: </td>
                <td>\"OUR\" & \"OLD\" Realm:</td>
                <td>Realmlist:              </td>
                <td>Account:                </td>
                <td>Password:               </td>
                <td>Server URL:             </td>
                <td>Admin Options:          </td>
            </tr>";
            while($row = mysql_fetch_array($query)) {
                if($row["cStatus"] == 0)
                    echo "
                    <tr bgcolor = #FFFFCC>
                        <td>". $row["cNameNEW"] ." / ". $row["cNameOLD"] ."</td>
                        <td>". _CheckRealm($AccountDBHost, $DBUser, $DBPassword, $AccountDB, $row["cRealm"]) ." / ". $row["oRealm"] ."</td>
                        <td>". $row["oRealmlist"]               ."</td>
                        <td>". $row["oAccount"]                 ."</td>
                        <td>". base64_decode($row["oPassword"]) ."</td>
                        <td>". $row["oServer"]                  ."</td>
                        <td align = center>
                            <button name = \"Approve\" id = \"".$row["id"]."\" onclick = \"javascript:DoApprove('".$row["id"]."', '".$row["cRealm"]."', '".$row["GUID"]."');\" style = \"font-size:10px\"><font color = \"green\">". $TEXT6 ."</font></button><br>
                            <button name = \"Deny\" id = \"".$row["id"]."\" onclick = \"javascript:DoDeny('".$row["id"]."', '".$row["cRealm"]."', '".$row["GUID"]."');\" style = \"font-size:10px\"><font color = \"red\">". $TEXT7 ."</font></button><br>
                            <button name = \"Resend\" id = \"".$row["id"]."\" onclick = \"javascript:DoResend('".$row["id"]."', '".$row["cRealm"]."', '".$row["GUID"]."');\" style = \"font-size:10px\"><font color = \"purple\">Resend</font></button>
                        </td>
                    </tr>";
            }
        } else {
            echo "
                <td>No.:            </td>
                <td>Character Name: </td>
                <td>Realm:          </td>
                <td>Status:         </td>
                <td>Options:        </td>
            </tr>";
            while($row = mysql_fetch_array($query)) {
                echo "
                    <tr bgcolor = #FFFFCC>
                        <td align = center bgcolor = #FFEAC7>". $row["id"]  ."</td>
                        <td>". $row["cNameNEW"]                             ."</td>
                        <td align = center>". _CheckRealm($AccountDBHost, $DBUser, $DBPassword, $AccountDB, $row["cRealm"]) ."</td>
                        <td bgcolor = #FFEAC7 ". _CheckReason($row["cStatus"], $row["Reason"]) .">". _CheckStatus($row["cStatus"], $TEXT8, $TEXT9, $TEXT10, $TEXT11, $TEXT12, $row["Reason"])   ."</td>
                        <td align = center>";
                if($row["cStatus"] == 0)
                    echo "<button name = \"cancel\" id = \"".$row["id"]."\" onclick = \"javascript:DoCancel('".$row["id"]."', '".$row["cRealm"]."', '".$row["GUID"]."');\" style = \"font-size:10px\"><font color = \"purple\">". $TEXT13 ."</button>";
                echo "  </td>
                    </tr>";
            }
        }
        echo "</table></div>";
    }
?>