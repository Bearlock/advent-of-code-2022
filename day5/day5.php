<?php

const CHAR_GAP = 4;
const LTR_BOUNDARY = 33;
const BOTTOM_BOUNDARY = 8;
const DIRECTIVE_START_LINE = 10;

function parsePackages(array $stacks, string $line) : array {
	$stackIndex = 0;
	$charIndex = 1;

	while ($charIndex <= LTR_BOUNDARY) {
		$char = trim(substr($line, $charIndex, 1));

		if (!empty($char)) {
			$stacks[$stackIndex][] = $char;
		}

		$stackIndex++;
		$charIndex = $charIndex + CHAR_GAP;
	}

	return $stacks;
}

function popPackages(array $stacks, int $sourceIndex, int $destIndex, int $count) :  array {
	while ($count > 0) {
		$stacks[$destIndex][] = array_pop($stacks[$sourceIndex]);
		$count--;
	}

	return $stacks;
}

function getDirectives(string $line) : array {
	preg_match_all('!\d+!', $line, $matches);
	[$moveCount, $sourceArray, $destArray] = $matches[0];

	return [$moveCount, $sourceArray - 1, $destArray - 1];
}

function movePackagesOrdered(array $stacks, int $sourceIndex, int $destIndex, int $count) : array {
	// We want to start grabbing items from the length of the array - the number
	// of items we need. This'll also be useful for updating the array that we're
	// taking items from; we'll need to grab all items _up until_ the offset.
	$offset = (count($stacks[$sourceIndex]) - $count);

	$stacks[$destIndex] = array_merge(
		$stacks[$destIndex], array_slice($stacks[$sourceIndex], $offset, $count)
	);

	$stacks[$sourceIndex] = array_slice($stacks[$sourceIndex], 0, $offset);

	return $stacks;
}

function getStackTops(array $stacks) : string {
	return array_reduce($stacks, function ($acc, $item) {
		$acc = $acc . array_pop($item);
		return $acc;
	}, '');
}

$fileHandle = fopen('day5.txt', 'r');

$lineCount = 0;
$stacks = [[], [], [], [], [], [], [], [], []];
$stackDupe = [];
$flipped = false;

if ($fileHandle) {
	while (($line = fgets($fileHandle)) !== false) {
		$trimLine = rtrim($line);

		// Read 8 lines, 'cause that's the content of the pic
		if ($lineCount < BOTTOM_BOUNDARY) {
			$stacks = parsePackages($stacks, $trimLine);
		}

		if ($lineCount >= DIRECTIVE_START_LINE) {
			// We need the arrays to be flipped 'cause we technically read them in backwards
			if ($flipped === false) {
				$stacks = array_map(function ($elem) {
					return array_reverse($elem);
				}, $stacks);

				$flipped = true;
				$stackDupe = array_values($stacks);
			}

			[$moveCount, $sourceIndex, $destIndex] = getDirectives($trimLine);
			$stackDupe = movePackagesOrdered($stackDupe, $sourceIndex, $destIndex, $moveCount);
			$stacks = popPackages($stacks, $sourceIndex, $destIndex, $moveCount);
		}

		$lineCount++;
	}

	var_dump(getStackTops($stacks));
	var_dump(getStackTops($stackDupe));
}
