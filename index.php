<?php
/*
 * Copyright (C) 2019+ MasterkinG32 <https://masterking32.com>
 * Copyright (C) 2017+ AzerothCore <www.azerothcore.org>, released under GNU GPL v2 license: http://github.com/azerothcore/azerothcore-wotlk/LICENSE-GPL2
 * Copyright (C) 2008-2016 TrinityCore <http://www.trinitycore.org/>
 * Copyright (C) 2005-2009 MaNGOS <http://getmangos.com/>
*/

require_once 'core/init.php';
require_once '_transfer/t_config.php';
require_once '_transfer/language.php';
require_once 'template/t_header.php';

$user = new User();
if($user->isLoggedIn()) {
    Redirect::to('playerside.php');
}

if(Input::exists()) {
        $validate   = new Validation();
        $validation = $validate->check($_POST, array(
            'username' => array('required' => true),
            'password' => array('required' => true)
        ));

        if($validation->passed()) {
            $user  = new User();
            $login = $user->login(Input::get('username'), Input::get('password'));

            if($login) {
                Redirect::to('playerside.php');
            }
        } else {
            foreach($validation->errors() as $error) {
                echo $error, '<br>';
            }
        }
}

// START OF HELL DOWN BELOW...

?>
<img src = "template/images/logon_vrch.jpg">
<table align = "center" style = "background : url(template/images/logon_obsah.jpg); width : 689px; height: 289px;">
<tr><th>
    <table width = "255" align = "center" valign = "top">
    <tr><td class = "caption"><?php echo $write[1]; ?></td></tr>
    <tr><td style = "font-face : Times New Roman; color : #826230; font-size : 12px; height : 65px";></td></tr>
        <form action="" method="POST" id="login">
            <tr><td><?php echo $write[3]; ?><br><input type = "text" class = "EnterData" name = "username" onkeydown = "keyDown(event)"></td></tr>
            <tr><td><?php echo $write[4]; ?><br><input type = "password" class = "EnterData" name = "password" onkeydown = "keyDown(event)"></td></tr>
            <?php if($captchaEnable != 0) echo "
            <tr>
                <td>Captcha: <img src = \"captcha.php\" height = \"22\">
                <input style = \"formul\" name = \"CaptchaText\" type = \"text\" size = \"6\" maxlength = \"8\" onkeydown = \"keyDown(event)\"></td>
            </tr>"?>
            <tr><td align = center><a href = '#' onClick = "document.getElementById('login').submit();" class = "login" ><?php echo $write[1]; ?></a></td></tr>

        <input type="hidden" name="token" value="<?=Token::generate();?>">
        </form>
    </table>
</th></tr>
</table>
<script type="text/javascript">
function keyDown(event)
{
    var key = event.keyCode;
    if (key == 13)
    {
        document.getElementById('login').submit();
    }
}
</script>
<img src = "template/images/logon_spodek.jpg">
<?php include('template/t_footer.php'); ?>