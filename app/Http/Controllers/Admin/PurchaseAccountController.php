<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseAccount;
use Illuminate\Http\Request;

class PurchaseAccountController extends Controller
{
    public function index()
    {

        $account=PurchaseAccount::all();
        return view('admin.layouts.purchase_account.list',compact('account'));
    }

    public function createForm()
    {
        return view('admin.layouts.purchase_account.create');
    }

    public function create(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'type'=>'required',
        ]);

        PurchaseAccount::create([
            'name'=>$request->name,
            'type'=>$request->type,
            'description'=>$request->description,
        ]);

        return redirect()->back()->with('message','Account Created Successfully');
    }

    public function edit($id)
    {
        $account=PurchaseAccount::find($id);
        return view('admin.layouts.purchase_account.edit',compact('account'));
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'name'=>'required',
            'type'=>'required',
        ]);

        PurchaseAccount::where('id',$id)->update([
            'name'=>$request->name,
            'type'=>$request->type,
            'description'=>$request->description,
            'status'=>$request->status,
        ]);

        return redirect()->back()->with('message','Account Updated Successfully');
    }
}
