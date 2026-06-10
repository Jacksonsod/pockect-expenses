<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePocketExpenseRequest;
use App\Http\Requests\UpdatePocketExpenseRequest;
use Illuminate\Http\Request;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{

    public function index()
    {
        $totalAllTime = Expense::sum('amount');
        $totalEntries = Expense::count();

        $totalThisMonth = Expense::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        $categories = Expense::select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->orderBy('total', 'desc')
            ->get();

        $categoryBalances = $categories->map(function ($item) use ($totalAllTime) {
            $item->percentage = $totalAllTime > 0 ? ($item->total / $totalAllTime) * 100 : 0;
            return $item;
        });

        $query = Expense::query();

        if (request()->has('search') && request('search') != '') {
            $search = request('search');
            $query->where('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
        }

        if (request()->has('category') && request('category') != 'All Categories' && request('category') != '') {
            $query->where('category', request('category'));
        }

        $recentExpenses = $query->latest()->paginate(5)->withQueryString();

        $allCategories = Expense::select('category')->distinct()->pluck('category');

        return view('expenses.index', [
            'totalAllTime' => $totalAllTime,
            'totalThisMonth' => $totalThisMonth,
            'totalEntries' => $totalEntries,
            'categoryBalances' => $categoryBalances,
            'recentExpenses' => $recentExpenses,
            'allCategories' => $allCategories,
        ]);
    }

    public function create()
    {
        return redirect()->route('expenses.index')->with('openAddModal', true);
    }

    public function store(StorePocketExpenseRequest $request)
    {
        Expense::create($request->validated());

        return redirect()->route('expenses.index')
            ->with('success', 'Expense logged successfully.');
    }

    public function edit(Expense $expense)
    {
        return view('expenses.edit', compact('expense'));
    }

    public function update(UpdatePocketExpenseRequest $request, Expense $expense)
    {
        $expense->update($request->validated());
        return redirect()->route('expenses.index')
            ->with('success', 'Expense updated successfully!');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('expenses.index')
            ->with('success', 'Expense deleted successfully!');
    }
}
