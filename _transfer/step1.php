<?php

    include_once("t_dbfunctions.php");
    include_once("t_functions.php");
    include_once("t_config.php");
    include_once("f_switch.php");

if(isset($_POST['Account']) && !empty($_POST['Account'])    && isset($_POST['Password'])    && !empty($_POST['Password'])
 && isset($_POST['ServerUrl'])  && !empty($_POST['ServerUrl'])  && isset($_POST['RealmlistList'])   && !empty($_POST['RealmlistList'])) {
    $o_Account  = trim($_POST['Account']);
    $o_Password = base64_encode(trim($_POST['Password']));
    $o_URL      = trim($_POST['ServerUrl']);
    if($_FILES['file']['name'] != "chardump.lua") {
        $realson = _RT("Wrong file!");
        Step1Form($AccountDB, $AccountDBHost, $DBUser, $DBPassword, $write[70], $write[71], $write[72], $write[79], $write[74], $write[76], $write[63], $write[77], $realson);
    } else {
        move_uploaded_file($_FILES['file']['tmp_name'], "./storage/". $_FILES['file']['name']);
        $file       = "./storage/chardump.lua";
        $fileopen   = fopen($file, 'r');
        $buffer     = '';
        $realson    = '';

        while(!feof($fileopen)) {
            $buffer2 = fgets($fileopen);
            $buffer .= $buffer2;
        }

        fclose($fileopen);
        unlink($file);
        $part       = explode('"', $buffer);
        if(isset($part[1])) {
            $DUMP               = $part[1];
            $REALM_NAME         = $_POST['RealmlistList'];
            $DECODED_DUMP       = _DECRYPT($DUMP);
            $CHAR_REALM         = GetRealmID($AccountDBHost, $DBUser, $DBPassword, $AccountDB, $REALM_NAME);
            $CHAR_ACCOUNT_ID    = _GetCharacterAccountID();
            $GM_ACCOUNT_ID      = CanOrNoTransferServer($AccountDBHost, $DBUser, $DBPassword, $AccountDB, $CHAR_REALM, $GMLevel);
            $json               = json_decode(stripslashes($DECODED_DUMP), true);
            $CHAR_NAME          = mb_convert_case(mb_strtolower($json['uinf']['name'], 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
            $O_REALMLIST        = $json['ginf']['realmlist'];
            $O_REALM            = $json['ginf']['realm'];
            $RaceID             = _GetRaceID(strtoupper($json['uinf']['race']));
            $ClassID            = _GetClassID(strtoupper($json['uinf']['class']));
            $CharLevel          = _MaxValue($json['uinf']['level'], $MaxCL);

            $connection = mysql_connect($AccountDBHost, $DBUser, $DBPassword);
            _SelectDB($AccountDB, $connection);
            $result = mysql_query("SELECT `address`,`port` FROM `realmlist` WHERE `id` = ". $CHAR_REALM .";", $connection) or die(mysql_error());
            $row    = mysql_fetch_array($result);
            $SPT    = $row['port'];
            $SIP    = $row['address'];
            mysql_close($connection);

            $AchievementsCount  = 0;
            $ACHMINTime         = 0;
            $ACHMAXTime         = 0;
            foreach($json['achiev'] as $key => $value) {
                if($ACHMINTime == 0)
                    $ACHMINTime = $value['D'];
                if($ACHMINTime > $value['D'])
                    $ACHMINTime = $value['D'];
                if($ACHMAXTime < $value['D'])
                    $ACHMAXTime = $value['D'];
                ++$AchievementsCount;
            }

            if(CheckGameBuild($json['ginf']['clientbuild'], $GAMEBUILD)) {
                $realson = _RT($write[50] ." ". $GAMEBUILD);
            } else if(((10 + $CharLevel > $AchievementsCount) || ($AchievementsCount > $AchievementsMinCount)) && $AchievementsCheck == 1) {
                $realson = _RT("Seems bad characters, not enought achievements!");
            } else if(CHECKDAY($ACHMAXTime, $ACHMINTime) < $PLAYTIME) {
                $realson = _RT("Small playtime!");
            } else if(_CheckBlackList($AccountDBHost, $DBUser, $DBPassword, $AccountDB, $o_URL) ||
                _CheckBlackList($AccountDBHost, $DBUser, $DBPassword, $AccountDB, $O_REALM)     ||
                _CheckBlackList($AccountDBHost, $DBUser, $DBPassword, $AccountDB, $O_REALMLIST)) {
                $realson = _RT($write[57]);
            } else if(CanOrNoTransferPlayer(_HostDBSwitch($CHAR_REALM), $DBUser, $DBPassword, _CharacterDBSwitch($CHAR_REALM), $CHAR_ACCOUNT_ID)) {
                $realson = _RT($write[52] . $REALM_NAME . $write[53]);
            } else if($GM_ACCOUNT_ID < 0) {
                $realson = _RT($write[54] . $REALM_NAME . $write[55]);
            } else if(strlen($o_Account) > 32) {
                $realson = _RT($write[99]);
            } else if(!_ServerOn($SIP, $SPT))
                $realson = _RT("Realm: \"". $REALM_NAME ."\" <u>OFFLINE!</u>");

            $GUID   = CheckCharacterGuid($AccountDBHost, $DBUser, $DBPassword, $AccountDB, $CHAR_REALM, GetCharacterGuid(_HostDBSwitch($CHAR_REALM), $DBUser, $DBPassword, _CharacterDBSwitch($CHAR_REALM)));

            if(empty($realson)) {
                $ID = 0;
                $ID =
                WriteDumpFromFileInDB($AccountDBHost, $DBUser, $DBPassword, $AccountDB,
                $DUMP, $CHAR_NAME, $CHAR_ACCOUNT_ID, $CHAR_REALM,
                                    $o_Account, $o_Password, $O_REALMLIST, $O_REALM, $o_URL, $ID, $GUID, $GM_ACCOUNT_ID, $write[20]);
            }
        } else if(!isset($part[1]))
            $realson = _RT($write[51]);
         if(!empty($realson)) {
            Step1Form($AccountDB, $AccountDBHost, $DBUser, $DBPassword, $write[70], $write[71], $write[72], $write[79], $write[74], $write[76], $write[63], $write[77], $realson);
        } else {
            $_SESSION['STEP2']  = "NO";
            $char_money         = _MaxValue($json['uinf']['money'], $MaxMoney);
            $char_speccount     = $json['uinf']['specs'];
            $char_gender        = $json['uinf']['gender'] - 2 == 1 ? 1 : 0;
            $char_totalkills    = $json['uinf']['kills'];
            $char_arenapoints   = _MaxValue($json['uinf']['arenapoints'], $MaxAP);
            $char_honorpoints   = _MaxValue($json['uinf']['honor'], $MaxHP);
            $INVrow             = "";
            $GEMrow             = "";
            $CURrow             = "";
            $row                = "";
            $QUERYFOREXECUTE    = "";

            $connection         = mysql_connect(_HostDBSwitch($CHAR_REALM), $DBUser, $DBPassword);
            
            _SelectDB(_CharacterDBSwitch($CHAR_REALM), $connection);
            mysql_query("
            INSERT INTO `characters`(`guid`,`name`,`level`,`gender`,`totalHonorPoints`,`arenaPoints`,`totalKills`,`money`,`class`,`race`,`at_login`,`account`,`taximask`,`speccount`,`online`) VALUES (
            ". $GUID .",\"". _X($CHAR_NAME) ."\",". (int)$CharLevel .",". (int)$char_gender .",". (int)$char_honorpoints .",". (int)$char_arenapoints .",
            ". (int)$char_totalkills .",".(int)$char_money .",". $ClassID .",". $RaceID .", 0x180, 1, \"0 0 0 0 0 0 0 0 0 0 0 0 0 0\",". (int)$char_speccount .", 0);", $connection);
            $QUERYFOREXECUTE    = $QUERYFOREXECUTE. "
            INSERT INTO `character_transfer` VALUES (". $GUID .",". $CHAR_ACCOUNT_ID .",". $GM_ACCOUNT_ID .",". $ID .");

            UPDATE `characters` SET
            `position_x`    = 5741.36,
            `position_y`    = 626.982,
            `position_z`    = 648.354,
            `map`           = 571,
            `health`        = 100,
            `zone`          = 4395,
            `cinematic`     = 1
                WHERE `guid` = ". $GUID .";";

            if($char_speccount == 2) {
                LearnSeparateSpell(63644, $GUID, $connection);
                LearnSeparateSpell(63645, $GUID, $connection);
            }

            if($ClassID == 6)
                $QUERYFOREXECUTE = $QUERYFOREXECUTE. "\n ". DeathKnightTransfer($GUID);

            foreach($json['glyphs'] as $key => $value) {
                $GlyphID1   = _GetGlyphID($value[0][0]);
                $GlyphID2   = _GetGlyphID($value[0][1]);
                $GlyphID3   = _GetGlyphID($value[0][2]);
                $GlyphID4   = _GetGlyphID($value[1][0]);
                $GlyphID5   = _GetGlyphID($value[1][1]);
                $GlyphID6   = _GetGlyphID($value[1][2]);
                $QUERYFOREXECUTE    = $QUERYFOREXECUTE. "\n INSERT IGNORE /* GLYPHS */ INTO `character_glyphs` VALUES (". $GUID .",". (int)$key .",
                ". (int)$GlyphID1 .",". (int)$GlyphID4 .",". (int)$GlyphID5 .",". (int)$GlyphID2 .",". (int)$GlyphID6 .",". (int)$GlyphID3 .");";
            }

            foreach($json['achiev'] as $key => $value) {
                $achievement        = $value['I'];
                $date               = $value['D'];
                if(_CheckWrongOrNoAchievement($achievement))
                    $QUERYFOREXECUTE    = $QUERYFOREXECUTE. "\n INSERT IGNORE /* ACHIEVEMENT */ INTO `character_achievement` VALUES (". $GUID .", ". (int)$achievement .", ". (int)$date .");";
            }

            $locale         = trim(strtoupper($json['ginf']['locale']));
            foreach($json['rep'] as $key => $value) {
                $reputation = $value['V'];
                $faction    = GetFactionID(mb_strtoupper($value['N'], 'UTF-8'), $locale);
                if($faction < 1 || $reputation < 1)
                    continue;
                $flag       = $value['F'] + 1;
                if($faction == 1119 && $reputation > 1)
                    $QUERYFOREXECUTE = $QUERYFOREXECUTE. "\n ". SonsOfHordirTransfer($GUID);
                $QUERYFOREXECUTE    = $QUERYFOREXECUTE. "\n INSERT IGNORE /* REPUTATION */ INTO `character_reputation` VALUES (". $GUID .", ". $faction .", ". (int)$reputation .",". (int)$flag .");";
            }

            foreach($json['skills'] as $key => $value) {
                $SkillName = mb_strtoupper($value['N'], 'UTF-8');

                if(_CheckRiding($SkillName, $value['C'], $connection, $GUID, $CharLevel))
                    continue;

                $SkillID    = GetSkillID($SkillName, $locale);
                if($SkillID < 1)
                    continue;

                $max        = _MaxValue(RemoveRaceBonus($RaceID, $SkillID, $value['M']), 450);
                $cur        = _MaxValue(RemoveRaceBonus($RaceID, $SkillID, $value['C']), 450);
                $SpellID    = GetSpellIDForSkill($SkillID, $max);

                if(CheckExtraSpell($SkillID))
                    LearnSeparateSpell(GetExtraSpellForSkill($SkillID, $cur, $GUID, $connection), $GUID, $connection);

                $QUERYFOREXECUTE    = $QUERYFOREXECUTE. "\n INSERT IGNORE /* SKILL */ INTO `character_skills` VALUES (". $GUID .", ". (int)$SkillID .",". (int)$cur .",". (int)$max .");";
                if($SpellID < 3)
                    continue;

                $QUERYFOREXECUTE    = $QUERYFOREXECUTE. "\n INSERT IGNORE /* SPELL FOR SKILL */ INTO `character_spell` VALUES (". $GUID .", ". (int)$SpellID .", 1, 0);";
            }

            foreach($json['spells'] as $SpellID => $value) {
                if(_isSpellValid($SpellID, $ClassID))
                    $QUERYFOREXECUTE    = $QUERYFOREXECUTE. "\n INSERT IGNORE /* NOT MOUNT OR CRITTER */ INTO `character_spell` VALUES (". $GUID .", ". (int)$SpellID .", 1, 0);";
            }

            foreach($json['creature'] as $key => $SpellID) {
                $QUERYFOREXECUTE        = $QUERYFOREXECUTE. "\n INSERT IGNORE /* MOUNT OR CRITTER */ INTO `character_spell` VALUES (". $GUID .", ". (int)$SpellID .", 1, 0);";
            }

            mysql_close($connection);
            foreach($json['currency'] as $key => $value) {
                $CurrencyID = $value['I'];
                $COUNT      = $value['C'];
                if($COUNT < 1)
                    continue;

                if(_CheckCurrency($CurrencyID))
                    $CURrow .= $CurrencyID.":".$COUNT." ";
            }

            foreach($json['inventory'] as $key => $value) {
                $item   = _GetChangedItem($CHAR_REALM, $value['I']);
                $count  = CheckItemCount($value['C']);

                $INVrow .= $item .":". $count ." ";
                $GEM1   = _GetGemID($value['G1']);
                $GEM2   = _GetGemID($value['G2']);
                $GEM3   = _GetGemID($value['G3']);
                if($GEM1 > 1)
                    $GEMrow .= $GEM1 .":1 ";
                if($GEM2 > 1)
                    $GEMrow .= $GEM2 .":1 ";
                if($GEM3 > 1)
                    $GEMrow .= $GEM3 .":1 ";
            }
            
            $QUERYFOREXECUTE_CON    = new mysqli(_HostDBSwitch($CHAR_REALM), $DBUser, $DBPassword, _CharacterDBSwitch($CHAR_REALM));
            mysqli_multi_query($QUERYFOREXECUTE_CON, $QUERYFOREXECUTE) or die(mysql_error());
            
            $row = trim($INVrow . $GEMrow . $CURrow);
            UpdateDumpITEMROW($AccountDBHost, $DBUser, $DBPassword, $AccountDB, $ID, $row);
            if(_CheckCharacterName(_HostDBSwitch($CHAR_REALM), $DBUser, $DBPassword, _CharacterDBSwitch($CHAR_REALM), $CHAR_NAME) > 1) {
                $_SESSION['guid']       = $GUID;
                $_SESSION['realm']      = $CHAR_REALM;
                $_SESSION['dumpID']     = $ID;
                $_SESSION['STEP2']      = "YES";
                include("step2.php");
            } else {
                UpdateDumpStatus($AccountDBHost, $DBUser, $DBPassword, $AccountDB, $ID, 0);
                _PreparateMails($row, $CHAR_NAME, $TransferLetterTitle, $TransferLetterMessage, $SOAPUser, $SOAPPassword, _SOAPPSwitch($CHAR_REALM), _SOAPHSwitch($CHAR_REALM), _SOAPURISwitch($CHAR_REALM));
                _TalentsReset(_HostDBSwitch($CHAR_REALM), $DBUser, $DBPassword, _CharacterDBSwitch($CHAR_REALM), $GUID);
                MoveToGMAccount(_HostDBSwitch($CHAR_REALM), $DBUser, $DBPassword, _CharacterDBSwitch($CHAR_REALM), $GUID);
                echo "<font color = \"green\">". $write[91] ."</font>";
            }
        }
    }
} else Step1Form($AccountDB, $AccountDBHost, $DBUser, $DBPassword, $write[70], $write[71], $write[72], $write[79], $write[74], $write[76], $write[63], $write[77]);

    function CHECKDAY($TIME1, $TIME2) {
        $DIFF = floor(($TIME1-$TIME2)/86400);
        return $DIFF;
    }

    function Step1Form($AccountDB, $AccountDBHost, $DBUser, $DBPassword, $TEXT1, $TEXT2, $TEXT3, $TEXT4, $TEXT5, $TEXT6, $TEXT7, $TEXT8, $REALSON = "") {
        echo $REALSON. "<div align = center class = \"MythTable\">". $TEXT1 ."</div>
        <br>
        <form action=\"". $_SERVER['PHP_SELF'] ."\" method=\"post\" enctype=\"multipart/form-data\">
            <table width=\"525px\">
            <tr><td><div align = right class = \"MythTable\">". $TEXT2 ."</div></td></tr>
            <tr><td><b>Account: </b><input name=\"Account\" type=\"text\" size=\"32\" style = \"float: right;\"></td></tr>
            <tr><td><div align = right class = \"MythTable\">". $TEXT3 ."</div></td></tr>
            <tr><td><b>Password: </b><input name=\"Password\" type=\"password\" size=\"32\" style = \"float: right;\"></tr>
            <tr><td><br></td></tr>
            <tr><td><div align = right class = \"MythTable\">". $TEXT4 ."</div></td></tr>
            <tr><td><b>I want transfer to Realm: </b><select name=\"RealmlistList\">";
                $connection = mysql_connect($AccountDBHost, $DBUser, $DBPassword);
                _SelectDB($AccountDB, $connection);
                $result = mysql_query("SELECT `id`,`name` FROM `realmlist` WHERE `TransferAvailable` = 1;");
                mysql_close($connection);
                while($row = mysql_fetch_array($result))
                    echo "<option name=\"".$row['id']."\">". $row['name'] ."</option>";
            echo "</select><tr><td>
            <tr><td><div align = right class = \"MythTable\">". $TEXT5 ."</div></td></tr>
                <tr><td><b>Server URL: </b><input name=\"ServerUrl\" type=\"text\" size=\"60\" style = \"float: right;\"></td></tr>
            <tr><td><div align = right class = \"MythTable\">". $TEXT6 ."</div></td></tr>
            </table>
                <div class = \"MythInput\">
                    <style = \"font-size:14px\">". $TEXT7 ."</style>
                    <input type=\"file\" name=\"file\" id=\"file\" />
                    <input type=\"submit\" name=\"load\" value=\"". $TEXT8 ."\" />
                </div>
        </form>";
    }

    function _RT($TEXT) { return "<font color=#CC0000><b>". $TEXT ."</b></font><br>"; }
?>