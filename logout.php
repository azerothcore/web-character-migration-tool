<?php

    session_start();
    session_unset();
    session_destroy();

    include('_transfer/language.php');
    include('template/t_header.php'); ?>

<table height = "250"><tr><td align = "center"><h1 class = "caption"><?php echo $write[41];?> <a href = "index.php"></h1><font color = "black"><?php echo $write[42];?></a></td></tr></table>
<?php include ('template/t_footer.php'); ?>