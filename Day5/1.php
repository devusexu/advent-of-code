<?php

require("../helper.php");
const FILE_NAME = 'input';

function solve() {
  if (!file_exists(FILE_NAME)) {
    throw new Exception("File " . FILE_NAME . " not found.");
  }

  $file = file(FILE_NAME);
  $seeds = getSeeds($file);
  $maps = getMaps($file);
  
  $results = [];
  foreach($seeds as $seed) {
    // pass a seed into a map, get the result and pass it into next map 
    $results[] = array_reduce($maps, function ($intermediate, $map) {
      return mapping($intermediate, $map);
    }, $seed);  
  }

  // get the smallest location numbers
  return min($results);
}

// a map consists of several pairs of [drs, srs, rl]
function mapping($source, $mapPairs) {
  foreach($mapPairs as $pair) {
    // extract variables
    [
      'drs' => $drs,
      'srs' => $srs,
      'rl' => $rl
    ] = $pair;

    // calculate the result directly because creating the whole map would cost too much memory
    if ($source >= $srs && $source <= $srs + $rl - 1) {
      return $source - $srs + $drs;
    }
  }
  // $source is not in the map, return the same number
  return $source;
}

function getSeeds($file) {
  $seeds = [];
  // get the seed numbers and turn strings into numbers
  if (preg_match_all('/\s\d+/', $file[0], $matches)) {
    foreach($matches[0] as $seed) {
      $seeds[] = (int)$seed;
    }
  }
  return $seeds;
}

function getMaps($file) {
  $file = array_slice($file, 2);
  $mapID = 0;
  $mapsArray = [];

  foreach ($file as $index => $line) {
    if (preg_match('/map/', $line)) {
      continue;
    }
    elseif ($line === "\n") {
      $mapID++;
      continue;
    }
    else {
      if(preg_match_all('/\d+/', $line, $matches)) {
        // drs: Destination Range Start
        // srs: Source Range Start
        // rl: Range Length
        $mapsArray[$mapID][] = [
          'drs' => (int)$matches[0][0],
          'srs' => (int)$matches[0][1],
          'rl' => (int)$matches[0][2]
        ];
      }
    }
  }
  // mapsArray consists of maps, each map consists of pairs of [drs, srs, rl]
  return $mapsArray;
}

try {
  $answer = solve();
  dd($answer);
} catch (Exception $e) {
  echo "Error: " . $e->getMessage();
}