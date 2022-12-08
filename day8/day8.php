<?php

function isTreeVisible(array $grove, int $rowIndex, int $columnIndex) {
	$north = isTreeVisibleNorth($grove[$rowIndex][$columnIndex], $rowIndex - 1, $columnIndex, $grove);
	if ($north) {
		return $north;
	}

	$south = isTreeVisibleSouth($grove[$rowIndex][$columnIndex], $rowIndex + 1, $columnIndex, $grove);
	if ($south) {
		return $south;
	}

	$east = isTreeVisibleEast($grove[$rowIndex][$columnIndex], $rowIndex, $columnIndex - 1, $grove);
	if ($east) {
		return $east;
	}

	return isTreeVisibleWest($grove[$rowIndex][$columnIndex], $rowIndex, $columnIndex + 1, $grove);
}

function isTreeVisibleNorth($tree, $rowIndex, $columnIndex, $grove, $visible = true) {
	$boundary = 0;

	if ($visible === false) {
		return $visible;
	}

	if ($rowIndex === $boundary) {
		return $tree > $grove[$boundary][$columnIndex];
	}

	return isTreeVisibleNorth($tree, $rowIndex - 1, $columnIndex, $grove, $tree > $grove[$rowIndex][$columnIndex]);
}

function isTreeVisibleSouth($tree, $rowIndex, $columnIndex, $grove, $visible = true) {
	$boundary = count($grove) - 1;

	if ($visible === false) {
		return $visible;
	}

	if ($rowIndex === $boundary) {
		return $tree > $grove[$boundary][$columnIndex];
	}

	return isTreeVisibleSouth($tree, $rowIndex + 1, $columnIndex, $grove, $tree > $grove[$rowIndex][$columnIndex]);
}

function isTreeVisibleEast($tree, $rowIndex, $columnIndex, $grove, $visible = true) {
	$boundary = 0;

	if ($visible === false) {
		return $visible;
	}

	if ($columnIndex === $boundary) {
		return $tree > $grove[$rowIndex][$boundary];
	}

	return isTreeVisibleEast($tree, $rowIndex, $columnIndex - 1, $grove, $tree > $grove[$rowIndex][$columnIndex]);
}

function isTreeVisibleWest($tree, $rowIndex, $columnIndex, $grove, $visible = true) {
	$boundary = count($grove) - 1;

	if ($visible === false) {
		return $visible;
	}

	if ($columnIndex === $boundary) {
		return $tree > $grove[$rowIndex][$boundary];
	}

	return isTreeVisibleWest($tree, $rowIndex, $columnIndex + 1, $grove, $tree > $grove[$rowIndex][$columnIndex]);
}

function getScenicScore($grove, $rowIndex, $columnIndex) {
	$north = getScenicScoreNorth($grove[$rowIndex][$columnIndex], $rowIndex - 1, $columnIndex, $grove);
	$south = getScenicScoreSouth($grove[$rowIndex][$columnIndex], $rowIndex + 1, $columnIndex, $grove);
	$east = getScenicScoreEast($grove[$rowIndex][$columnIndex], $rowIndex, $columnIndex - 1, $grove);
	$west = getScenicScoreWest($grove[$rowIndex][$columnIndex], $rowIndex, $columnIndex + 1, $grove);

	return $north * $south * $east * $west;
}

function getScenicScoreNorth($tree, $rowIndex, $columnIndex, $grove, $score = 0) {
	$boundary = 0;
	$score = $score + 1;

	if ($rowIndex === $boundary) {
		return $score;
	}

	if ($tree <= $grove[$rowIndex][$columnIndex]) {
		return $score;
	}

	return getScenicScoreNorth($tree, $rowIndex - 1, $columnIndex, $grove, $score);
}

function getScenicScoreSouth($tree, $rowIndex, $columnIndex, $grove, $score = 0) {
	$boundary = count($grove) - 1;
	$score = $score + 1;

	if ($rowIndex === $boundary) {
		return $score;
	}

	if ($tree <= $grove[$rowIndex][$columnIndex]) {
		return $score;
	}

	return getScenicScoreSouth($tree, $rowIndex + 1, $columnIndex, $grove, $score);
}

function getScenicScoreEast($tree, $rowIndex, $columnIndex, $grove, $score = 0) {
	$boundary = 0;
	$score = $score + 1;

	if ($columnIndex === $boundary) {
		return $score;
	}

	if ($tree <= $grove[$rowIndex][$columnIndex]) {
		return $score;
	}

	return getScenicScoreEast($tree, $rowIndex, $columnIndex - 1, $grove, $score);
}

function getScenicScoreWest($tree, $rowIndex, $columnIndex, $grove, $score = 0) {
	$boundary = count($grove) - 1;
	$score = $score + 1;

	if ($columnIndex === $boundary) {
		return $score;
	}

	if ($tree <= $grove[$rowIndex][$columnIndex]) {
		return $score;
	}

	return getScenicScoreWest($tree, $rowIndex, $columnIndex + 1, $grove, $score);
}

$grove = [];
$fileHandle = fopen('day8.txt', 'r');

if ($fileHandle) {
	while (($line = fgets($fileHandle)) !== false) {
		$trimLine = trim($line);
		$grove[] = array_map('intval', str_split($trimLine));
	}

	$visibleEdgeTrees = (count($grove) * 2) + ((count($grove[0]) - 2) * 2);
	$visibleInnerTrees = 0;
	$scenicScores = [];

	for ($j = 1; $j < count($grove) - 1; $j++) {
		for ($k = 1; $k < count($grove[0]) - 1; $k++) {
			$visibleInnerTrees = isTreeVisible($grove, $j, $k) ? $visibleInnerTrees + 1 : $visibleInnerTrees + 0;
			$scenicScores[] = getScenicScore($grove, $j, $k);
		}
	}

	var_dump($visibleEdgeTrees + $visibleInnerTrees);
	var_dump(max($scenicScores));
}
