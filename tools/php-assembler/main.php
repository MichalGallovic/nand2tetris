<?php

require __DIR__ . '/vendor/autoload.php';

use Nand2Tetris\Code;
use Nand2Tetris\Parser;
use Nand2Tetris\SymbolTable;

$parser = new Parser(__DIR__ . '/' . $argv[1]);
$code = new Code();
$symbolTable = new SymbolTable();

$binary = "";
$counter = 0;

while ($parser->hasMoreCommands()) {
    $command = $parser->advance();
    if ($parser->commandType() === Parser::L_COMMAND) {
        $symbolTable->addEntry($parser->symbol(), $counter);
    } else {
        $counter++;
    }
}

$counter = 16;

$parser->restart();

while($parser->hasMoreCommands()) {
    $command = $parser->advance();

    if ($parser->commandType() === Parser::A_COMMAND) {
        $symbol = $parser->symbol();

        if (!is_numeric($symbol)) {
            if (!$symbolTable->contains($symbol)) {
                $symbolTable->addEntry($symbol, $counter);
                $counter++;
            }
            $symbol = $symbolTable->getAddress($symbol);
        }

        $binary .= "0" . sprintf("%015d", decbin($symbol)) . PHP_EOL;
    } else if ($parser->commandType() === Parser::C_COMMAND) {
        $dest = $code->dest($parser->dest());
        $comp = $code->comp($parser->comp());
        $jump = $code->jump($parser->jump());
        $binary .= "111" . $comp . $dest . $jump . PHP_EOL;
    }
}


file_put_contents($argv[2], $binary);
