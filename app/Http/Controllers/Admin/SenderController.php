<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sender;
use Illuminate\Http\Request;

class SenderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $senders=Sender::where('status','active')->get();
        return view('admin.layouts.sender.list',compact('senders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.layouts.sender.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
           'name'=>'required',
           'number'=>'required|unique:senders'
        ]);

        Sender::create([
            'name'=>$request->name,
            'number'=>$request->number,
            'description'=>$request->description
        ]);

        return redirect()->back()->with('message','Sender Created Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       $sender=Sender::find($id);
       return view('admin.layouts.sender.edit',compact('sender'));
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
        $sender=Sender::find($id);
        $request->validate([
            'name'=>'required',
            'number'=>'required|unique:senders,number,'.$sender->id
        ]);

        $sender->update([
            'name'=>$request->name,
            'number'=>$request->number,
            'description'=>$request->description,
            'status'=>$request->status
        ]);

        return redirect()->back()->with('message','Sender Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
