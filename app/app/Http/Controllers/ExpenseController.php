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

        // 5. Get recent expenses with filtering and pagination
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

        // Pass all dynamic aggregates directly to your view layout
        return view('expenses.index', [
            'totalAllTime' => $totalAllTime,
            'totalThisMonth' => $totalThisMonth,
            'totalEntries' => $totalEntries,
            'categoryBalances' => $categoryBalances,
            'recentExpenses' => $recentExpenses,
            'allCategories' => $allCategories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('expenses.index')->with('openAddModal', true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePocketExpenseRequest $request)
    {
        $data = $request->validated();
        $data['description'] = $data['description'] ?? '';

        $expense = new Expense();
        $expense->amount = $data['amount'];
        $expense->category = $data['category'];
        $expense->description = $data['description'];
        if (!empty($data['date'])) {
            $expense->created_at = $data['date'];
        }
        $expense->save();

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
        $data = $request->validated();
        if (array_key_exists('description', $data)) {
            $data['description'] = $data['description'] ?? '';
        }

        $expense->fill($data);
        if (!empty($data['date'])) {
            $expense->created_at = $data['date'];
        }
        $expense->save();

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
