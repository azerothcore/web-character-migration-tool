<?php
/*
 * Copyright (C) 2019+ MasterkinG32 <https://masterking32.com>
 * Copyright (C) 2017+ AzerothCore <www.azerothcore.org>, released under GNU GPL v2 license: http://github.com/azerothcore/azerothcore-wotlk/LICENSE-GPL2
 * Copyright (C) 2008-2016 TrinityCore <http://www.trinitycore.org/>
 * Copyright (C) 2005-2009 MaNGOS <http://getmangos.com/>
*/

    include_once("t_dbfunctions.php");
    include_once("t_functions.php");
    include_once("t_config.php");

    if(isset($_POST['rename'])) {
        $CHAR_NAME  = mb_convert_case(trim($_POST['rename']), MB_CASE_TITLE, 'UTF-8');
        $GUID       = $_SESSION['guid'];
        $RealmID    = $_SESSION['realm'];
        $ID         = $_SESSION['dumpID'];
        $realson    = "";

        $connection = mysqli_connect($AccountDBHost, $DBUser, $DBPassword, $AccountDB, $DB_PORT);
        _SelectDB($connection);
        $result = mysqli_query($connection,"SELECT `name`,`address`,`port` FROM `realmlist` WHERE `id` = ". $RealmID .";", $connection) or die(mysqli_error($connection));
        $row    = mysqli_fetch_array($result);
        $SPT    = $row["port"];
        $SNA    = $row["name"];
        $SIP    = $row["address"];
        mysqli_close($connection);

        if(!isset($_SESSION['guid']) || !isset ($_SESSION['realm']) || !isset($_SESSION['dumpID']) || $_SESSION['STEP2'] != "YES") {
            $realson    = $write[98];
        } else if(preg_match('/[\'^?$%&*()}{@#~?><>,|=_+�-]./', $CHAR_NAME)) {
            $realson    = $write[92];
        } else if(strstr( $CHAR_NAME, " ")) {
            $realson    = $write[93];
        } else if(preg_match("/[0-9]/", $CHAR_NAME)) {
            $realson    = $write[94];
        } else if(mb_strlen($CHAR_NAME, 'UTF-8') > 16 && mb_strlen($CHAR_NAME, 'UTF-8') > 1) {
            $realson    = $write[95];
        } else if(_CheckCharacterName(_HostDBSwitch($RealmID), $DBUser, $DBPassword, _CharacterDBSwitch($RealmID), $CHAR_NAME) > 0) {
            $realson    = $write[96] . $CHAR_NAME . $write[97];
        } else if(!_ServerOn($SIP, $SPT))
            $realson    = "Realm: \"". $SNA ."\" <u>OFFLINE!</u>";

        if(!empty($realson)) {
            Step2Form($realson, $write[90]);
        } else {
            $_SESSION['STEP2']  = "NO";
            UpdateCharacterName(_HostDBSwitch($RealmID), $DBUser, $DBPassword, _CharacterDBSwitch($RealmID), $CHAR_NAME, $GUID);
            _TalentsReset(_HostDBSwitch($RealmID), $DBUser, $DBPassword, _CharacterDBSwitch($RealmID), $GUID);
            _PreparateMails(LoadItemRoW($AccountDBHost, $DBUser, $DBPassword, $AccountDB, $ID),
            $CHAR_NAME, $TransferLetterTitle, $TransferLetterMessage, $SOAPUser, $SOAPPassword, _SOAPPSwitch($RealmID),_SOAPHSwitch($RealmID), _SOAPURISwitch($RealmID));
            UpdateDumpSTATUSandNAME($AccountDBHost, $DBUser, $DBPassword, $AccountDB, $ID, $CHAR_NAME, 0);
            MoveToGMAccount(_HostDBSwitch($RealmID), $DBUser, $DBPassword, _CharacterDBSwitch($RealmID), $GUID);

            echo "<font color = \"green\">". $write[91] ."</font>";
        }
    } else Step2Form("Character with same name \"".$CHAR_NAME."\" already exists!", $write[90]);

    function Step2Form($TEXT1, $TEXT2) {
        echo "<div align = center style = \"padding-right: 2px;font-family: 'Tahoma'; color:#CC0000; font-size:15px;\"><b>". $TEXT1 ."</b><br></div>
        <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\" enctype=\"multipart/form-data\">
            <table width=\"650px\">
                <tr><td><div align = right class = \"MythTable\">". $TEXT2 ."</div></td></tr>
                <tr><td>
                    <div align = center class = \"MythInput\">
                        <input type=\"text\" name=\"rename\" id=\"rename\">
                        <button type=\"submit\" style = \"font-size:10px\">Submit</button>
                    </div>
                </td></tr>
            </table>
        </form>";
    }
?>