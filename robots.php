<?php
header("Content-Type: text/plain");

$hosts = [
  ["name" => "didev.bs", "code" => "bs"],
  ["name" => "discotechecremona.it", "code" => "cr"]
];

foreach ($hosts as $h) {
  if (stristr($_SERVER["HTTP_HOST"], $h["name"]) !== false) {
    $code = $h["code"];
    echo @file_get_contents("robots/robots.$code.txt");
  }
}