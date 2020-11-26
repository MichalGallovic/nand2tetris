<?php

namespace Nand2Tetris;

class Parser
{
    private $file;

    private $command;

    const A_COMMAND = 'A';
    const C_COMMAND = 'C';
    const L_COMMAND = 'L';

    public function __construct(string $path)
    {
        $this->file = new \SplFileObject($path);

        if (is_null($this->file)) {
            throw new \Exception("File does not exist");
        }
    }

    public function setCommand($command)
    {
        $this->command = $command;
    }

    /**
     * @return bool
     */
    public function hasMoreCommands()
    {
        return !$this->file->eof();
    }

    /**
     * @return string
     */
    public function advance()
    {
        $command = $this->file->fgets();

        while(preg_match("/^(\/\/|\s+)/", $command)) {
            $command = $this->file->fgets();
        }

        $this->command = str_replace("\r\n", '', $command);

        return $this->command;
    }

    public function commandType()
    {
        if (preg_match('/^@/', $this->command)) {
            return static::A_COMMAND;
        } else if ($this->matchesDest($this->command)) {
            return static::C_COMMAND;
        }
    }

    private function matchesDest($command, &$matches = null)
    {
        return preg_match('/^(A|M|D)+(D|M)*D*/', $command, $matches);
    }

    private function matchesComp($command, &$matches = null)
    {
        return preg_match('/^(?:A|M|D)+(?:D|M)*\s{0,1}D*=\s{0,1}(D\+1|A\+1|D-1|A-1|D\+A|D-A|A-D|D&A|D\|A|M\+1|M-1|D\+M|D-M|M-D|D&M|D\|M|0|1|-1|D|A|!D|!A|-D|-A|M|!M|-M)/', $command, $matches);
    }

    private function matchesJump($command, &$matches = null)
    {
        return preg_match('/^(?:A|M|D)+(?:D|M)*\s{0,1}D*=\s{0,1}(?:D\+1|A\+1|D-1|A-1|D\+A|D-A|A-D|D&A|D\|A|M\+1|M-1|D\+M|D-M|M-D|D&M|D\|M|0|1|-1|D|A|!D|!A|-D|-A|M|!M|-M)\s{0,1};\s{0,1}(JGT|JEQ|JGE|JLT|JNE|JLE|JMP)/', $command, $matches);
    }

    public function dest()
    {
        if ($this->commandType() !== static::C_COMMAND) {
            return null;
        }

        $this->matchesDest($this->command, $matches);

        return $matches[0];
    }

    public function comp()
    {
        if ($this->commandType() !== static::C_COMMAND) {
            return null;
        }

        $this->matchesComp($this->command, $matches);

        return count($matches) > 0 ? $matches[1] : null;
    }

    public function jump()
    {
        if ($this->commandType() !== static::C_COMMAND) {
            return null;
        }

        $this->matchesJump($this->command, $matches);

        return count($matches) > 0 ? $matches[1] : null;
    }
}
