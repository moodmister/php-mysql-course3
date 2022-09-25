<?php
require_once('init-mysqli.php');
$schema = fopen('schema.sql', 'r');
$query = fread($schema, filesize('schema.sql'));
$mysqli->query($query);

$mysqli->multi_query($query);

while ($mysqli->next_result()) {}

fclose($query);