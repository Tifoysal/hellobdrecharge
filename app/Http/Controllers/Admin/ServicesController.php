<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = Service::all();
        return view('admin.layouts.services.list', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.layouts.services.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'code' => 'required|unique:services',
            'rate' => 'required',
            'fees' => 'required',
            'commission_discount' => 'required',
        ]);

        Service::create([
            'name' => $request->name,
            'code' => $request->code,
            'rate' => $request->rate,
            'fees' => $request->fees,
            'commission_discount' => $request->commission_discount,
            'notice' => $request->notice,
        ]);

        return redirect()->back()->with('message', 'service created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $service = Service::find($id);
        return view('admin.layouts.services.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'rate' => 'required',
            'fees' => 'required',
            'commission_discount' => 'required',
            'status' => 'required'
        ]);
        $service = Service::findorFail($id);
        $service->update([
            'name' => $request->name,
            'rate' => $request->rate,
            'fees' => $request->fees,
            'commission_discount' => $request->commission_discount,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('message','Service updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
