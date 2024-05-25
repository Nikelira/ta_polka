<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shelf;

class ApplicationRental extends Controller
{
    public function index(Request $request)
    {
        // Получение выбранных полок по их уникальным идентификаторам
        $selectedShelfIds = session('selected_shelves', []);
        $selectedShelvesObjects = Shelf::whereIn('id', $selectedShelfIds)->get();

        return view('postavshik.cooperation', compact('selectedShelvesObjects'));
    }

    public function deselect(Request $request)
    {
        $shelfId = $request->input('shelf_id');
        $selectedShelves = session('selected_shelves', []);
        
        // Удаление полки из сессии
        if (($key = array_search($shelfId, $selectedShelves)) !== false) {
            unset($selectedShelves[$key]);
        }

        session(['selected_shelves' => array_values($selectedShelves)]);

        return response()->json(['success' => true, 'message' => 'Полка удалена из сессии']);
    }
}
