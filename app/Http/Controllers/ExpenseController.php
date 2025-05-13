<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenses = Expense::where('user_id', Auth::id())
            ->with('category')
            ->orderBy('date', 'desc')
            ->paginate(10);

        $categories = ExpenseCategory::all();

        return view('expenses.index', compact('expenses', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0',
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:expense_categories,id',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $expense = new Expense($request->except('attachment'));
        $expense->user_id = Auth::id();

        // Proses upload file jika ada
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments/expenses', 'public');
            $expense->attachment = $path;
        }

        $expense->save();

        return redirect()->route('expenses.index')
            ->with('success', 'Pengeluaran berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        // Cek apakah expense milik user yang sedang login
        if ($expense->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $categories = ExpenseCategory::all();

        return view('expenses.edit', compact('expense', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        // Cek apakah expense milik user yang sedang login
        if ($expense->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Validasi input
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0',
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:expense_categories,id',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $expense->fill($request->except('attachment'));

        // Proses upload file jika ada
        if ($request->hasFile('attachment')) {
            // Hapus file lama jika ada
            if ($expense->attachment) {
                Storage::disk('public')->delete($expense->attachment);
            }

            $path = $request->file('attachment')->store('attachments/expenses', 'public');
            $expense->attachment = $path;
        }

        $expense->save();

        return redirect()->route('expenses.index')
            ->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        // Cek apakah expense milik user yang sedang login
        if ($expense->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Soft delete - data tetap ada tapi tidak terlihat
        $expense->delete();

        return redirect()->route('expenses.index')
            ->with('success', 'Pengeluaran berhasil dihapus.');
    }
}
