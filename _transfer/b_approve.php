<?php

    ob_start();
    session_start();

    include_once("t_dbfunctions.php");
    include_once("t_functions.php");
    include_once("t_config.php");

    if(isset($_POST['Approve']) && isset($_POST['RealmlistList']) && isset($_POST['GUID'])) {
        $ACCOUNT_ID = _GetCharacterAccountID();
        $ID         = $_POST['Approve'];
        $RealmID    = $_POST['RealmlistList'];
        $GUID       = $_POST['GUID'];
        if(_CheckCharacterOnlineStatus(_HostDBSwitch($RealmID), $DBUser, $DBPassword, _CharacterDBSwitch($RealmID), $GUID)) {
            if(CheckTransferStatus($AccountDBHost, $DBUser, $DBPassword, $AccountDB, $ID) == 0) {
                if(_CheckGMAccess($AccountDBHost, $DBUser, $DBPassword, $AccountDB, $ACCOUNT_ID, $GMLevel)) {
                    ApproveCharacterTransfer(_HostDBSwitch($RealmID), $DBUser, $DBPassword, _CharacterDBSwitch($RealmID), $GUID);
                    UpdateDumpStatus($AccountDBHost, $DBUser, $DBPassword, $AccountDB, $ID, 1);
                } else die("ACCESS DENIED:");
            } else die("NOT \"IN PROGRESS\" STATUS");
        } else die("LOG OFF WITH THIS CHARACTER! BEFORE MAKE ANY ACTIONS!");
    } else die("SHIT HAPPENS, ERROR 29");

    ob_end_flush();
?>