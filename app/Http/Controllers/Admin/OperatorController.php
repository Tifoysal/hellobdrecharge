<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Operator;
use Illuminate\Http\Request;

class OperatorController extends Controller
{
    public function index()
    {
        $operators=Operator::all();
        return view('admin.layouts.operator.list',compact('operators'));
    }

    public function createForm()
    {
        return view('admin.layouts.operator.create');
    }

    public function create(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'opcode'=>'required',
            'logo'=>'required'
        ]);

        $filename='';
        if ($request->hasFile('logo')) {
            if ($request->file('logo')) {
                $file = $request->file('logo');
                $filename = uniqid('photo', true) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('/uploads/operator'), $filename);
                $_file['logo'] = $filename;
            }
        }

        Operator::create([
            'name'=>$request->name,
            'opcode'=>$request->opcode,
            'description'=>$request->description,
            'logo'=>$filename,
        ]);

        return redirect()->back()->with('message','Operator Created Successfully');
    }
    public function edit($id)
    {
        $operator=Operator::find($id);
        return view('admin.layouts.operator.edit',compact('operator'));
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'name'=>'required',
            'opcode'=>'required'
        ]);
        $operator=Operator::findorFail($id);

        $filename=$operator->logo;

        if ($request->hasFile('logo')) {
            if ($request->file('logo')) {
                $file = $request->file('logo');
                $filename = uniqid('photo', true) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('/uploads/operator'), $filename);
                $_file['logo'] = $filename;
            }
        }

       $operator->update([
        'name'=>$request->name,
        'opcode'=>$request->opcode,
        'description'=>$request->description,
        'status'=>$request->status,
        'logo'=>$filename,
    ]);

        return redirect()->back()->with('message','Operator Updated Successfully');
    }
}
