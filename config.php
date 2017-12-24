<?php

define("DBHOST", "");
define("DBUSER", "");
define("DBPASS", "");
define("DB", "");

$connection = @mysqli_connect(DBHOST, DBUSER, DBPASS, DB) or die("Нет соединения с БД");
mysqli_set_charset($connection, "utf8") or die("Не установлена кодировка соединения");