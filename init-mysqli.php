<?php
$config = array(
  'database' => 'libraries',
  'username' => 'libraries',
  'password' => 'libraries',
  'host' => 'localhost'
);

$mysqli = new mysqli($config['host'], $config['username'], $config['password'], $config['database'], '3306');

if ($mysqli->connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli->connect_error;
  die;
}
$mysqli->autocommit(true);
