<?php
    session_start();
    function randomText($length) {
        $key = "";
        $pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
        for($i = 0; $i < $length; $i++)
            $key .= $pattern{rand(0, 35)};

        return $key;
    }

    $_SESSION['CaptchaText'] = randomText(5);
    $Image     = imagecreatefromgif("bgcaptcha.gif");
    if(!$Image)
        $Image = imagecreatetruecolor(150, 30);
    $TextColor     = imagecolorallocate($Image, 0, 0, 0);
    imagestring($Image, 4, 2, 0, $_SESSION['CaptchaText'], $TextColor);

    header("Content-type: image/gif");
    imagegif($Image);
?>