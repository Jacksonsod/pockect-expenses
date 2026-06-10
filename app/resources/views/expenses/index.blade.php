<?php

namespace App\Http\Controllers;

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

        if (request('search')) {
            $search = request('search');
            $query->where('description', 'like', "%$search%")
                  ->orWhere('category', 'like', "%$search%");
        }

        if (request('category') && request('category') != 'All Categories') {
            $query->where('category', request('category'));
        }

        $recentExpenses = $query->latest()->paginate(5)->withQueryString();

        $allCategories = Expense::select('category')->distinct()->pluck('category');

        return view('expenses.index', compact(
            'totalAllTime',
            'totalThisMonth',
            'totalEntries',
            'categoryBalances',
            'recentExpenses',
            'allCategories'
        ));
    }

    public function create()
    {
        return redirect()->route('expenses.index')->with('openAddModal', true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'category' => 'required',
            'description' => 'nullable|string',
            'date' => 'nullable|date'
        ]);

        $expense = new Expense();
        $expense->amount = $request->amount;
        $expense->category = $request->category;
        $expense->description = $request->description;

        if ($request->date) {
            $expense->created_at = $request->date;
        }

        $expense->save();

        return redirect()->route('expenses.index')
            ->with('success', 'Expense added successfully!');
    }

    public function edit(Expense $expense)
    {
        return view('expenses.edit', compact('expense'));
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'category' => 'required',
            'description' => 'nullable|string',
        ]);

        $expense->update($request->only(['amount','category','description']));

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