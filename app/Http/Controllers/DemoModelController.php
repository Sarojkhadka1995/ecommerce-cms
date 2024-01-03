<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoredemoModelRequest;
use App\Http\Requests\UpdatedemoModelRequest;
use App\demoModel;

class DemoModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoredemoModelRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(demoModel $demoModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(demoModel $demoModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatedemoModelRequest $request, demoModel $demoModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(demoModel $demoModel)
    {
        //
    }
}
