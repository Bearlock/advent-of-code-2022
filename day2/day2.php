<?php

function prepConditions(array $conditions) : callable {
	return function(string $result) use ($conditions) : array {
		if (in_array($result, array_keys($conditions))) {
			return [true, $conditions[$result]];
		}

		return [false, 0];
	};
}

function getScore(string $result, callable $scoreFunction) : int {
	return $scoreFunction($result)[1];
}

const TIE = 3;
const WIN = 6;

const ROCK = 1;
const PAPER = 2;
const SCISSORS = 3;

$winConditions = ['A Y' => PAPER + WIN, 'B Z' => SCISSORS + WIN, 'C X' => ROCK + WIN];
$tieConditions = ['A X' => ROCK + TIE, 'B Y' => PAPER + TIE, 'C Z' => SCISSORS + TIE];
$lossConditions = ['A Z' => SCISSORS, 'B X' => ROCK, 'C Y' => PAPER];
$totalPoints = 0;

$winConditionsPartTwo = ['A Z' => PAPER + WIN, 'B Z' => SCISSORS + WIN, 'C Z' => ROCK + WIN];
$tieConditionsPartTwo = ['A Y' => ROCK + TIE, 'B Y' => PAPER + TIE, 'C Y' => SCISSORS + TIE];
$lossConditionsPartTwo = ['A X' => SCISSORS, 'B X' => ROCK, 'C X' => PAPER];
$totalPointsPartTwo = 0;

$isWin = prepConditions($winConditions);
$isTie = prepConditions($tieConditions);
$isLoss = prepConditions($lossConditions);

$isWinPartTwo = prepConditions($winConditionsPartTwo);
$isTiePartTwo = prepConditions($tieConditionsPartTwo);
$isLossPartTwo = prepConditions($lossConditionsPartTwo);

$fileHandle = fopen('day2.txt', 'r');
if ($fileHandle) {
	while (($line = fgets($fileHandle)) !== false) {
		$trimLine = trim($line);
		$totalPoints += getScore($trimLine, $isWin) + getScore($trimLine, $isTie) + getScore($trimLine, $isLoss);
		$totalPointsPartTwo += getScore($trimLine, $isWinPartTwo) + getScore($trimLine, $isTiePartTwo) + getScore($trimLine, $isLossPartTwo);
	}

	var_dump($totalPoints);
	var_dump($totalPointsPartTwo);
	fclose($fileHandle);
}
