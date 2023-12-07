<?php 

require('../helper.php');
const FILE_NAME = 'input';

function solve() {
  if (!file_exists(FILE_NAME)) {
    throw new Exception("File " . FILE_NAME . " not found");
  }

  $rewardsArray= []; // [1 => [2, 3, 4, 5], 2 => [3, 4]]
  $fileHandle = fopen(FILE_NAME, 'r');

  while(!feof($fileHandle)) {
    $line = fgets($fileHandle);
    $lineData = getLineData($line);
    // to handle the last line: \n
    if (is_null($lineData)) {
      continue;
    }
    $rewardIDs = getRewardIDs($lineData);
    $rewardsArray[$rewardIDs['id']] = $rewardIDs['rewardIDs'];
  }

  foreach($rewardsArray as  $index => $reward) {
    recursiveCount($index, $rewardsArray);
  }
  return;
}

function recursiveCount($index, $rewardsArray) {
  // every time this function is run, cards++
  global $cards;
  $cards++;

  $indices = $rewardsArray[$index];

  // for every card, run this function again(get reward ids, run this on each of them, repeat)
  foreach($indices as $i) {
    recursiveCount($i, $rewardsArray);
  }
  
  return;
}

function getLineData($line) {
  $cardID = 0;

  if (preg_match('/Card\s+(\d+)/', $line, $matches)) {
    $cardID = (int)$matches[1];
  } else {
    return null;
  }

  $winningNumbers = getWinningNumbers($line);
  $yourNumbers = getYourNumbers($line);

  return [
    'id' => $cardID,
    'winning' => $winningNumbers,
    'yours' => $yourNumbers
  ];
}

function getRewardIDs($lineData) {
  [
    'id' => $id, 
    'winning' => $winningNumbers, 
    'yours' => $yourNumbers
  ] = $lineData;

  $winningNumbersSet = getSet($winningNumbers);

  $points = 0;

  forEach($yourNumbers as $number) {
    // if your number exists in the set
    if($winningNumbersSet[$number]) {
      $points++;
    }
  }
  
  $rewardIDs = [];
  for ($i = 1; $i <= $points; $i++) {
    $rewardIDs[] = $id + $i;
  }

  return [
    'id' => $id,
    'rewardIDs' => $rewardIDs
  ];
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
  $cards = 0;
  solve();
  dd($cards);
} catch (Exception $e) {
  echo "Error " . $e->getMessage();
}