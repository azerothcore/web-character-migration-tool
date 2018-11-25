<?php
/*
 * Copyright (C) 2019+ MasterkinG32 <https://masterking32.com>
 * Copyright (C) 2017+ AzerothCore <www.azerothcore.org>, released under GNU GPL v2 license: http://github.com/azerothcore/azerothcore-wotlk/LICENSE-GPL2
 * Copyright (C) 2008-2016 TrinityCore <http://www.trinitycore.org/>
 * Copyright (C) 2005-2009 MaNGOS <http://getmangos.com/>
*/

    $TransferLetterTitle    = "Transfer System";                    // Letter Title
    $TransferLetterMessage  = "Items from Character Transfer";      // Letter Message

    $language               = "en";         // WebPage Language "en" - English, "de" - German, "es" - Spanish, "ru" - Russian.
    $captchaEnable          = 0;            // ENABLE (1) / DISABLE (0) Captcha
    $AchievementsCheck      = 0;            // ENABLE (1) / DISABLE (0) FORMULA: must have more then Level > 10 OR AchievementsMinCount param
    $AchievementsMinCount   = 50;           // Minimum ammount of Achievements.
    $PLAYTIME               = 0;           // Minimum Playtime. Counted as: last archievment date - first archievment date

    $AccountDB          = "auth"; // Your Account DB Name
    $AccountDBHost      = "127.0.0.1";      // Your Account DB Host
    $DB_PORT         	= "3306";       // Your DB Port
    $DBUser             = "root";           // Your DB User
    $DBPassword         = "root";       // Your DB Password

    $SOAPUser           = "admin";          // SOAP USER
    $SOAPPassword       = "admin";          // SOAP USER PASSWORD
    $GMLevel            = "(3,4,5,6,7)";    // GM LEVEL ACCESS AVAIBLE CHECK TRANSFERS. IN BRACKETS AND SEPARATE WITH COMMA. EXAMPLE: "(3,4)"

    $GAMEBUILD          = 12340;            // Game Build for available transfer. that mean transfers accept only from this build. DO NOT TOUCH.
    $STORAGE            = 0;                // Account Where story Rejected or Canceled Transfers
    $MaxMoney           = 200000000;        // Max Money, if more then it, then only this. put values in copper coins
    $MaxHP              = 75000;            // Max Honor Points, if more then it, then only this.
    $MaxCL              = 80;               // Max Character level, if more then it, then only this.
    $MaxAP              = 5000;             // Max Arena Points, if more then it, then only this.
                                            // if do not exist stay -1, if no then put info
    function _SOAPURISwitch($ID) {          // Realm ID = Realm ID From Realmlist table
        $SOAPURI1  = "urn:TC";              // Realm 1 SOAP URI
        $SOAPURI2  = -1;                    // Realm 2 SOAP URI
        $SOAPURI3  = -1;                    // Realm 3 SOAP URI
        $SOAPURI4  = -1;                    // Realm 4 SOAP URI
        $SOAPURI5  = -1;                    // Realm 5 SOAP URI
        $SOAPURIUNK = -1;                   // if 6+ Realm exist return Error
        switch($ID) {
            case 1:     return $SOAPURI1;
            case 2:     return $SOAPURI2;
            case 3:     return $SOAPURI3;
            case 4:     return $SOAPURI4;
            case 5:     return $SOAPURI5;
            default:    return $SOAPURIUNK;
        }
    }

                                            // if do not exist stay -1, if no then put info
    function _SOAPHSwitch($ID) {            // Realm ID = Realm ID From Realmlist table
        $SOAPHost1  = "127.0.0.1";          // Realm 1 SOAP HOST
        $SOAPHost2  = -1;                   // Realm 2 SOAP HOST
        $SOAPHost3  = -1;                   // Realm 3 SOAP HOST
        $SOAPHost4  = -1;                   // Realm 4 SOAP HOST
        $SOAPHost5  = -1;                   // Realm 5 SOAP HOST
        $SOAPHostUNK = -1;                  // if 6+ Realm exist return Error
        switch($ID) {
            case 1:     return $SOAPHost1;
            case 2:     return $SOAPHost2;
            case 3:     return $SOAPHost3;
            case 4:     return $SOAPHost4;
            case 5:     return $SOAPHost5;
            default:    return $SOAPHostUNK;
        }
    }
                                            // if do not exist stay -1, if no then put info
    function _SOAPPSwitch($ID) {            // Realm ID = Realm ID From Realmlist table
        $SOAPPort1  = 7878;                 // Realm 1 SOAP PORT
        $SOAPPort2  = -1;                   // Realm 2 SOAP PORT
        $SOAPPort3  = -1;                   // Realm 3 SOAP PORT
        $SOAPPort4  = -1;                   // Realm 4 SOAP PORT
        $SOAPPort5  = -1;                   // Realm 5 SOAP PORT
        $SOAPPortUNK = -1;                  // if 6+ Realm exist return Error
        switch($ID) {
            case 1:     return $SOAPPort1;
            case 2:     return $SOAPPort2;
            case 3:     return $SOAPPort3;
            case 4:     return $SOAPPort4;
            case 5:     return $SOAPPort5;
            default:    return $SOAPPortUNK;
        }
    }
                                              // if do not exist stay -1, if no then put info, FOR CHARACTERS DBs
    function _HostDBSwitch($ID) {             // Realm ID = Realm ID From Realmlist table
        $HostDB1      = "127.0.0.1";          // Realm 1 Host DB
        $HostDB2      = -1;                   // Realm 2 Host DB
        $HostDB3      = -1;                   // Realm 3 Host DB
        $HostDB4      = -1;                   // Realm 4 Host DB
        $HostDB5      = -1;                   // Realm 5 Host DB
        $HostDBUNK    = -1;                   // if 6+ Realm exist return Error
        switch($ID) {
            case 1:     return $HostDB1;
            case 2:     return $HostDB2;
            case 3:     return $HostDB3;
            case 4:     return $HostDB4;
            case 5:     return $HostDB5;
            default:    return $HostDBUNK;
        }
    }
                                                    // if do not exist stay -1, if no then put info, FOR CHARACTERS DBs
    function _CharacterDBSwitch($ID) {              // Realm ID = Realm ID From Realmlist table
        $CharactersDB1      = "characters";// Realm 1 Character DB
        $CharactersDB2      = -1;                   // Realm 2 Character DB
        $CharactersDB3      = -1;                   // Realm 3 Character DB
        $CharactersDB4      = -1;                   // Realm 4 Character DB
        $CharactersDB5      = -1;                   // Realm 5 Character DB
        $CharactersDBUNK    = -1;                   // if 6+ Realm exist return Error
        switch($ID) {
            case 1:     return $CharactersDB1;
            case 2:     return $CharactersDB2;
            case 3:     return $CharactersDB3;
            case 4:     return $CharactersDB4;
            case 5:     return $CharactersDB5;
            default:    return $CharactersDBUNK;
        }
    }

    function _CheckWrongOrNoItem($REALMID, $ID) {
        switch($REALMID) {      // case ID: = realm id from realmlist table
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
            default:
                switch($ID) {   // IF YOU WANT REJECT ITEM. ADD CASE <ID> : BEFORE RETURN TRUE. ITEM DROP OUR FROM DELIVERY LIST.
                    case 17:    // UBER CHEST: Martin Fury
                    case 43651: // UBER FINISH POLE: Crafty's Pole
                    case 25596: // UBER MOUNT: Peep's Whistle
                    case 17782: // UBER NECK: Talisman of Binding Shard
                    case 12947: // UBER RING: Alex's Ring of Audacity
                    case 192:   // UBER STAFF: Martin's Broken Staff
                    case 22989: // UBER BLADE: The Breaking
                    case 36942: // UBER BLADE: Frostmourne
                    case 32824: // UBER BLADE: Tigole's Trashbringer
                    case 18582: // UBER BLADE: The Twin Blades of Azzinoth
                    case 18583: // UBER BLADE: Warglaive of Azzinoth (Right)
                    case 18584: // UBER BLADE: Warglaive of Azzinoth (Left)
                        return true;
                    default: return false;
                }
                break;
        }
    }

    function _GetChangedItem($REALMID, $ID) {
        if(_CheckWrongOrNoItem($REALMID, $ID))
            return -1;

        switch($REALMID) {      // case ID: = realm id from realmlist table
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
            default:
                switch($ID) {   // IF YOU WANT CHANGE ITEM. ADD CASE <ID> : RETURN < NEW ID>. ITEM WILL BE CHANGED IN DELIVERY LIST.
                    case 49623: return 49888;   // Shadowmourne to Shadow's Edge
                    default:
                        return $ID;
                }
                break;
        }
    }

    function _CheckWrongOrNoAchievement($ID) {
        switch($ID) {
            //case XX: return false;
            default: return true;
        }
    }
?>