<?php
/*
 * Copyright (C) 2019+ MasterkinG32 <https://masterking32.com>
 * Copyright (C) 2017+ AzerothCore <www.azerothcore.org>, released under GNU GPL v2 license: http://github.com/azerothcore/azerothcore-wotlk/LICENSE-GPL2
 * Copyright (C) 2008-2016 TrinityCore <http://www.trinitycore.org/>
 * Copyright (C) 2005-2009 MaNGOS <http://getmangos.com/>
*/

    ob_start();
    session_start();
sleep(5);
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

        if(_CheckCharacterOnlineStatus(_HostDBSwitch($RealmID),$DB_PORT, $DBUser, $DBPassword, _CharacterDBSwitch($RealmID), $GUID)) {
            if(CheckTransferStatus($AccountDBHost,$DB_PORT, $DBUser, $DBPassword, $AccountDB, $ID) == 0) {
                if(_CheckGMAccess($AccountDBHost,$DB_PORT, $DBUser, $DBPassword, $AccountDB, $ACCOUNT_ID, $GMLevel)) {
                    AddComment($AccountDBHost,$DB_PORT, $DBUser, $DBPassword, $AccountDB, $ID, $REASON);
                    CancelORDenyCharacterTransfer(_HostDBSwitch($RealmID),$DB_PORT, $DBUser, $DBPassword, _CharacterDBSwitch($RealmID), $GUID, $STORAGE);
                    UpdateDumpStatus($AccountDBHost,$DB_PORT, $DBUser, $DBPassword, $AccountDB, $ID, 2);
					ob_end_flush();
                    die("Transfer with ID: ". $ID ." Canceled");
                } else die("ACCESS DENIED");
            } else die("NOT \"IN PROGRESS\" STATUS");
        } else die("LOG OFF WITH THIS CHARACTER! BEFORE MAKE ANY ACTIONS!");
    } else die("SHIT HAPPENS, ERROR 35");

    ob_end_flush();

    function AddComment($AccountDBHost,$DB_PORT, $DBUser, $DBPassword, $AccountDB, $ID, $REASON){
        $connection = mysqli_connect($AccountDBHost, $DBUser, $DBPassword,$AccountDB,$DB_PORT) or die(mysqli_error($connection));
        _SelectDB($connection);
        $query = mysqli_query($connection,"UPDATE `account_transfer` SET `Reason` = \"". _X($connection,$REASON) ."\" WHERE `id` = ". (int)$ID .";") or die(mysqli_error($connection));
        mysqli_close($connection);
    }
?>