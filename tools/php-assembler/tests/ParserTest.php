<?php

namespace Tests;

use Nand2Tetris\Parser;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    /**
    * @test
    */
    public function can_load_existing_file()
    {
      new Parser(__DIR__ . '/fixtures/add/Add.asm');
      $this->assertTrue(true);
    }


    /**
    * @test
    */
    public function throws_exception_when_loading_unknown_file()
    {
        try {
            new Parser(__DIR__ . '/fixtures/add/Addasdasd.asm');
        } catch (\Exception $e) {
            $this->assertTrue(true);
            return;
        }

        $this->assertTrue(false);
    }

    /**
    * @test
    */
    public function has_more_commands_at_beginning()
    {
        $parser = new Parser(__DIR__ . '/fixtures/add/Add.asm');
        $this->assertTrue($parser->hasMoreCommands());
    }

    /**
    * @test
    */
    public function has_no_more_commands_at_end()
    {
        $parser = new Parser(__DIR__ . '/fixtures/add/Add.asm');

        foreach (range(0, 6) as $i) {
            $parser->advance();
        }

        $this->assertFalse($parser->hasMoreCommands());
    }

    /**
     * @test
     */
    public function can_advance()
    {
        $parser = new Parser(__DIR__ . '/fixtures/add/Add.asm');
        $this->assertEquals("@2", $parser->advance());
        $this->assertEquals("D=A", $parser->advance());
    }

    /**
     * @test
     */
    public function cannot_advance_end_of_file()
    {
        $parser = new Parser(__DIR__ . '/fixtures/add/Add.asm');

        foreach (range(0, 6) as $i) {
            $parser->advance();
        }

        try {
            $parser->advance();
        } catch (\RuntimeException $e) {
            $this->assertTrue(true);
            return;
        }

        $this->assertFalse(true, 'Should have thrown runtime exception');
    }

    /**
    * @test
    */
    public function can_detect_a_command()
    {
        $parser = new Parser(__DIR__ . '/fixtures/add/Add.asm');

        $parser->advance();

        $this->assertEquals(Parser::A_COMMAND, $parser->commandType());
    }

    /**
    * @test
    */
    public function can_detect_c_command()
    {
        $parser = new Parser(__DIR__ . '/fixtures/add/Add.asm');

        $parser->advance();
        $parser->advance();

        $this->assertEquals(Parser::C_COMMAND, $parser->commandType());
    }

//    /**
//    * @test
//    */
//    public function can_detect_l_command()
//    {
//
//    }


    /**
     * @test
     */
    public function can_get_dest_mnemonic()
    {
        $parser = new Parser(__DIR__ . '/fixtures/add/Add.asm');

        $parser->setCommand('D=D+A');
        $this->assertEquals('D', $parser->dest());
        $parser->setCommand('M=');
        $this->assertEquals('M', $parser->dest());
        $parser->setCommand('A=');
        $this->assertEquals('A', $parser->dest());
        $parser->setCommand('MD=');
        $this->assertEquals('MD', $parser->dest());
        $parser->setCommand('AM=');
        $this->assertEquals('AM', $parser->dest());
        $parser->setCommand('AD=');
        $this->assertEquals('AD', $parser->dest());
        $parser->setCommand('AMD=');
        $this->assertEquals('AMD', $parser->dest());
    }

    /**
    * @test
    */
    public function can_get_comp_mnemonic()
    {
        $parser = new Parser(__DIR__ . '/fixtures/add/Add.asm');

        $parser->setCommand('D=');
        $this->assertEquals(null, $parser->comp());
        $parser->setCommand('D=0');
        $this->assertEquals('0', $parser->comp());
        $parser->setCommand('D=1');
        $this->assertEquals('1', $parser->comp());
        $parser->setCommand('D=-1');
        $this->assertEquals('-1', $parser->comp());
        $parser->setCommand('D=D');
        $this->assertEquals('D', $parser->comp());
        $parser->setCommand('D=A');
        $this->assertEquals('A', $parser->comp());
        $parser->setCommand('D=!D');
        $this->assertEquals('!D', $parser->comp());
        $parser->setCommand('D=!A');
        $this->assertEquals('!A', $parser->comp());
        $parser->setCommand('D=-D');
        $this->assertEquals('-D', $parser->comp());
        $parser->setCommand('D=-A');
        $this->assertEquals('-A', $parser->comp());
        $parser->setCommand('D=D+1');
        $this->assertEquals('D+1', $parser->comp());
        $parser->setCommand('D=A+1');
        $this->assertEquals('A+1', $parser->comp());
        $parser->setCommand('D=D-1');
        $this->assertEquals('D-1', $parser->comp());
        $parser->setCommand('D=A-1');
        $this->assertEquals('A-1', $parser->comp());
        $parser->setCommand('D=D+A');
        $this->assertEquals('D+A', $parser->comp());
        $parser->setCommand('D=D-A');
        $this->assertEquals('D-A', $parser->comp());
        $parser->setCommand('D=A-D');
        $this->assertEquals('A-D', $parser->comp());
        $parser->setCommand('D=D&A');
        $this->assertEquals('D&A', $parser->comp());
        $parser->setCommand('D=D|A');
        $this->assertEquals('D|A', $parser->comp());
        $parser->setCommand('D=M');
        $this->assertEquals('M', $parser->comp());
        $parser->setCommand('D=!M');
        $this->assertEquals('!M', $parser->comp());
        $parser->setCommand('D=-M');
        $this->assertEquals('-M', $parser->comp());
        $parser->setCommand('D=M+1');
        $this->assertEquals('M+1', $parser->comp());
        $parser->setCommand('D=M-1');
        $this->assertEquals('M-1', $parser->comp());
        $parser->setCommand('D=D+M');
        $this->assertEquals('D+M', $parser->comp());
        $parser->setCommand('D=D-M');
        $this->assertEquals('D-M', $parser->comp());
        $parser->setCommand('D=M-D');
        $this->assertEquals('M-D', $parser->comp());
        $parser->setCommand('D=D&M');
        $this->assertEquals('D&M', $parser->comp());
        $parser->setCommand('D=D|M');
        $this->assertEquals('D|M', $parser->comp());
    }

    /**
    * @test
    */
    public function can_get_jump_mnemonic()
    {
        $parser = new Parser(__DIR__ . '/fixtures/add/Add.asm');

        $parser->setCommand('D=0');
        $this->assertEquals(null, $parser->jump());
        $parser->setCommand('D=0;JGT');
        $this->assertEquals('JGT', $parser->jump());
        $parser->setCommand('D=0;JEQ');
        $this->assertEquals('JEQ', $parser->jump());
        $parser->setCommand('D=0;JGE');
        $this->assertEquals('JGE', $parser->jump());
        $parser->setCommand('D=0;JLT');
        $this->assertEquals('JLT', $parser->jump());
        $parser->setCommand('D=0;JNE');
        $this->assertEquals('JNE', $parser->jump());
        $parser->setCommand('D=0;JLE');
        $this->assertEquals('JLE', $parser->jump());
        $parser->setCommand('D=0;JMP');
        $this->assertEquals('JMP', $parser->jump());
    }
}
