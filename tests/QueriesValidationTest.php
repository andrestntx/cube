<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class QueriesValidationTest extends TestCase
{

    public function testBasic()
    {
        $this->visitRoute('cube.index')
            ->type('2', 'text')
            ->press('Send')
            ->dontSee('Result');
    }

    public function testErrorUpdate()
    {
        $this->visitRoute('cube.index')
            ->type("2\n2 1\nUPDATE 1 1 1 4 5", 'text')
            ->press('Send')
            ->dontSee('Result');
    }

    public function testErrorQuery()
    {
        $this->visitRoute('cube.index')
            ->type("2\n2 1\nQUERY 1 1 1 4 5", 'text')
            ->press('Send')
            ->dontSee('Result');
    }

    public function testIncompleteError()
    {
        $this->visitRoute('cube.index')
            ->type("2\n2 2", 'text')
            ->press('Send')
            ->dontSee('Result');
    }

    public function testIncompleteTestError()
    {
        $this->visitRoute('cube.index')
            ->type("2\n2 2\nUPDATE 1 1 1 4", 'text')
            ->press('Send')
            ->dontSee('Result');
    }

    public function testBasicSuccess()
    {
        $this->visitRoute('cube.index')
            ->type("2\n2 1\nUPDATE 1 1 1 4", 'text')
            ->press('Send')
            ->see('Result');
    }


    public function testFullSuccess()
    {
        $this->visitRoute('cube.index')
            ->type("2\n4 5\nUPDATE 2 2 2 4\nQUERY 1 1 1 3 3 3\nUPDATE 1 1 1 23\nQUERY 2 2 2 4 4 4\nQUERY 1 1 1 3 3 3\n2 4\nUPDATE 2 2 2 1\nQUERY 1 1 1 1 1 1\nQUERY 1 1 1 2 2 2\nQUERY 2 2 2 2 2 2", 'text')
            ->press('Send')
            ->see('Result');
    }


}
