Character Migration Tool
========

v12 >> WOTLK (3.3.5a) >> WOTLK (3.3.5a)

Translated to Spanish, English, French, German and Russian.

Screenshots
========
[![login form](https://raw.githubusercontent.com/masterking32/web-character-migration-tool/master/Screenshots/login.jpg)](https://raw.githubusercontent.com/masterking32/web-character-migration-tool/master/Screenshots/userpanel-1.jpg)[![User Panel 1](https://raw.githubusercontent.com/masterking32/web-character-migration-tool/master/Screenshots/userpanel-1.jpg)](https://raw.githubusercontent.com/masterking32/web-character-migration-tool/master/Screenshots/import-step1.jpg)[![login form](https://raw.githubusercontent.com/masterking32/web-character-migration-tool/master/Screenshots/import-step1.jpg)](https://raw.githubusercontent.com/masterking32/web-character-migration-tool/master/Screenshots/import-step1.jpg)


Requeriments
========
PHP >= 5.3.X and MySQL >= 5.1.X / MariaDB

PHP: enable SOAP module

World Server: enable SOAP module

How to Install
========

Import SQL files to your databases, Inside "SQL+Core's Commit".

Config file "core/init.php" and "_transfer/t_config.php"

In your database Table, 'account_transfer_blacklist' you can edit or add some servers to your transfer black list.

How to Install Addon
========

Download chardump.zip & extract it in your wow game folder. It should looks like ( D:\wotlk\Interface\AddOns\chardump )

Login in game with account, and enable addon.

While in game, simply write in chat /chardump   --> It will generate your character dump file.

When the process is done. Chardump is located in your wow folder D:\wotlk\WTF\Account\YourAccount\SavedVariables\chardump.lua

Go on Migration website, write account details and upload Chardump.lua file ---> DONE!

What is new?
========
Updated to support last version of PHP (5.3-7.x).

Fixed all bugs up to AzerothCore rev d34e5329dfe6df44143219b3b9521610ee142ce2.
