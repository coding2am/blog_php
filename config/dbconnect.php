<?php

define('MYSQL_HOST', 'localhost');
define('MYSQL_DBNAME', 'aprogrammer_blog');
define('MYSQL_USER', 'root');
define('MYSQL_PASSWORD', '');

$options = array(
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
);

$dsn = 'mysql:host=' . MYSQL_HOST . ';dbname=' . MYSQL_DBNAME;
$pdo = new PDO($dsn, MYSQL_USER, MYSQL_PASSWORD, $options);