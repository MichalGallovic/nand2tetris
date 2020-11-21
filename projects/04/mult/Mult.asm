// This file is part of www.nand2tetris.org
// and the book "The Elements of Computing Systems"
// by Nisan and Schocken, MIT Press.
// File name: projects/04/Mult.asm

// Multiplies R0 and R1 and stores the result in R2.
// (R0, R1, R2 refer to RAM[0], RAM[1], and RAM[2], respectively.)

// Put your code here.

// Multiplication is:
// 3 * 4 = 3 + 3 + 3 + 3 = 12

// Pseudocode for
// RAM[2] = RAM[0] * RAM[1]
// is
// for 1 -> RAM[1]:
//   RAM[2] = RAM[2] + RAM[0]

// init i to 1 and R2 to 0
@i
M=0
@R2
M=0

(LOOP)
  @R1
  D=M
  @i
  D=D-M
  @END
  D;JEQ
  @R2
  D=M
  @R0
  D=D+M
  @R2
  M=D
  @i
  M=M+1
  @LOOP
  0;JMP

(END)
  @END
  0;JMP
