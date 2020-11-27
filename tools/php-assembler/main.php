<?php

require __DIR__ . '/vendor/autoload.php';

use Nand2Tetris\Code;
use Nand2Tetris\Parser;

$parser = new Parser(__DIR__ . '/tests/fixtures/max/MaxL.asm');
$code = new Code();

$binary = "";

while($parser->hasMoreCommands()) {
    $command = $parser->advance();
    var_dump($command);
    if ($parser->commandType() === Parser::A_COMMAND) {
        $symbol = $parser->symbol();
        $binary .= "0" . sprintf("%015d", decbin($symbol)) . PHP_EOL;
    } else if ($parser->commandType() === Parser::C_COMMAND) {
        $dest = $code->dest($parser->dest());
        $comp = $code->comp($parser->comp());
        $jump = $code->jump($parser->jump());
        $binary .= "111" . $comp . $dest . $jump . PHP_EOL;
    }
    var_dump($binary);
}

echo $binary;
