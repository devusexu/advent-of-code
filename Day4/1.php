<?php 

require('../helper.php');
const FILE_NAME = 'input';

function solve() {
  if (!file_exists(FILE_NAME)) {
    throw new Exception("File " . FILE_NAME . " not found");
  }

  $totalPoints = 0;
  $fileHandle = fopen(FILE_NAME, 'r');

  while(!feof($fileHandle)) {
    $line = fgets($fileHandle);
    $totalPoints += getLinePoints($line);
  }

  return $totalPoints;
}

function getLinePoints($line) {
  $winningNumbers = getWinningNumbers($line);
  $yourNumbers = getYourNumbers($line);
  $winningNumbersSet = getSet($winningNumbers);

  $points = 0;

  forEach($yourNumbers as $number) {
    // if your number exists in the set
    if($winningNumbersSet[$number]) {
      if($points === 0) {
        $points = 1;
      } else {
        $points *= 2;
      }
    }
  }
  return $points;
}

// create a associative array like set: [number => true]
function getSet($array) {
  $newSet = [];
  forEach($array as $element) {
    $newSet[$element] = true;
  }

  return $newSet;
}

function getWinningNumbers($line) {
  if (preg_match('/((\d+\s+){1,})\|/', $line, $matches)) {
    /*
      1. trim spaces on both end
      2. replace spaces with ','
      3. split string into array with delimiter ','
    */
    return explode(',', preg_replace('/\s+/', ',', trim($matches[1]))); 
  }
}

function getYourNumbers($line) {
  if(preg_match('/\|\s+((\d+\s*){1,})/', $line, $matches)){
    // same as getWinningNumbers()
    return explode(',', preg_replace('/\s+/', ',', trim($matches[1]))); 
  }
}

try {
  $answer = solve();
  dd($answer);
} catch (Exception $e) {
  echo "Error " . $e->getMessage();
}