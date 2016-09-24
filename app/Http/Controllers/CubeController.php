<?php

namespace App\Http\Controllers;


use App\Http\Requests;
use App\Http\Requests\QueryCube;

class CubeController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('cube.index');
    }

    public function queries(QueryCube $request)
    {
        return view('cube.index')->with(['result' => 'resultado']);
    }
}
