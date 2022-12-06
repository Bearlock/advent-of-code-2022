<?php

function cmp($a1, $a2) {
	if (count($a1) == count($a2)) {
		return 0;
	}
	return (count($a1) < count($a2)) ? -1 : 1;
}

function toRange(string $rangeNotation) {
	[$start, $end] = explode('-', $rangeNotation);
	return range($start, $end);
}

$fileHandle = fopen('day4.txt', 'r');
$fullyContainsCount = 0;
$partialContainsCount = 0;

if ($fileHandle) {
	while (($line = fgets($fileHandle)) !== false) {

		$ranges = array_map(function($elem) {
			return toRange($elem);
		}, explode(',', trim($line)));

		usort($ranges, 'cmp');

		[$shorter, $longer] = $ranges;

		$fullyContainsCount = count(array_intersect($shorter, $longer)) === count($shorter) ? $fullyContainsCount + 1 : $fullyContainsCount;
		$partialContainsCount = count(array_intersect($shorter, $longer)) > 0 ? $partialContainsCount + 1 : $partialContainsCount;
	}

	var_dump($fullyContainsCount);
	var_dump($partialContainsCount);

	fclose($fileHandle);
}
