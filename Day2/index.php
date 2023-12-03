<?php

require('../helper.php');
const FILE_NAME = 'input';

function solve() {
  if (!file_exists(FILE_NAME)) {
    throw new Exception("File " . FILE_NAME . " not found");
  }

  $myfile = fopen(FILE_NAME, "r");
  $totalSum = 0;

  while (!feof($myfile)) {
    //get one line per loop
    $input = fgets($myfile);
    $totalSum += validGameID(12, 13, 14, $input);
  }

  fclose($myfile);
  return $totalSum;
}

function validGameID($red, $green, $blue, $input) {
  $input = str_replace(' ', '', $input);
  $id = preg_match('/Game(\d+)/', $input, $matches) ? $matches[1] : 0;
  $input = preg_replace('/Game\d+:/', '', $input);

  $blueNumbers = preg_match_all('/(\d+)blue/', $input, $matches) ? $matches[1] : 0;
  $greenNumbers = preg_match_all('/(\d+)green/', $input, $matches) ? $matches[1] : 0;
  $redNumbers = preg_match_all('/(\d+)red/', $input, $matches) ? $matches[1] : 0;

  return isGameValid($blueNumbers, $blue) && isGameValid($greenNumbers, $green) && isGameValid($redNumbers, $red) ? (int)$id : 0;
}

function isGameValid($cubeNums, $bound) {
  if (!$cubeNums) {
    return true;
  }

  forEach($cubeNums as $number) {
    if ((int)$number > $bound) {
      return false;
    }
  }

  return true;
}

try {
  $answer = solve();
  dd($answer);
} catch (Exception $e) {
  echo 'Error: ' . $e->getMessage();
}