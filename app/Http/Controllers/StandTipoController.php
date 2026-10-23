<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StandTipo;

class StandTipoController extends Controller
{

    public function index()
    {
        $tipos = StandTipo::orderBy('id', 'desc')->get();

        return view('App.StandTipo.index', compact('tipos'));
    }

    public function create()
    {
        $standTipo = new StandTipo();

        return view('App.StandTipo.form', compact('standTipo'));
    }

    public function edit($id)
    {
        $standTipo = StandTipo::findOrFail($id);

        return view('App.StandTipo.form', compact('standTipo'));
    }

    public function updateStore(Request $request)
    {

        if ($request->id) {
            $item = StandTipo::find($request->id);
        } else {
            $item = new StandTipo();
        }

        $item->nombre = $request->nombre;
        $item->save();

        return redirect()
            ->route('stand-tipo.index')
            ->with('success', 'Guardado correctamente');
    }
}
