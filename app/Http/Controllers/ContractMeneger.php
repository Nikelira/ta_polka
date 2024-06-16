<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RentalContract;
use App\Models\CompositionRentalApplication;
use App\Models\RentalApplication;
use App\Models\Product;
use Carbon\Carbon; 
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;


class ContractMeneger extends Controller
{
    //
    public function index(Request $request){
        $today = Carbon::today();
        $category = $request->input('category', 'active'); // По умолчанию показываем активные договора

        if ($category == 'active') {
            $contracts = RentalContract::where('date_end', '>=', $today)->get();
        } 
        else {
            $contracts = RentalContract::where('date_end', '<', $today)->get();
        }
        return view('meneger.contracts', compact('contracts', 'category'));
    }

    public function create()
    {
        // Получаем все одобренные заявки (например, со статусом 2)
        $approvedApplications = RentalApplication::where('application_status_id', 3)->get();

        // Возвращаем представление с формой для создания нового договора
        return view('meneger.contract_create', compact('approvedApplications'));
    }

    public function getApplicationData($applicationId)
    {
        $application = RentalApplication::with(['compositions.shelf', 'products' => function($query) {
            $query->where('product_status_id', 4);
        }])->find($applicationId);
    
        if (!$application) {
            return response()->json(['error' => 'Заявка не найдена'], 404);
        }
    
        $shelves = $application->compositions->map(function ($composition) {
            return [
                'number_shelv' => $composition->shelf->number_shelv,
                'number_wardrobe' => $composition->shelf->number_wardrobe,
                'length' => $composition->shelf->length,
                'width' => $composition->shelf->wigth,
                'cost' => $composition->shelf->cost,
            ];
        });
    
        $approvedProducts = $application->products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'cost' => $product->cost,
                'count' => $product->count,
                'photo_path' => $product->photo_path,
            ];
        });
    
        return response()->json([
            'shelves' => $shelves,
            'approvedProducts' => $approvedProducts,
        ]);
    }

    public function store(Request $request)
    {
        // Валидация данных
        $request->validate([
            'application_id' => 'required|exists:rental_applications,id',
            'date_start' => 'required|date',
            'contract_duration' => 'required|integer|min:1|max:12',
        ]);

        // Получение данных из запроса
        $applicationId = $request->input('application_id');
        $dateStart = Carbon::parse($request->input('date_start'));

        // Преобразуем срок контракта в месяцы в целое число
        $durationInMonths = intval($request->input('contract_duration'));

        // Вычисление даты окончания на основе срока
        $dateEnd = $dateStart->copy()->addMonths($durationInMonths);

        // Получение одобренной заявки и связанных полок
        $application = RentalApplication::findOrFail($applicationId);
        $shelves = $application->shelves;

        // Вычисление общей суммы аренды полок
        $totalShelfCost = $shelves->sum('cost');

        // Создание нового договора
        $contract = new RentalContract();
        $contract->rental_application_id = $applicationId;
        $contract->date_begin = $dateStart;
        $contract->date_end = $dateEnd;
        $contract->summa_contract = $totalShelfCost; // Записываем сумму аренды полок
        $contract->save();

        // Обновление статуса заявки
        $application->application_status_id = 6;
        $application->save();

        // Обновление статуса товаров
        Product::where('rental_application_id', $applicationId)
            ->where('product_status_id', 4)
            ->update(['product_status_id' => 1]);

        // Перенаправление на страницу списка договоров с сообщением об успехе
        return redirect()->route('contracts.index')
                        ->with('success', 'Договор успешно создан');
    }

    public function show($id)
    {
        $contract = RentalContract::with([
            'rentalApplication.user',
            'rentalApplication.shelves',
            // Фильтруем продукты по статусу
            'rentalApplication.products' => function ($query) {
                $query->whereIn('product_status_id', [1, 2]);
            }
        ])->findOrFail($id);

        return view('meneger.contract_show', compact('contract'));
    }

    public function edit($id)
    {
        $contract = RentalContract::with([
            'rentalApplication.user',
            'rentalApplication.shelves',
            // Фильтруем продукты по статусу
            'rentalApplication.products' => function ($query) {
                $query->whereIn('product_status_id', [1, 2]);
            }
        ])->findOrFail($id);

        // Преобразуем даты в объекты Carbon
        $contract->date_begin = Carbon::parse($contract->date_begin);
        $contract->date_end = Carbon::parse($contract->date_end);

        // Получаем одобренную заявку для отображения
        $approvedApplication = $contract->rentalApplication;

        return view('meneger.contract_edit', compact('contract', 'approvedApplication'));
    }

    public function update(Request $request, $id)
    {
        // Валидация данных, кроме application_id
        $request->validate([
            'date_start' => 'required|date',
            'contract_duration' => 'required|integer|min:1|max:12',
        ]);

        $contract = RentalContract::findOrFail($id);
        
        // Обновление данных контракта
        $contract->date_begin = Carbon::parse($request->input('date_start'));
        $contract->date_end = $contract->date_begin->copy()->addMonths($request->input('contract_duration'));
        
        // Сохраняем изменения
        $contract->save();

        // Перенаправление с сообщением об успехе
        return redirect()->route('contracts.index')
                        ->with('success', 'Договор успешно обновлен');
    }

    public function download($id)
    {
        try {
            $contract = RentalContract::findOrFail($id);

            // Load the template document
            $templatePath = public_path('Шаблон.doc');
            if (!file_exists($templatePath)) {
                throw new \Exception('Template file not found.');
            }

            $templateProcessor = new TemplateProcessor($templatePath);

            // Replace placeholders with actual data
            $templateProcessor->setValue('Номер_договора', $contract->contract_number);
            $templateProcessor->setValue('Дата', \Carbon\Carbon::parse($contract->date_begin)->format('d.m.Y'));
            $templateProcessor->setValue('ФИО_пользователя', $contract->rentalApplication->user->full_name);
            $templateProcessor->setValue('номера_полок', $contract->shelf_numbers);
            $templateProcessor->setValue('наименования_товаров', $contract->product_names);

            // Save the generated document to a temporary file
            $tempFilePath = storage_path('app/public/contracts/Договор по номеру ' . $contract->id . '.docx');
            $templateProcessor->saveAs($tempFilePath);

            // Return the file as a download response
            return response()->download($tempFilePath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            // Log the error for debugging purposes
            \Log::error('Error generating document: ' . $e->getMessage());
            
            // Return an error response or redirect as needed
            return back()->withError('Failed to generate document.');
        }
    }
}