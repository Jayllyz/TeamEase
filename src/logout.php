<?php
session_start();
session_destroy();
// Refresh 0.1s
header("location: index.php");
