<?php

class LocalFile {
	private string $name;
	private int $size;

	public function __construct(string $name, int $size) {
		$this->name = $name;
		$this->size = $size;
	}

	public function getSize() : int {
		return $this->size;
	}

	public function getName() : string {
		return $this->name;
	}
}

class LocalDir {
	private string $name;
	private array $files = [];
	private array $childDirectories = [];
	private ?LocalDir $parentLocalDir;

	public function __construct(string $name, $parentLocalDir = null) {
		$this->name = $name;
		$this->parentLocalDir = $parentLocalDir;
	}

	public function getSize() : int {
		$fileTotals = array_reduce($this->files, function($acc, $file) {
			return $acc + $file->getSize();
		}, 0);

		$dirTotals = array_reduce($this->childDirectories, function ($acc, $dir) {
			return $acc + $dir->getSize();
		}, 0);

		return $fileTotals + $dirTotals;
	}

	public function getParent() : LocalDir {
		if ($this->parentLocalDir != null) {
			return $this->parentLocalDir;
		}

		return $this;
	}

	public function getName() : string {
		return $this->name;
	}

	public function changeLocalDir(string $name) : LocalDir {
		if ($name === '..') {
			return $this->getParent();
		}

		if (isset($this->childDirectories[$name])) {
			return $this->childDirectories[$name];
		}

		if ($name === $this->name) {
			return $this;
		}

		return $this->getParent()->changeLocalDir($name);

	}

	public function addChild(string $name) : LocalDir {
		$newChild = new LocalDir($name, $this);
		$this->childDirectories[$name] = $newChild;

		return $newChild;
	}

	public function addFile(string $name, int $size) : LocalFile {
		$newFile = new LocalFile($name, $size);
		$this->files[$name] = $newFile;

		return $newFile;
	}

	public function traverse(array $output = []) {
		foreach ($this->childDirectories as $child) {
			$output = $child->traverse($output);
		}

		$output[] = $this;
		return $output;
	}

	public function findDirectoryToDelete(int $size, $candidates = []) {
		foreach ($this->childDirectories as $child) {
			if ($child->getSize() >= $size) {
				$candidates = $child->findDirectoryToDelete($size, $candidates);
			}
		}

		if ($this->getSize() >= $size) {
			$candidates[] = $this->getSize();
		}

		return $candidates;
	}
}

const THRESHOLD = 100000;
const TOTAL_DISK = 70000000;
const UPDATE_MEM = 30000000;

$currentLocalDir = null;
$root = null;
$fileHandle = fopen('day7.txt', 'r');

if ($fileHandle) {
	while (($line = fgets($fileHandle)) !== false) {
		$line = trim($line);
		//var_dump($line);
		if (str_starts_with($line, '$ cd')) {
			[, , $directoryName] = explode(' ', $line);

			if (empty($currentLocalDir)) {
				$currentLocalDir = new LocalDir($directoryName);
				$root = $currentLocalDir;
			} else {
				$currentLocalDir = $currentLocalDir->changeLocalDir($directoryName);
			}
		} elseif (str_starts_with($line, 'dir')) {
			[, $directoryName] = explode(' ', $line);
			$currentLocalDir->addChild($directoryName);
		} elseif (str_starts_with($line, '$ ls')) {
			// do nothing?
		} else {
			// Assume file
			[$size, $name] = explode(' ', $line);
			$currentLocalDir->addFile($name, $size);
		}
	}

	$solution1 = array_reduce($root->traverse(), function ($acc, $dir) {
		if ($dir->getSize() <= THRESHOLD) {
			return $acc + $dir->getSize();
		}
		return $acc;
	}, 0);


	$spaceToFreeUp = UPDATE_MEM - (TOTAL_DISK - $root->getSize());
	$deletionCandidates = $root->findDirectoryToDelete($spaceToFreeUp);
	sort($deletionCandidates);

	var_dump($solution1);
	var_dump($deletionCandidates[0]);
}
