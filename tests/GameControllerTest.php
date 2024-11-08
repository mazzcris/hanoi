<?php

namespace Hanoi\tests;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class GameControllerTest extends TestCase
{
    private string $sessionFile = 'cookies.txt';

    protected function setUp(): void
    {
        if (file_exists($this->sessionFile)) {
            unlink($this->sessionFile);
        }
    }

    #[Test]
    public function testNew()
    {
        $response = shell_exec("curl -c {$this->sessionFile} http://localhost:8000/new");

        $expectedOutput = '
        ####NEWGAME#####
        
         =                   |                   |
         ==                  |                   |
        ===                  |                   |
        ====                 |                   |
       =====                 |                   |
       ======                |                   |
      =======                |                   |
      
      ';

        $this->assertEquals(str_replace(' ', '', $expectedOutput), str_replace(' ', '', $response));
    }

    #[Test]
    public function testMove()
    {
        $response = shell_exec("curl -c {$this->sessionFile} http://localhost:8000/new");

        $expectedOutput = '
        ####NEWGAME#####
        
         =                   |                   |
         ==                  |                   |
        ===                  |                   |
        ====                 |                   |
       =====                 |                   |
       ======                |                   |
      =======                |                   |
      
      ';

        $this->assertEquals(str_replace(' ', '', $expectedOutput), str_replace(' ', '', $response));

        $response = shell_exec("curl -X POST -b {$this->sessionFile} http://localhost:8000/move/1/2");

        $expectedOutput = '
         |                   |                   |
         ==                  |                   |
        ===                  |                   |
        ====                 |                   |
       =====                 |                   |
       ======                |                   |
      =======                =                   |
      
      ';

        $this->assertEquals(str_replace(' ', '', $expectedOutput), str_replace(' ', '', $response));
    }

    #[Test]
    public function testState()
    {
        $response = shell_exec("curl -c {$this->sessionFile} http://localhost:8000/new");

        $expectedOutput = '
        ####NEWGAME#####
        
         =                   |                   |
         ==                  |                   |
        ===                  |                   |
        ====                 |                   |
       =====                 |                   |
       ======                |                   |
      =======                |                   |
      
      ';

        $this->assertEquals(str_replace(' ', '', $expectedOutput), str_replace(' ', '', $response));

        shell_exec("curl -X POST -b {$this->sessionFile} http://localhost:8000/move/1/2");
        $response = shell_exec("curl -b {$this->sessionFile} http://localhost:8000/state");

        $expectedOutput = '
         |                   |                   |
         ==                  |                   |
        ===                  |                   |
        ====                 |                   |
       =====                 |                   |
       ======                |                   |
      =======                =                   |
      
      ';

        $this->assertEquals(str_replace(' ', '', $expectedOutput), str_replace(' ', '', $response));
    }

    #[Test]
    public function testWin()
    {
        shell_exec("curl -c {$this->sessionFile} http://localhost:8000/demo");

        $response = shell_exec("curl -b {$this->sessionFile} http://localhost:8000/state");

        $expectedOutput = '
         |                   |                   |
         |                   |                   ==
         |                   |                  ===
         |                   |                   ====
         |                   |                  =====
         |                   |                  ======
         |                   =                 =======
      
      ';

        $this->assertEquals(str_replace(' ', '', $expectedOutput), str_replace(' ', '', $response));


        $response = shell_exec("curl -X POST -b {$this->sessionFile} http://localhost:8000/move/2/3");
        $expectedOutput = '        
         |                   |                    =
         |                   |                   ==
         |                   |                  ===
         |                   |                   ====
         |                   |                  =====
         |                   |                  ======
         |                   |                 =======
      
      
       #####YOUWON#####
       
      ';


        $this->assertEquals(str_replace(' ', '', $expectedOutput), str_replace(' ', '', $response));
    }
}