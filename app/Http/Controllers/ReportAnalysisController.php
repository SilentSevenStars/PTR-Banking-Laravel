<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportAnalysisController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $userId = Auth::id();
            $driver = DB::getDriverName();
            $validTypes = ['deposit', 'withdraw', 'loan repayment'];

            $summary = [
                'deposit' => 0,
                'withdraw' => 0,
                'balance' => 0,
            ];

            $rows = DB::table('transactions')
                ->select('type', DB::raw('SUM(ABS(amount)) as total'))
                ->where('user_id', $userId)
                ->whereIn('type', $validTypes)
                ->where('status', 'success')
                ->groupBy('type')
                ->get();

            foreach ($rows as $row) {
                $summary[$row->type] = (float) $row->total;
            }

            $summary['balance'] = ($summary['deposit'] ?? 0) - ($summary['withdraw'] ?? 0);

            if ($driver === 'sqlite') {
                $monthExpr = "strftime('%Y-%m', created_at)";
            } else {
                $monthExpr = "DATE_FORMAT(created_at, '%Y-%m')";
            }

            $chartData = DB::table('transactions')
                ->select(
                    DB::raw("$monthExpr as month"),
                    DB::raw("SUM(CASE WHEN type='deposit' THEN ABS(amount) ELSE 0 END) as deposits"),
                    DB::raw("SUM(CASE WHEN type='withdraw' THEN ABS(amount) ELSE 0 END) as withdrawals")
                )
                ->where('user_id', $userId)
                ->whereIn('type', $validTypes)
                ->where('status', 'success')
                ->groupBy(DB::raw("$monthExpr"))
                ->orderBy(DB::raw("$monthExpr"))
                ->get();

            return response()->json([
                'summary' => $summary,
                'chartData' => $chartData,
            ]);
        }

        return view('user.report_analysis');
    }

    public function export($type)
    {
        $userId = Auth::user()->id;
        $transactions = DB::table('transactions')
            ->where('user_id', $userId)
            ->where('status', 'success')
            ->select('id', 'type', 'amount', 'status', 'created_at')
            ->orderBy('created_at', 'asc')
            ->get();

        // --- CSV ---
        if ($type === 'csv') {
            $filename = "report_analysis_" . date('Ymd_His') . ".csv";
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];

            $callback = function () use ($transactions) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['ID', 'Type', 'Amount', 'Status', 'Date']);
                foreach ($transactions as $txn) {
                    fputcsv($file, [
                        $txn->id,
                        ucfirst($txn->type),
                        number_format($txn->amount, 2),
                        ucfirst($txn->status),
                        $txn->created_at,
                    ]);
                }
                fclose($file);
            };

            return new StreamedResponse($callback, 200, $headers);
        }

        // --- EXCEL (PhpSpreadsheet) ---
        if ($type === 'xlsx') {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Headings
            $sheet->setCellValue('A1', 'ID');
            $sheet->setCellValue('B1', 'Type');
            $sheet->setCellValue('C1', 'Amount');
            $sheet->setCellValue('D1', 'Status');
            $sheet->setCellValue('E1', 'Date');

            // Rows
            $row = 2;
            foreach ($transactions as $txn) {
                $sheet->setCellValue('A' . $row, $txn->id);
                $sheet->setCellValue('B' . $row, ucfirst($txn->type));
                $sheet->setCellValue('C' . $row, number_format($txn->amount, 2));
                $sheet->setCellValue('D' . $row, ucfirst($txn->status));
                $sheet->setCellValue('E' . $row, $txn->created_at);
                $row++;
            }

            $writer = new Xlsx($spreadsheet);
            $filename = "report_analysis_" . date('Ymd_His') . ".xlsx";

            return response()->streamDownload(function () use ($writer) {
                $writer->save('php://output');
            }, $filename);
        }

        // --- PDF ---
        if ($type === 'pdf') {
            $pdf = Pdf::loadView('user.report_pdf', ['transactions' => $transactions]);
            return $pdf->download("report_analysis_" . date('Ymd_His') . ".pdf");
        }

        return response()->json(['message' => 'Invalid export type.']);
    }
}
