<?php

const UPPER_MOD = 26;

[$priorities] = array_reduce(range('a', 'z'), function ($acc, $item) {
	$acc[0][$item] = $acc[1];
	$acc[0][strtoupper($item)] = ($acc[1] + UPPER_MOD);
	$acc[1] = $acc[1] + 1;
	return $acc;
}, [[], 1]);

$totalPriority = 0;
$fileHandle = fopen('day3.txt', 'r');

$threeGroup = [];
$totalBadgePriority = 0;

if ($fileHandle) {
	while (($line = fgets($fileHandle)) !== false) {
		/* Part 1 */
		$trimmedLine = trim($line);
		$tokens = str_split($trimmedLine);
		[$firstCompartment, $secondCompartment] = array_chunk($tokens, count($tokens) / 2);

		$duplicate = array_values(array_intersect($firstCompartment, $secondCompartment))[0];
		$totalPriority += $priorities[$duplicate];

		/* Part 2 */
		$threeGroup[] = $tokens;
		if (count($threeGroup) === 3) {
			[$firstGroupTokens, $secondGroupTokens, $thirdGroupTokens] = $threeGroup;

			$badge = array_values(array_intersect($firstGroupTokens, $secondGroupTokens, $thirdGroupTokens))[0];
			$totalBadgePriority += $priorities[$badge];

			$threeGroup = [];
		}
	}

	var_dump($totalPriority);
	var_dump($totalBadgePriority);
}
