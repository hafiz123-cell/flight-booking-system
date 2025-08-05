<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Models\Airport; 
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/dashboard-data', fn () => response()->json([
        'secure' => true,
        'message' => 'You are logged in'
    ]));
});

Route::get('/set-airports-data', function () {
    ini_set('max_execution_time', 300); // or use set_time_limit(300);
    set_time_limit(300);

    Airport::truncate(); // Clears existing data (optional)

    $excelFilePath = public_path('airports/Tripjack Airport & Airline Information.xlsx');

    if (!file_exists($excelFilePath)) {
        return response()->json([
            'error' => 'Excel file not found',
            'path' => $excelFilePath
        ], 404);
    }

    $reader = new Xlsx();
    $spreadsheet = $reader->load($excelFilePath);
    $rows = $spreadsheet->getActiveSheet()->toArray();

    array_shift($rows); // Remove header
    $batch = [];
    $importedCount = 0;

    foreach ($rows as $row) {
        if (empty($row[0])) continue;

        $batch[] = [
            'code'         => $row[0] ?? '',
            'name'         => $row[1] ?? '',
            'city'         => $row[2] ?? '',
            'city_code'    => $row[3] ?? '',
            'country'      => $row[4] ?? '',
            'country_code' => $row[5] ?? '',
            'address'      => trim(($row[2] ?? '') . ', ' . ($row[4] ?? '')),
            'created_at'   => now(),
            'updated_at'   => now(),
        ];

        if (count($batch) === 500) {
            Airport::insert($batch);
            $importedCount += count($batch);
            $batch = [];
        }
    }

    // Insert remaining rows
    if (!empty($batch)) {
        Airport::insert($batch);
        $importedCount += count($batch);
    }

    return response()->json([
        'success' => true,
        'message' => "$importedCount airports imported successfully"
    ]);
});