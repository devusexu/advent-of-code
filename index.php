<?php

function solve() {
  $answer = 0;
  $myfile = fopen("input", "r") or die("Unable to open file!");

  while (!feof($myfile)) {
    //get one line per loop
    $input = fgets($myfile);
    $answer += getNumber($input);
  }

  fclose($myfile);
  return $answer;
}

function getNumber($input) {
  // concatenate two string then convert to number 
  return (int)(getFirstNumberAsString($input) . getLastNumberAsString($input));
}

function getFirstNumberAsString($input) {
  forEach(str_split($input) as $char) {
    if (is_numeric($char)) {
      return $char;
    }
  }
}

function getLastNumberAsString($input) {
  // reverse then traverse
  forEach(array_reverse(str_split($input)) as $char) {
    if (is_numeric($char)) {
      return $char;
    }
  }
}

$answer = solve();
var_dump($answer);
