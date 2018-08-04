<?php
require("../vendor/autoload.php");

echo getenv('CLEARDB_DATABASE_URL');
$dbopts = parse_url(getenv('CLEARDB_DATABASE_URL'));
print_r($dbopts);

