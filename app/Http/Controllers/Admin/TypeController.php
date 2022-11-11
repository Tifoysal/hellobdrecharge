<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    public function index()
    {
        $types=Type::all();
        return view('admin.layouts.type.index',compact('types'));
    }

    public function create()
    {
        return view('admin.layouts.type.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required'
        ]);

        Type::create([
           'name'=>$request->name,
           'code'=>$request->code,
        ]);
        return redirect()->back()->with('message','Type created successfully.');
    }

    public function edit($id)
    {

    }

    public function update(Request $request, $id)
    {

    }
}
