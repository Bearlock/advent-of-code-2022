<?php

const PACKET_LENGTH = 4;
const MESSAGE_LENGTH = 14;

function getMarkerIndex(array $message, int $length) {
	$index = 0;
	$uniq = [];

	while ($index < count($message)) {
		if (count($uniq) === $length) {
			if (count(array_unique($uniq)) === $length) {
				return $index;
			}
			array_shift($uniq);
		}

		$uniq[] = $message[$index];
		$index++;
	}
}

$fileHandle = fopen('day6.txt', 'r');

if ($fileHandle) {
	$puzzleArray = str_split(trim(fgets($fileHandle)));
	var_dump(getMarkerIndex($puzzleArray, PACKET_LENGTH));
	var_dump(getMarkerIndex($puzzleArray, MESSAGE_LENGTH));
}
