<?php

$fileHandle = fopen('day1.txt', 'r');

if ($fileHandle) {
	$totalCalories = [];
	$currentCalories = 0;

	while (($line = fgets($fileHandle)) !== false) {
		if ($line != "\n") {
			$currentCalories += intval($line);
		} else {
			$totalCalories[] = $currentCalories;
			$currentCalories = 0;
		}
	}

	$totalCalories[] = $currentCalories;

	/* First answer */
	var_dump(max($totalCalories));

	// -----------------------------------------------
	// Start part 2
	arsort($totalCalories);
	$topThree = array_slice($totalCalories, 0, 3);

	$totalThree = array_reduce($topThree, function($acc, $elem) {
		return $acc + $elem;
	}, 0);

	/* Second answer */
	var_dump($totalThree);

	fclose($fileHandle);
}
