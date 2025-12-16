<?php
// logout.php
require 'config.php'; // config.php has session_start()
session_unset();
session_destroy();
header('Location: ../home.php');
exit();
