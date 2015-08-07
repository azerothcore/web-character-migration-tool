<?php


    ob_start();
    session_start();

    include_once('_transfer/t_config.php');

    if(isset($_SESSION['loged'])) {
        Header('Location: playerside.php');
    } else {
        include_once('_transfer/language.php');

        if(!isset($_POST['username']) || !isset($_POST['username'])) {
            include_once('template/t_header.php');
            $reason = "<font color=\"darkred\">". $write[2] ."</font><br>";
        } else if($captchaEnable != 0 && ($_SESSION['CaptchaText'] != $_POST['CaptchaText'])) {
            include_once('template/t_header.php');
            $reason = "<font color=\"darkred\">Wrong Captcha code!</font><br>";
        } else {
            $username       = strtoupper(addslashes($_POST['username']));
            $SHA1Password   = SHA1Password($username, strtoupper(addslashes($_POST['password'])));

            $connection     = mysql_connect($AccountDBHost, $DBUser, $DBPassword);
            mysql_select_db($AccountDB, $connection);
            mysql_set_charset('utf8',$connection);

            $query  = mysql_query("SELECT `id`,`username` FROM `account` WHERE `username` = \"". _Y($username) ."\" AND `sha_pass_hash` = \"". _Y($SHA1Password) ."\";", $connection) or die(mysql_error());
            $result = mysql_fetch_array($query);
            mysql_close($connection);

            if($result['username'] == "") {
                include_once('template/t_header.php');
                $reason = "<font color=\"darkred\">Wrong Password!</font><br>";
            } else if($result['username']) {
                $_SESSION['loged']  = $SHA1Password;
                $_SESSION['id']     = $result['id'];
                $_SESSION['user']   = $result['username'];
                Header('Location: playerside.php');
            }
        }
?>
<img src = "template/images/logon_vrch.jpg">
<table align = "center" style = "background : url(template/images/logon_obsah.jpg); width : 689px; height: 289px;">
<tr><th>
    <table width = "255" align = "center" valign = "top">
    <tr><td class = "caption"><?php echo $write[1]; ?></td></tr>
    <tr><td style = "font-face : Times New Roman; color : #826230; font-size : 12px; height : 65px";><?php echo $reason; ?></td></tr>
        <form action = "<?php echo $_SERVER['PHP_SELF'] ?>" method = "POST" id = "login">
            <tr><td><?php echo $write[3]; ?><br><input type = "text" class = "EnterData" name = "username" onkeydown = "keyDown(event)"></td></tr>
            <tr><td><?php echo $write[4]; ?><br><input type = "password" class = "EnterData" name = "password" onkeydown = "keyDown(event)"></td></tr>
            <?php if($captchaEnable != 0) echo "
            <tr>
                <td>Captcha: <img src = \"captcha.php\" height = \"22\">
                <input style = \"formul\" name = \"CaptchaText\" type = \"text\" size = \"6\" maxlength = \"8\" onkeydown = \"keyDown(event)\"></td>
            </tr>"?>
            <tr><td align = center><a href = '#' onClick = "document.getElementById('login').submit();" class = "login" ><?php echo $write[1]; ?></a></td></tr>
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
<?php include('template/t_footer.php');
    }

    ob_end_flush();

    function SHA1Password($username, $password) {
        return SHA1($username .':'. $password);
    }

    function _Y($A) {
        return get_magic_quotes_gpc() ? stripslashes(mysql_real_escape_string($A)) : mysql_real_escape_string($A);
    }
?>