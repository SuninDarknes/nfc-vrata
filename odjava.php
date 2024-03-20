<?php

session_start();

session_destroy();

header("Location: prijava.php");
exit;