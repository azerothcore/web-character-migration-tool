<?php

    ob_start();
    session_start();

    include_once("t_dbfunctions.php");
    include_once("t_functions.php");
    include_once("t_config.php");

    if(isset($_POST['cancel']) && isset($_POST['RealmlistList']) && isset($_POST['GUID'])) {
        $ACCOUNT_ID = _GetCharacterAccountID();
        $ID         = $_POST['cancel'];
        $RealmID    = $_POST['RealmlistList'];
        $GUID       = $_POST['GUID'];
        if(_CheckCharacterOnlineStatus(_HostDBSwitch($RealmID), $DBUser, $DBPassword, _CharacterDBSwitch($RealmID), $GUID)) {
            if(CheckTransferStatus($AccountDBHost, $DBUser, $DBPassword, $AccountDB, $ID) == 0) {
                if(!_CheckGMAccess($AccountDBHost, $DBUser, $DBPassword, $AccountDB, $ACCOUNT_ID, $GMLevel)) {
                    CancelORDenyCharacterTransfer(_HostDBSwitch($RealmID), $DBUser, $DBPassword, _CharacterDBSwitch($RealmID), $GUID, $STORAGE);
                    UpdateDumpStatus($AccountDBHost, $DBUser, $DBPassword, $AccountDB, $ID, 3);
                    ob_end_flush();
                    die("Transfer with ID: ". $ID ." Canceled");
                } else die("ACCESS DENIED");
            } else die("NOT \"IN PROGRESS\" STATUS");
        } else die("LOG OFF WITH THIS CHARACTER! BEFORE MAKE ANY ACTIONS!");
    } else die("SHIT HAPPENS, ERROR 30");
?>