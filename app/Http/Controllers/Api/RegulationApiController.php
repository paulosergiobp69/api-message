<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Regulation;

class RegulationApiController extends Controller
{

    public function __construct(Regulation $regulation, Request $request )
    {
        $this->regulation = $regulation;
        $this->request = $request;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!$data = $this->regulation->all())
        {
            return response()->json(['message' =>'Registro Não Localizado.'], 404);
        }else{
            return response()->json($data);
        }

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->regulation->rules());

        $dataform = $request->all(); 

        $data = $this->regulation->create($dataform);

        return response()->json($data,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!$data = $this->regulation->find($id))
        {
            return response()->json(['message' =>'Registro Não Localizado.'], 404);
        }else{
            return response()->json($data);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $regulation = Regulation::findOrFail($id);
        $regulation->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $regulation = Regulation::findOrFail($id);
        $regulation->delete();
    }
}
