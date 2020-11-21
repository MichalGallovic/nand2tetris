<?php

namespace Nand2Tetris;

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
        $command = $this->file->fgets();

        while(preg_match("/^(\/\/|\s+)/", $command)) {
            $command = $this->file->fgets();
        }

        $this->command = str_replace("\r\n", '', $command);

        return $this->command;
    }
}
