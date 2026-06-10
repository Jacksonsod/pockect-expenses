<!DOCTYPE html>
<html>
<head>
    <title>Add Expense</title>
</head>

<body class="bg-gray-50 p-6">

<div class="max-w-xl mx-auto bg-white p-6 rounded shadow">

    <h1 class="text-xl font-bold mb-4">Add Expense</h1>

    <!-- FORM START -->
    <form method="POST" action="{{ route('expenses.store') }}">
        @csrf

        <!-- AMOUNT -->
        <input type="number"
               name="amount"
               placeholder="Amount"
               class="w-full border p-2 mb-3"
               required>

        <!-- CATEGORY -->
        <input type="text"
               name="category"
               placeholder="Category"
               class="w-full border p-2 mb-3"
               required>

        <!-- DESCRIPTION -->
        <textarea name="description"
                  placeholder="Description"
                  class="w-full border p-2 mb-3"></textarea>

        <!-- SUBMIT -->
        <button class="bg-green-600 text-white px-4 py-2 rounded">
            Save Expense
        </button>

        <a href="{{ route('expenses.index') }}"
           class="ml-3 text-gray-600">
            Cancel
        </a>

    </form>
    <!-- FORM END -->

</div>

</body>
</html>