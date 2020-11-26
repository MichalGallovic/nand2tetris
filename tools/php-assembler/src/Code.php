<?php

namespace Nand2Tetris;

class Code
{
    public function dest($dest)
    {
        if (is_null($dest)) {
            return '000';
        }

        $map = [
            'M' => '001',
            'D' => '010',
            'MD' => '011',
            'A' => '100',
            'AM' => '101',
            'AD' => '110',
            'AMD' => '111'
        ];

        return $map[$dest];
    }
}
