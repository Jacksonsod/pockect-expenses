<!DOCTYPE html>
<html>
<head>
    <title>Edit Expense</title>
</head>

<body class="bg-gray-50 p-6">

<div class="max-w-xl mx-auto bg-white p-6 rounded shadow">

    <h1 class="text-xl font-bold mb-4">Edit Expense</h1>

    <form method="POST" action="{{ route('expenses.update', $expense->id) }}">
        @csrf
        @method('PUT')

        <input type="text" name="title"
               value="{{ $expense->title }}"
               class="w-full border p-2 mb-3">

        <input type="number" step="0.01" name="amount"
               value="{{ $expense->amount }}"
               class="w-full border p-2 mb-3">

        <input type="text" name="category"
               value="{{ $expense->category }}"
               class="w-full border p-2 mb-3">

        <input type="date" name="expense_date"
               value="{{ $expense->expense_date }}"
               class="w-full border p-2 mb-3">

        <button class="bg-blue-600 text-white px-4 py-2 rounded">
            Update
        </button>

        <a href="{{ route('expenses.index') }}"
           class="ml-3 text-gray-600">
            Cancel
        </a>
    </form>

</div>

</body>
</html>