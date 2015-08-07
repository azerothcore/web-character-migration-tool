<?php

    ob_start();
    session_start();

    include_once("t_dbfunctions.php");
    include_once("t_functions.php");
    include_once("t_config.php");

    if(isset($_POST['Resend']) && isset($_POST['RealmlistList']) && isset($_POST['GUID'])) {
        $ACCOUNT_ID = _GetCharacterAccountID();
        $ID         = $_POST['Resend'];
        $RealmID    = $_POST['RealmlistList'];
        $GUID       = $_POST['GUID'];
        if(_CheckCharacterOnlineStatus(_HostDBSwitch($RealmID), $DBUser, $DBPassword, _CharacterDBSwitch($RealmID), $GUID)) {
            if(CheckTransferStatus($AccountDBHost, $DBUser, $DBPassword, $AccountDB, $ID) == 0) {
                if(_CheckGMAccess($AccountDBHost, $DBUser, $DBPassword, $AccountDB, $ACCOUNT_ID, $GMLevel)) {
                    _PreparateMails(LoadItemRoW($AccountDBHost, $DBUser, $DBPassword, $AccountDB, $ID),
                    _GetCharacterName(_HostDBSwitch($RealmID), $DBUser, $DBPassword, _CharacterDBSwitch($RealmID), $GUID),
                    $TransferLetterTitle, $TransferLetterMessage, $SOAPUser, $SOAPPassword, _SOAPPSwitch($RealmID), _SOAPHSwitch($RealmID), _SOAPURISwitch($RealmID));
                    ob_end_flush();
                    die("ITEMS RE-SENDED");
                } else die("ACCESS DENIED STATUS");
            } else die("NOT \"IN PROGRESS\" STATUS");
        } else die("LOG OFF WITH THIS CHARACTER! BEFORE MAKE ANY ACTIONS!");
    } else die("SHIT HAPPENS, ERROR 30");
?>