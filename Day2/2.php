<?php

require('../helper.php');
const FILE_NAME = 'input';

function solve() {
  if (!file_exists(FILE_NAME)) {
    throw new Exception("File " . FILE_NAME . " not found");
  }

  $myfile = fopen(FILE_NAME, "r");
  $powerSum = 0;

  while (!feof($myfile)) {
    //get one line per loop
    $input = fgets($myfile);
    $powerSum += powerOfSet($input);
  }

  fclose($myfile);
  return $powerSum;
}

function powerOfSet($input) {
  // remove spaces
  $input = str_replace(' ', '', $input);

  // capture numbers before each color
  $blueNumbers = preg_match_all('/(\d+)blue/', $input, $matches) ? $matches[1] : 0;
  $greenNumbers = preg_match_all('/(\d+)green/', $input, $matches) ? $matches[1] : 0;
  $redNumbers = preg_match_all('/(\d+)red/', $input, $matches) ? $matches[1] : 0;
  
  // multiply together the max number of each color of cubes
  return max($blueNumbers) * max($greenNumbers) * max($redNumbers);
}

try {
  $answer = solve();
  dd($answer);
} catch (Exception $e) {
  echo 'Error: ' . $e->getMessage();
}