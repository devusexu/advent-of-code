<?php

require('../helper.php');
const FILE_NAME = 'input';

function solve() {
  if (!file_exists(FILE_NAME)) {
    throw new Exception("File " . FILE_NAME . " not found");
  }

  $myfile = fopen(FILE_NAME, "r");

  $inputsArr = [];
  $numberPositionArr = [];
  
  while (!feof($myfile)) {
    // get one line per loop
    $input = fgets($myfile);

    // capture numbers of this line
    $numbers = [];

    if(preg_match_all('/\d+/', $input, $matches, PREG_OFFSET_CAPTURE)) {
      foreach($matches[0] as $match) {
        $numbers[] = [
          'number' => $match[0],
          'index' => $match[1],
          'digits' => strlen($match[0])
        ];
      }
    }


    $charArr = str_split($input);
    $numberPosition = [];
    foreach($numbers as $number) {
      // store information of number in the position of first digit 
      $charArr[$number['index']] = $number;
      // store number index of each row so we don't have to loop through all indices
      $numberPosition[] = $number['index'];
    }

    // store a single row into $inputsArr
    $inputsArr[] = $charArr;
    // store number index info of this row into $numberPositionArr
    $numberPositionArr[] = $numberPosition;
  }

  $numberOfRows =  count($inputsArr);
  $numberOfColumns = count($inputsArr[0]) - 1; // last character is \n

  $partNumberSum = 0;

  for ($i = 0; $i < $numberOfRows; $i++) {
    // this row has numbers
    if(!empty($numberPositionArr[$i])) {
      // for each number position
      foreach ($numberPositionArr[$i] as $position) {
        $partNumberSum += isAdjacentToSymbol($inputsArr, $i, $position, $numberOfRows, $numberOfColumns) ? (int)$inputsArr[$i][$position]['number'] : 0;
      }
    }
  }
  fclose($myfile);
  return $partNumberSum;
}

function isAdjacentToSymbol($inputsArr, $row, $column, $numberOfRows, $numberOfColumns) {
  // check the surrounding characters
  // $inputsArr[$row][$column] = ['number =>, 'index' =>, 'digits']
  // check from row: row - 1 ~ row + 1; column: index - 1 ~ index + digits
  $rowStart = $row - 1;
  $rowEnd = $row + 1;
  $columnStart = $column - 1;
  $columnEnd = $column + $inputsArr[$row][$column]['digits'];

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

  // loop through the surrounding positions
  for ($i = $rowStart; $i <= $rowEnd; $i++) {
    for ($j = $columnStart; $j <= $columnEnd; $j++) {
      $subject = $inputsArr[$i][$j];

      // if this is the whole array i.e. ['number =>, 'index' =>, 'digits'], extract the first digit of the number as subject
      if(!is_string($inputsArr[$i][$j])) {
        $subject = $inputsArr[$i][$j]['number'][0];
      }

      // this character is not a dot or a single number
      if (preg_match('/[^.\d]/', $subject)) {
        return true;
      } 
    }
  }
  // symbol not found
  return false;
}


try {
  $answer = solve();
  dd($answer);
} catch (Exception $e) {
  echo 'Error: ' . $e->getMessage();
}