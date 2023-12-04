<?php

require('../helper.php');
const FILE_NAME = 'input';

function solve() {
  if (!file_exists(FILE_NAME)) {
    throw new Exception("File " . FILE_NAME . " not found");
  }

  $myfile = fopen(FILE_NAME, "r");
  $idSum = 0;

  while (!feof($myfile)) {
    //get one line per loop
    $input = fgets($myfile);
    $idSum += possibleGameID(12, 13, 14, $input);
  }

  fclose($myfile);
  return $idSum;
}

function possibleGameID($redBound, $greenBound, $blueBound, $input) {
  // remove spaces
  $input = str_replace(' ', '', $input);
  // capture id
  $id = preg_match('/Game(\d+)/', $input, $matches) ? $matches[1] : 0;
  // remove prefix i.e. Game *:
  $input = preg_replace('/Game\d+:/', '', $input);

  // capture numbers before each color
  $blueNumbers = preg_match_all('/(\d+)blue/', $input, $matches) ? $matches[1] : 0;
  $greenNumbers = preg_match_all('/(\d+)green/', $input, $matches) ? $matches[1] : 0;
  $redNumbers = preg_match_all('/(\d+)red/', $input, $matches) ? $matches[1] : 0;

  // return id if all colors of cubes are possible for this game, 0 otherwise
  return isGamePossible($blueNumbers, $blueBound) && isGamePossible($greenNumbers, $greenBound) && isGamePossible($redNumbers, $redBound) ? (int)$id : 0;
}

function isGamePossible($cubeNums, $bound) {
  // no numbers captured for this color => 0 makes this game possible since it must be less than bound
  if (!$cubeNums) {
    return true;
  }

  // if numbers captured bigger than bound, this game is impossible
  forEach($cubeNums as $number) {
    if ((int)$number > $bound) {
      return false;
    }
  }

  // otherwise game is possible
  return true;
}

try {
  $answer = solve();
  dd($answer);
} catch (Exception $e) {
  echo 'Error: ' . $e->getMessage();
}