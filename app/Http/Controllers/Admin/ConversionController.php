<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversion;
use Illuminate\Http\Request;

class ConversionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'rate' => 'required|numeric'
        ]);
        $conversion = Conversion::where('user_id', auth()->user()->id)->first();
        if ($conversion) {
            $conversion->update([
                'rate' => (float)$request->rate
            ]);
        } else {
            Conversion::create([
                'rate' => (float)$request->rate,
                'user_id' => auth()->user()->id,
            ]);
        }


        return redirect()->back()->with('message', 'Rate store successfully.');
    }
}
