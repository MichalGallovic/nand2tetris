<?php

use Nand2Tetris\Code;
use Nand2Tetris\Parser;

$parser = new Parser(__DIR__ . '/fixtures/add/Add.asm');
$code = new Code();

$binary = "";

while($parser->hasMoreCommands()) {
    $parser->advance();
}
