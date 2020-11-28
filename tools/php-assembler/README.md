### Hack assember

Based on course [Nand2Tetris](https://nand2tetris.org), this program can assemble Hack assembly language into binary 16bit binary code runnable on Hack computer described in the course.

Supports:
 - a instructions
 - c instructions
 - labels

#### Running Assembler
```
php main.php <source *.asm> <target *.hack>
```

#### Example input / output

```
// Computes R0 = 2 + 3  (R0 refers to RAM[0])

@2
D=A
@3
D=D+A
@0
M=D
```

is assembled as

```
0000000000000010
1110110000010000
0000000000000011
1110000010010000
0000000000000000
1110001100001000
```

