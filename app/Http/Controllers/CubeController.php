<?php

namespace App\Http\Controllers;


use App\Http\Requests;
use App\Http\Requests\QueryCube;
use App\Facades\CubeFacade;

class CubeController extends Controller
{
    protected $facade;

    /**
     * CubeController constructor.
     * @param $facade
     */
    public function __construct(CubeFacade $facade)
    {
        $this->facade = $facade;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('cube.index');
    }

    /**
     * @param QueryCube $request
     * @return $this
     */
    public function queries(QueryCube $request)
    {
        $result = $this->facade->queries($request->get('text'));

        return redirect()->route('cube.index')
            ->with(['result' => $result])
            ->withInput();
    }
}
