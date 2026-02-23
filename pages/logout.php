<?php
session_start();
session_destroy();
header('Location: /gymbook/pages/login.php');
exit;