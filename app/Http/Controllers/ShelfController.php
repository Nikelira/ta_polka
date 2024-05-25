<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shelf;

class ShelfController extends Controller
{
    public function index(){
        $selectedShelves = session('selected_shelves', []);
        $displayShelves = Shelf::whereIn('number_shelv', range(1, 5))
                                ->where('shelf_status_id', 1)
                                ->whereNotIn('id', $selectedShelves)
                                ->get();
        $selectedShelvesObjects = Shelf::whereIn('id', $selectedShelves)->get();

        return view('shelf', compact('displayShelves', 'selectedShelves', 'selectedShelvesObjects'));
    }

    public function selectShelf(Request $request)
    {
        $shelfId = $request->input('shelf_id');
        $selectedShelves = session('selected_shelves', []);

        $selectedShelves[] = $shelfId;

        session(['selected_shelves' => $selectedShelves]);

        return response()->json(['success' => true, 'message' => 'Полка выбрана']);
    }

    public function deselectShelf(Request $request)
    {
        $shelfNumber = $request->input('shelf_number');
        $selectedShelves = session('selected_shelves', []);

        // Найти все полки с заданным номером в сессии
        $shelvesWithNumber = array_filter($selectedShelves, function($shelfId) use ($shelfNumber) {
            $shelf = Shelf::find($shelfId);
            return $shelf && $shelf->number_shelv == $shelfNumber;
        });

        if (count($shelvesWithNumber) > 0) {
            // Удалить последнюю полку с этим номером
            $lastShelfId = array_pop($shelvesWithNumber);
            if (($key = array_search($lastShelfId, $selectedShelves)) !== false) {
                unset($selectedShelves[$key]);
            }
            session(['selected_shelves' => array_values($selectedShelves)]);

            return response()->json(['success' => true, 'message' => 'Выбор полки отменен']);
        } else {
            return response()->json(['success' => false, 'message' => 'Выбранных полок нет']);
        }
    }
}
