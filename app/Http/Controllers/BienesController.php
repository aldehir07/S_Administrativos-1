<?php

namespace App\Http\Controllers;

use App\Models\Bienes;
use Illuminate\Http\Request;

class BienesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('bienes.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Bienes $bienes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bienes $bienes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bienes $bienes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bienes $bienes)
    {
        //
    }
}
