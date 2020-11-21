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

    }

    /**
    * @test
    */
    public function can_detect_c_command()
    {

    }

    /**
    * @test
    */
    public function can_detect_l_command()
    {

    }
}
