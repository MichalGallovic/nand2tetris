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
    public function has_more_commands_at_end()
    {
        $parser = new Parser(__DIR__ . '/fixtures/add/Add.asm');

        foreach (range(0, 13) as $i) {
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
        $this->assertEquals("// This file is part of www.nand2tetris.org\r\n", $parser->advance());
        $this->assertEquals("// and the book \"The Elements of Computing Systems\"\r\n", $parser->advance());
    }

    /**
     * @test
     */
    public function cannot_advance_end_of_file()
    {
        $parser = new Parser(__DIR__ . '/fixtures/add/Add.asm');

        foreach (range(0, 13) as $i) {
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
}
