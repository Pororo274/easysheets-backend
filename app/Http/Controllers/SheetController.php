<?php

namespace App\Http\Controllers;

use App\Models\Sheet;
use App\Models\SheetCell;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SheetController extends Controller
{
    public function create(Request $request)
    {
        $sheet = Sheet::query()->create([
            'name' => $request->input('name')
        ]);

        return response()->json($sheet);
    }

    public function addCell(int $sheetId, Request $request)
    {
        $cell = SheetCell::query()->create([
            'sheet_id' => $sheetId,
            'address' => $request->input('address'),
            'value' => $request->input('value')
        ]);

        return response()->json($cell);
    }

    public function export(int $sheetId)
    {
        $cells = SheetCell::query()->where('sheet_id', $sheetId)->get();

        $spreadsheet = new Spreadsheet;

        $cells->each(function (SheetCell $cell) use (&$spreadsheet) {
            $spreadsheet->getActiveSheet()->setCellValue($cell->address, $cell->value);
        });

        $writer = new Xlsx($spreadsheet);

        $writer->save(Storage::path('public/sheet.xlsx'));

        return response()->json([
            'status' => 'success'
        ]);
    }
}
