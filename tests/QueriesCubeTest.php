<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class QueriesCubeTest extends TestCase
{
    public function testBasic()
    {
        $this->json('POST', '/api/json', [
                'text' => "1\n3 2\nUPDATE 1 1 1 4\nQUERY 1 1 1 3 3 3"
            ])->seeJson([
                'success' => true,
                'result'  => [[4]]
        ]);
    }

    public function testFullTest()
    {
        $this->json('POST', '/api/json', [
            'text' => "2\n4 5\nUPDATE 2 2 2 4\nQUERY 1 1 1 3 3 3\nUPDATE 1 1 1 23\nQUERY 2 2 2 4 4 4\nQUERY 1 1 1 3 3 3\n2 4\nUPDATE 2 2 2 1\nQUERY 1 1 1 1 1 1\nQUERY 1 1 1 2 2 2\nQUERY 2 2 2 2 2 2"
        ])->seeJson([
            'success' => true,
            'result'  => [[4,4,27], [0,1,1]]
        ]);
    }
}
