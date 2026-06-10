<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    // 📊 INDEX
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

    // ➕ CREATE
    public function create()
    {
        return redirect()->route('expenses.index')->with('openAddModal', true);
    }

    // 💾 STORE
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'category' => 'required',
            'description' => 'nullable|string',
        ]);

        Expense::create([
            'amount' => $request->amount,
            'category' => $request->category,
            'description' => $request->description,
        ]);

        return redirect()->route('expenses.index')
            ->with('success', 'Expense added successfully!');
    }

    // ✏️ EDIT
    public function edit(Expense $expense)
    {
        return view('expenses.edit', compact('expense'));
    }

    // 🔄 UPDATE
    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'category' => 'required',
            'description' => 'nullable|string',
        ]);

        $expense->update([
            'amount' => $request->amount,
            'category' => $request->category,
            'description' => $request->description,
        ]);

        return redirect()->route('expenses.index')
            ->with('success', 'Expense updated successfully!');
    }

    // ❌ DELETE
    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('expenses.index')
            ->with('success', 'Expense deleted successfully!');
    }
}