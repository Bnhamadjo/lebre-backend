<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Evento;


class EventoController extends Controller
{
    public function index() {
    return Evento::all();
}

public function store(Request $request) {
    return Evento::create($request->all());
}

public function update(Request $request, $id) {
    $evento = Evento::findOrFail($id);
    $evento->update($request->all());
    return $evento;
}

public function destroy($id) {
    Evento::destroy($id);
    return response()->noContent();
}

}
