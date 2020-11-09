<?php

namespace Nand2Tetris;

use function Couchbase\defaultDecoder;

class Parser
{
    private $file;

    private $command;

    public function __construct(string $path)
    {
        $this->file = new \SplFileObject($path);

        if (is_null($this->file)) {
            throw new \Exception("File does not exist");
        }
    }

    public function hasMoreCommands()
    {
        return !$this->file->eof();
    }

    public function advance()
    {
        $this->command = $this->file->fgets();

        return $this->command;
    }
}
