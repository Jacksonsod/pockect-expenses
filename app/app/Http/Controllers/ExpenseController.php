<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePocketExpenseRequest;
use App\Http\Requests\UpdatePocketExpenseRequest;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;


class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 1. Calculate the financial summary tiles
        $totalAllTime = Expense::sum('amount');
        $totalEntries = Expense::count();

        // 2. Calculate the total for the current calendar month
        $totalThisMonth = Expense::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        // 3. Group expenses by category and calculate total per category
        $categories = Expense::select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->orderBy('total', 'desc')
            ->get();

        // 4. Calculate percentage weight for each category dynamically
        $categoryBalances = $categories->map(function ($item) use ($totalAllTime) {
            $item->percentage = $totalAllTime > 0 ? ($item->total / $totalAllTime) * 100 : 0;
            return $item;
        });

        // Pass all dynamic aggregates directly to your view layout
        return view('expenses.index', [
            'totalAllTime' => $totalAllTime,
            'totalThisMonth' => $totalThisMonth,
            'totalEntries' => $totalEntries,
            'categoryBalances' => $categoryBalances
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('expenses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePocketExpenseRequest $request)
    {
        Expense::create($request->validated());

        return redirect()->route('expenses.index')
            ->with('success', 'Expense logged successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        return view('expenses.edit', compact('expense'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePocketExpenseRequest $request, Expense $expense)
    {
        $expense->update($request->validated());

        return redirect()->route('expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }
}
