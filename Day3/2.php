<?php

require('../helper.php');
const FILE_NAME = 'input';

function solve() {
  if (!file_exists(FILE_NAME)) {
    throw new Exception("File " . FILE_NAME . " not found");
  }

  $fileHandle = fopen(FILE_NAME, "r");
  $numbersInfoArray = [];
  $grid = [];

  while (!feof($fileHandle)) {
    $line = fgets($fileHandle);
    $lineWithoutSymbol = getLineWithoutSymbol($line);
    $numbersInfoArray[] = getNumbersInfo($lineWithoutSymbol);
    $grid[] = $lineWithoutSymbol;
  }

  $numberOfRows = count($grid);
  $numberOfColumns = strlen($grid[0]); // use strlen since every line is still a string

  $pairs = getGearNumberPair($grid, $numbersInfoArray, $numberOfRows, $numberOfColumns);
  $gearRatioSum = getGearRatioSum($pairs);

  return $gearRatioSum;
}

function getGearRatioSum($pairs) {
  $sum = 0;

  foreach($pairs as $pair) {
    $numbers = array_values($pair);

    if(count($numbers) === 2) {
      $sum += $numbers[0] * $numbers[1];
    }
  }

  return $sum;
}

function getGearNumberPair($grid, $numbersInfoArray, $numberOfRows, $numberOfColumns) {

  $pairs = [];

  //  loop through each row
  foreach($numbersInfoArray as $row => $rowNumbersInfo) {
    // check this row has numbers
    if (!empty($rowNumbersInfo)) {
      // for every number's info, check whether it's adjacent to gear
      foreach($rowNumbersInfo as $numberInfo) {
        $gearPositions = getNearGearPositions($grid, $row, $numberInfo, $numberOfRows, $numberOfColumns);
        // this number is adjacent to a gear
        if (!is_null($gearPositions)) {
          // a number might near multiple gears
          foreach($gearPositions as $gearPosition) {
            // use serialize to generate unique key representation for each position since it's a fairly simple array
            $key = serialize($gearPosition);
            // push this number into each position key => $pairs = ['serialized position' => [number1, number2, ...], 'serialized position'=> ...]
            $pairs[$key][] = (int)$numberInfo['number'];
          }
        }
      }
    }
  }

  return $pairs;
}

function getNearGearPositions($grid, $row, $numberInfo, $numberOfRows, $numberOfColumns) {
  // check surrounding positions
  $rowStart = $row - 1;
  $rowEnd = $row + 1;

  $column = $numberInfo['index'];
  $columnStart = $column - 1;
  $columnEnd = $column + $numberInfo['digits'];

  // whenever encountering the border, adjust the index
  if ($rowStart === -1) {
    $rowStart = 0;
  }
  if ($rowEnd === $numberOfRows) {
    $rowEnd = $numberOfRows - 1;
  }
  if ($columnStart === -1) {
    $columnStart = 0;
  }
  if ($columnEnd === $numberOfColumns) {
    $columnEnd = $numberOfColumns - 1;
  }


  $gearPositions = [];

  // loop through the surrounding positions
  for ($i = $rowStart; $i <= $rowEnd; $i++) {
    for ($j = $columnStart; $j <= $columnEnd; $j++) {
      $subject = $grid[$i][$j];

      if (preg_match('/\*/', $subject)) {
        $gearPositions[] = [$i, $j];
      } 
    }
  }

  return !empty($gearPositions) ? $gearPositions : null;
}

function getLineWithoutSymbol($line) {
  return preg_replace('/[^.\d*]/', '.', $line);
}

function getNumbersInfo($line) {
  $numbersInfo = [];

  if(preg_match_all('/\d+/', $line, $matches, PREG_OFFSET_CAPTURE)) {
    foreach($matches[0] as $match) {
      $numbersInfo[] = [
        'number' => $match[0],
        'index' => $match[1],
        'digits' => strlen($match[0])
      ];
    }
  }
  return $numbersInfo;
}

try {
  $answer = solve();
  dd($answer);
} catch (Exception $e) {
  echo "Error ". $e->getMessage();
}

