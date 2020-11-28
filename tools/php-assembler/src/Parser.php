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
        $this->command = $this->cleanWhiteSpace($command);
    }

    /**
     * @return bool
     */
    public function hasMoreCommands()
    {
        return !$this->file->eof();
    }

    public function restart()
    {
        $this->file->rewind();
    }

    /**
     * @return string
     */
    public function advance()
    {
        $command = $this->file->fgets();

        while(preg_match("/^(\/\/|\s+$)/", $command)) {
            $command = $this->file->fgets();
        }

        $this->command = $this->cleanWhiteSpace($command);

        return $this->command;
    }

    public function commandType()
    {
        if ($this->matchesASymbol($this->command)) {
            return static::A_COMMAND;
        } else if ($this->matchesLSymbol($this->command)) {
            return static::L_COMMAND;
        } else if ($this->matchesDest($this->command) || $this->matchesComp($this->command) || $this->matchesJump($this->command)) {
            return static::C_COMMAND;
        }
    }

    private function matchesASymbol($command, &$matches = null)
    {
        return preg_match('/^@(.*)/', $command, $matches);
    }

    private function matchesLSymbol($command, &$matches = null)
    {
        return preg_match('/^\(([^\(\)]+)\)/', $command, $matches);
    }

    private function matchesDest($command, &$matches = null)
    {
        return preg_match('/^((A|M|D)+(D|M)*D*)\s{0,1}(?:=){1}/', $command, $matches);
    }

    private function matchesComp($command, &$matches = null)
    {
        if (preg_match('/^(?:A|M|D)+(?:D|M)*D*\s{0,1}=\s{0,1}(D\+1|A\+1|D-1|A-1|D\+A|D-A|A-D|D&A|D\|A|M\+1|M-1|D\+M|D-M|M-D|D&M|D\|M|0|1|-1|D|A|!D|!A|-D|-A|M|!M|-M)/', $command, $matches)) {
            return true;
        } else if (preg_match('/^(D\+1|A\+1|D-1|A-1|D\+A|D-A|A-D|D&A|D\|A|M\+1|M-1|D\+M|D-M|M-D|D&M|D\|M|0|1|-1|D|A|!D|!A|-D|-A|M|!M|-M)\s{0,1};\s{0,1}(?:JGT|JEQ|JGE|JLT|JNE|JLE|JMP)/', $command, $matches)) {
            return true;
        }

        return false;
    }

    private function matchesJump($command, &$matches = null)
    {
        if (preg_match('/^(?:A|M|D)+(?:D|M)*D*\s{0,1}=\s{0,1}(?:D\+1|A\+1|D-1|A-1|D\+A|D-A|A-D|D&A|D\|A|M\+1|M-1|D\+M|D-M|M-D|D&M|D\|M|0|1|-1|D|A|!D|!A|-D|-A|M|!M|-M)\s{0,1};\s{0,1}(JGT|JEQ|JGE|JLT|JNE|JLE|JMP)/', $command, $matches)) {
            return true;
        } else if (preg_match('/^(?:D\+1|A\+1|D-1|A-1|D\+A|D-A|A-D|D&A|D\|A|M\+1|M-1|D\+M|D-M|M-D|D&M|D\|M|0|1|-1|D|A|!D|!A|-D|-A|M|!M|-M)\s{0,1};\s{0,1}(JGT|JEQ|JGE|JLT|JNE|JLE|JMP)/', $command, $matches)) {
            return true;
        }

        return false;
    }

    public function symbol()
    {
        if (!in_array($this->commandType(), [static::A_COMMAND, static::L_COMMAND])) {
            return null;
        }

        $matches = [];

        if ($this->commandType() === static::A_COMMAND) {
            $this->matchesASymbol($this->command, $matches);
        } else if ($this->commandType() === static::L_COMMAND) {
            $this->matchesLSymbol($this->command, $matches);
        }

        return count($matches) > 0 ? $matches[1] : null;
    }

    public function dest()
    {
        if ($this->commandType() !== static::C_COMMAND) {
            return null;
        }

        $this->matchesDest($this->command, $matches);

        return count($matches) > 0 ? $matches[1] : null;
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

    /**
     * @param $command
     *
     * @return string
     */
    public function cleanWhiteSpace($command)
    {
        $command = str_replace(' ', '', $command);
        return str_replace("\r\n", '', $command);
    }
}
