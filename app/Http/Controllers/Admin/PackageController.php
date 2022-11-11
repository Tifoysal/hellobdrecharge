<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Operator;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {

        $packages=Package::with('operator_name')->get();
       return view('admin.layouts.package.list',compact('packages'));
    }

    public function createForm()
    {
        $operators=Operator::where('status','active')->get();
        return view('admin.layouts.package.create',compact('operators'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'operator_id'=>'required',
            'user_charge'=>'required|numeric',
            'type'=>'required',
            'vendor_charge'=>'required|numeric'
        ]);

        Package::create([
            'name'=>$request->name,
            'type'=>$request->type,
            'operator'=>$request->operator_id,
            'vendor_charge'=>$request->vendor_charge,
            'user_charge'=>$request->user_charge,
            'description'=>$request->description,
        ]);

        return redirect()->back()->with('message','Package Created Successfully');
    }

    public function edit($id)
    {
        $package=Package::find($id);
        $operators=Operator::where('status','active')->get();
        return view('admin.layouts.package.edit',compact('package','operators'));
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'name'=>'required',
            'operator_id'=>'required',
            'user_charge'=>'required|numeric',
            'type'=>'required',
            'vendor_charge'=>'required|numeric'
        ]);

        Package::where('id',$id)->update([
            'name'=>$request->name,
            'type'=>$request->type,
            'operator'=>$request->operator_id,
            'vendor_charge'=>$request->vendor_charge,
            'user_charge'=>$request->user_charge,
            'description'=>$request->description,
            'status'=>$request->status,
        ]);

        return redirect()->back()->with('message','Package Updated Successfully');
    }
}
