<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bitacora;
use Illuminate\Http\Request;

class BitacoraController extends Controller
{
    public function index()
    {
        // Obtener las bitácoras ordenadas por las más recientes
        $bitacoras = Bitacora::with('usuario')
            ->orderBy('fecha', 'desc')
            ->orderBy('hora', 'desc')
            ->paginate(50);
            
        return view('admin.bitacora.index', compact('bitacoras'));
    }
}
