<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Income;
use App\Models\MonthlyReport;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportController extends Controller
{
    /**
     * Display a listing of monthly reports.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $reports = MonthlyReport::where('user_id', Auth::id())
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate(12);

        return view('reports.index', compact('reports'));
    }

    /**
     * Generate a monthly report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function generate(Request $request): RedirectResponse
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2000|max:2100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $userId = Auth::id();
        $month = $request->month;
        $year = $request->year;

        // Cek jika laporan sudah ada
        $existingReport = MonthlyReport::where('user_id', $userId)
            ->where('month', $month)
            ->where('year', $year)
            ->first();

        if ($existingReport) {
            return redirect()->route('reports.show', $existingReport->id);
        }

        // Kumpulkan data untuk laporan
        $totalIncome = Income::where('user_id', $userId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->sum('amount');

        $totalExpense = Expense::where('user_id', $userId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->sum('amount');

        $balance = $totalIncome - $totalExpense;

        // Kelompokkan income berdasarkan kategori
        $incomesByCategory = Income::where('user_id', $userId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->with('category')
            ->get()
            ->groupBy(function ($income) {
                return $income->category ? $income->category->name : 'Tanpa Kategori';
            })
            ->map(function ($items) {
                return $items->sum('amount');
            });

        // Kelompokkan expenses berdasarkan kategori
        $expensesByCategory = Expense::where('user_id', $userId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->with('category')
            ->get()
            ->groupBy(function ($expense) {
                return $expense->category ? $expense->category->name : 'Tanpa Kategori';
            })
            ->map(function ($items) {
                return $items->sum('amount');
            });

        // Data transaksi untuk detail laporan
        $incomes = Income::where('user_id', $userId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->with('category')
            ->orderBy('date')
            ->get();

        $expenses = Expense::where('user_id', $userId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->with('category')
            ->orderBy('date')
            ->get();

        // Ringkasan data dalam format JSON untuk disimpan
        $summaryData = [
            'incomes_by_category' => $incomesByCategory,
            'expenses_by_category' => $expensesByCategory,
            'daily_balance' => $this->getDailyBalance($userId, $month, $year),
        ];

        // Buat objek laporan
        $report = new MonthlyReport([
            'user_id' => $userId,
            'month' => $month,
            'year' => $year,
            'total_income' => $totalIncome,
            'total_expense' => $totalExpense,
            'balance' => $balance,
            'summary_data' => $summaryData,
        ]);

        // Generate PDF dan simpan
        $pdf = PDF::loadView('reports.pdf', [
            'report' => $report,
            'incomes' => $incomes,
            'expenses' => $expenses,
            'incomesByCategory' => $incomesByCategory,
            'expensesByCategory' => $expensesByCategory,
            'monthName' => Carbon::createFromDate($year, $month, 1)->format('F'),
        ]);

        $filename = "report_{$userId}_{$year}_{$month}.pdf";
        $path = "reports/{$filename}";

        // Simpan PDF ke storage
        Storage::disk('public')->put($path, $pdf->output());

        $report->report_file = $path;
        $report->save();

        return redirect()->route('reports.show', $report->id)
            ->with('success', 'Laporan bulanan berhasil dibuat.');
    }

    /**
     * Display the specified report.
     *
     * @param  \App\Models\MonthlyReport  $report
     * @return \Illuminate\View\View
     */
    public function show(MonthlyReport $report): View
    {
        // Verifikasi kepemilikan
        if ($report->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Decode summary data
        $summaryData = $report->summary_data;

        // Ambil data untuk ditampilkan di view
        $monthName = Carbon::createFromDate($report->year, $report->month, 1)->format('F');

        return view('reports.show', compact('report', 'summaryData', 'monthName'));
    }

    /**
     * Download the report PDF.
     *
     * @param  \App\Models\MonthlyReport  $report
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
     */
    public function download(MonthlyReport $report): BinaryFileResponse|RedirectResponse
    {
        // Verifikasi kepemilikan
        if ($report->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Verifikasi file ada
        if (!$report->report_file || !Storage::disk('public')->exists($report->report_file)) {
            return redirect()->back()->with('error', 'File laporan tidak ditemukan.');
        }

        $monthName = Carbon::createFromDate($report->year, $report->month, 1)->format('F');
        $filename = "Laporan_Keuangan_{$monthName}_{$report->year}.pdf";

        // Download file dengan nama yang lebih user-friendly
        $filePath = Storage::disk('public')->path($report->report_file);
        return response()->download($filePath, $filename);
    }

    /**
     * Helper method to get daily balance for the month.
     *
     * @param int $userId
     * @param int $month
     * @param int $year
     * @return array
     */
    private function getDailyBalance(int $userId, int $month, int $year): array
    {
        $startDate = Carbon::createFromDate($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();
        $daysInMonth = $endDate->day;

        $dailyData = [];

        // Inisialisasi array dengan 0 untuk semua hari
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dailyData[$day] = [
                'income' => 0,
                'expense' => 0,
                'balance' => 0
            ];
        }

        // Isi data income
        $incomes = Income::where('user_id', $userId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();

        foreach ($incomes as $income) {
            $day = (int) $income->date->format('d');
            $dailyData[$day]['income'] += $income->amount;
        }

        // Isi data expense
        $expenses = Expense::where('user_id', $userId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();

        foreach ($expenses as $expense) {
            $day = (int) $expense->date->format('d');
            $dailyData[$day]['expense'] += $expense->amount;
        }

        // Hitung balance harian dan balance kumulatif
        $cumulativeBalance = 0;
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dailyBalance = $dailyData[$day]['income'] - $dailyData[$day]['expense'];
            $dailyData[$day]['balance'] = $dailyBalance;
            $cumulativeBalance += $dailyBalance;
            $dailyData[$day]['cumulative_balance'] = $cumulativeBalance;
        }

        return $dailyData;
    }
}
