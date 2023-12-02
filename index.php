<?php

require('Pair.php');

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
  $numberPairs = getNumberPairs($input);
  // concatenate first and last number
  return (int)($numberPairs['first']->getValue() . $numberPairs['last']->getValue());
}


function getNumberPairs($input) {
  $convertTable = [
    '/one|1/' => 1,
    '/two|2/' => 2,
    '/three|3/' => 3,
    '/four|4/' => 4,
    '/five|5/' => 5,
    '/six|6/' => 6,
    '/seven|7/' => 7,
    '/eight|8/' => 8,
    '/nine|9/' => 9
  ];
  // pair stores (index, value)
  $firstNumberPair = new Pair(INF, 0);
  $lastNumberPair = new Pair(-INF, 0);

  forEach($convertTable as $pattern => $number) {
    if (preg_match_all($pattern, $input, $matches, PREG_OFFSET_CAPTURE)) {
      // $matches[0] is simply array destructuring
      forEach($matches[0] as $match) {
        //$match[0]: matching pattern; $match[1]: pattern's starting index
        if($match[1] < $firstNumberPair->getIndex()) {
          $firstNumberPair->setPair($match[1], $number);
        }
        if($match[1] > $lastNumberPair->getIndex()) {
          $lastNumberPair->setPair($match[1], $number);
        }
      }
    }
  }
  
  return [
    'first' => $firstNumberPair, 
    'last' => $lastNumberPair
  ];
}

// for testing functions: dump and die
function dd($var) {
  echo "<pre>";
  var_dump($var);
  echo "</pre>";
  die();
}

$answer = solve();
dd($answer);