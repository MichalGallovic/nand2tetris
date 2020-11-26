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
}
