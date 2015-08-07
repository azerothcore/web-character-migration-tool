

INSERT INTO `characters` (`guid`,`account`,`name`,`race`,`class`,`gender`,`level`,`xp`,`money`,`playerBytes`,`playerBytes2`,`playerFlags`,`position_x`,`position_y`,`position_z`,`map`,`instance_id`,`instance_mode_mask`,`orientation`,`taximask`,`online`,`cinematic`,`totaltime`,`leveltime`,`logout_time`,`is_logout_resting`,`rest_bonus`,`resettalents_cost`,`resettalents_time`,`trans_x`,`trans_y`,`trans_z`,`trans_o`,`transguid`,`extra_flags`,`stable_slots`,`at_login`,`zone`,`death_expire_time`,`taxi_path`,`arenaPoints`,`totalHonorPoints`,`todayHonorPoints`,`yesterdayHonorPoints`,`totalKills`,`todayKills`,`yesterdayKills`,`chosenTitle`,`knownCurrencies`,`watchedFaction`,`drunk`,`health`,`power1`,`power2`,`power3`,`power4`,`power5`,`power6`,`power7`,`latency`,`speccount`,`activespec`,`exploredZones`,`equipmentCache`,`ammoId`,`knownTitles`,`actionBars`,`deleteInfos_Account`,`deleteInfos_Name`,`deleteDate`) VALUES
(1000000,1,'CTrigger1',7,8,1,80,0,2137455906,0,0,0,'5804.86','626.041','647.644',571,0,0,0,'0 0 0 0 0 0 0 0 0 0 0 0 0 0',0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,8,4395,0,NULL,25,31685,0,0,36,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL),
(2000000,1,'CTrigger2',7,8,1,80,0,2137455906,0,0,0,'5804.86','626.041','647.644',571,0,0,0,'0 0 0 0 0 0 0 0 0 0 0 0 0 0',0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,8,4395,0,NULL,25,31685,0,0,36,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL);

CREATE TABLE IF NOT EXISTS `character_transfer`(
  `GUID` INT(11) NOT NULL DEFAULT 0,
  `PLAYER_ACCOUNT` INT(11) NOT NULL DEFAULT 0,
  `GM_ACCOUNT` INT(11) NOT NULL DEFAULT 0,
  `DUMP_ID` INT(11) NOT NULL,
   PRIMARY KEY (`GUID`,`DUMP_ID`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

ALTER TABLE `character_transfer`
DROP PRIMARY KEY,
ADD PRIMARY KEY (`GUID`);