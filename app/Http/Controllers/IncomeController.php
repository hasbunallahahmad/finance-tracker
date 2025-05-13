<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\IncomeCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $incomes = Income::where('user_id', Auth::id())
            ->with('category')
            ->orderBy('date', 'desc')
            ->paginate(10);

        $categories = IncomeCategory::all();

        return view('incomes.index', compact('incomes', 'categories'));
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
            'category_id' => 'nullable|exists:income_categories,id',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $income = new Income($request->except('attachment'));
        $income->user_id = Auth::id();

        // Proses upload file jika ada
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments/incomes', 'public');
            $income->attachment = $path;
        }

        $income->save();

        return redirect()->route('incomes.index')
            ->with('success', 'Pemasukan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Income $income)
    {
        // Cek apakah income milik user yang sedang login
        if ($income->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $categories = IncomeCategory::all();

        return view('incomes.edit', compact('income', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Income $income)
    {
        // Cek apakah income milik user yang sedang login
        if ($income->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Validasi input
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0',
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:income_categories,id',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $income->fill($request->except('attachment'));

        // Proses upload file jika ada
        if ($request->hasFile('attachment')) {
            // Hapus file lama jika ada
            if ($income->attachment) {
                Storage::disk('public')->delete($income->attachment);
            }

            $path = $request->file('attachment')->store('attachments/incomes', 'public');
            $income->attachment = $path;
        }

        $income->save();

        return redirect()->route('incomes.index')
            ->with('success', 'Pemasukan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Income $income)
    {
        // Cek apakah income milik user yang sedang login
        if ($income->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Soft delete - data tetap ada tapi tidak terlihat
        $income->delete();

        return redirect()->route('incomes.index')
            ->with('success', 'Pemasukan berhasil dihapus.');
    }
}
