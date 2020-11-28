<?php

namespace Tests;

use Nand2Tetris\Code;
use PHPUnit\Framework\TestCase;

class CodeTest extends TestCase
{
    /**
    * @test
    */
    public function can_encode_dest()
    {
        $code = new Code();

        $this->assertEquals('001', $code->dest('M'));
        $this->assertEquals('010', $code->dest('D'));
        $this->assertEquals('011', $code->dest('MD'));
        $this->assertEquals('100', $code->dest('A'));
        $this->assertEquals('101', $code->dest('AM'));
        $this->assertEquals('110', $code->dest('AD'));
        $this->assertEquals('111', $code->dest('AMD'));
    }

    /**
    * @test
    */
    public function can_encode_comp()
    {
        $code = new Code();

        // a=0
        $this->assertEquals('0000000', $code->comp(null));
        $this->assertEquals('0101010', $code->comp('0'));
        $this->assertEquals('0111111', $code->comp('1'));
        $this->assertEquals('0111010', $code->comp('-1'));
        $this->assertEquals('0001100', $code->comp('D'));
        $this->assertEquals('0110000', $code->comp('A'));
        $this->assertEquals('0001101', $code->comp('!D'));
        $this->assertEquals('0110001', $code->comp('!A'));
        $this->assertEquals('0001111', $code->comp('-D'));
        $this->assertEquals('0110011', $code->comp('-A'));
        $this->assertEquals('0011111', $code->comp('D+1'));
        $this->assertEquals('0110111', $code->comp('A+1'));
        $this->assertEquals('0001110', $code->comp('D-1'));
        $this->assertEquals('0110010', $code->comp('A-1'));
        $this->assertEquals('0000010', $code->comp('D+A'));
        $this->assertEquals('0010011', $code->comp('D-A'));
        $this->assertEquals('0000111', $code->comp('A-D'));
        $this->assertEquals('0000000', $code->comp('D&A'));
        $this->assertEquals('0010101', $code->comp('D|A'));

        // a=1
        $this->assertEquals('1110000', $code->comp('M'));
        $this->assertEquals('1110001', $code->comp('!M'));
        $this->assertEquals('1110011', $code->comp('-M'));
        $this->assertEquals('1110111', $code->comp('M+1'));
        $this->assertEquals('1110010', $code->comp('M-1'));
        $this->assertEquals('1000010', $code->comp('D+M'));
        $this->assertEquals('1010011', $code->comp('D-M'));
        $this->assertEquals('1000111', $code->comp('M-D'));
        $this->assertEquals('1000000', $code->comp('D&M'));
        $this->assertEquals('1010101', $code->comp('D|M'));
    }

    /**
    * @test
    */
    public function can_encode_jump()
    {
        $code = new Code();

        $this->assertEquals('001', $code->jump('JGT'));
        $this->assertEquals('010', $code->jump('JEQ'));
        $this->assertEquals('011', $code->jump('JGE'));
        $this->assertEquals('100', $code->jump('JLT'));
        $this->assertEquals('101', $code->jump('JNE'));
        $this->assertEquals('110', $code->jump('JLE'));
        $this->assertEquals('111', $code->jump('JMP'));
    }
}
