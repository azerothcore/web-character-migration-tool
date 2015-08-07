<?php

    ob_start();
    session_start();

    include_once("t_dbfunctions.php");
    include_once("t_functions.php");
    include_once("t_config.php");

    if(isset($_POST['Deny']) && isset($_POST['RealmlistList']) && isset($_POST['GUID'])) {
        $ACCOUNT_ID = _GetCharacterAccountID();
        $ID         = $_POST['Deny'];
        $RealmID    = $_POST['RealmlistList'];
        $GUID       = $_POST['GUID'];
        $REASON     = $_POST['REALSON'];

        if(!isset($REASON) || empty($REASON))
            $REASON = "Not meet requeriments.";

        if(_CheckCharacterOnlineStatus(_HostDBSwitch($RealmID), $DBUser, $DBPassword, _CharacterDBSwitch($RealmID), $GUID)) {
            if(CheckTransferStatus($AccountDBHost, $DBUser, $DBPassword, $AccountDB, $ID) == 0) {
                if(_CheckGMAccess($AccountDBHost, $DBUser, $DBPassword, $AccountDB, $ACCOUNT_ID, $GMLevel)) {
                    AddComment($AccountDBHost, $DBUser, $DBPassword, $AccountDB, $ID, $REASON);
                    CancelORDenyCharacterTransfer(_HostDBSwitch($RealmID), $DBUser, $DBPassword, _CharacterDBSwitch($RealmID), $GUID, $STORAGE);
                    UpdateDumpStatus($AccountDBHost, $DBUser, $DBPassword, $AccountDB, $ID, 2);
                } else die("ACCESS DENIED");
            } else die("NOT \"IN PROGRESS\" STATUS");
        } else die("LOG OFF WITH THIS CHARACTER! BEFORE MAKE ANY ACTIONS!");
    } else die("SHIT HAPPENS, ERROR 35");

    ob_end_flush();

    function AddComment($AccountDBHost, $DBUser, $DBPassword, $AccountDB, $ID, $REASON){
        $connection = mysql_connect($AccountDBHost, $DBUser, $DBPassword) or die(mysql_error());
        _SelectDB($AccountDB, $connection);
        $query = mysql_query("UPDATE `account_transfer` SET `Reason` = \"". _X($REASON) ."\" WHERE `id` = ". (int)$ID .";", $connection) or die(mysql_error());
        mysql_close($connection);
    }
?>