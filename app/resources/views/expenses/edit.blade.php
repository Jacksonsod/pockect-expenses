<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Expense - Pocket</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #fafafa;
        }
        .custom-date-input::-webkit-calendar-picker-indicator {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: auto;
            height: auto;
            color: transparent;
            background: transparent;
            cursor: pointer;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="antialiased text-slate-800 bg-[#fafafa] min-h-screen">

    <div class="max-w-xl mx-auto px-4 py-12 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <a href="{{ route('expenses.index') }}" class="inline-flex items-center gap-2 text-slate-400 hover:text-slate-600 transition-colors mb-8 group">
            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span class="text-[14px] font-semibold">Back to dashboard</span>
        </a>

        <!-- Main Card -->
        <div class="bg-white rounded-[32px] shadow-sm border border-gray-100 p-8 sm:p-10 mb-8">
            <!-- Header -->
            <div class="mb-10">
                <div class="w-14 h-14 bg-emerald-50 text-[#0fa968] rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <h1 class="text-[24px] font-extrabold text-slate-900 tracking-tight">Edit expense</h1>
                <p class="text-[14px] text-slate-500 font-medium mt-1">Make changes to your tracked purchase.</p>
            </div>

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="mb-8 p-5 bg-red-50 border border-red-100 rounded-2xl text-red-600 text-[13px] font-semibold">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Edit Form -->
            <form action="{{ route('expenses.update', $expense->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Amount -->
                <div>
                    <label for="amount" class="block text-[13px] font-bold text-slate-700 mb-2">Amount</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 font-bold">$</span>
                        <input type="number" name="amount" id="amount" required min="1" step="1" 
                               value="{{ old('amount', $expense->amount) }}"
                               class="w-full pl-8 pr-4 py-3.5 bg-white border border-slate-200 rounded-[16px] text-[15px] text-slate-800 focus:outline-none focus:ring-4 focus:ring-[#0fa968]/5 focus:border-[#0fa968] transition-all font-semibold">
                    </div>
                </div>

                <!-- Category -->
                <div>
                    <label for="category" class="block text-[13px] font-bold text-slate-700 mb-2">Category</label>
                    <div class="relative">
                        <select name="category" id="category" required
                                class="w-full pl-4 pr-10 py-3.5 bg-white border border-slate-200 rounded-[16px] text-[15px] font-semibold text-[#0f172a] focus:outline-none focus:ring-4 focus:ring-[#0fa968]/5 focus:border-[#0fa968] appearance-none transition-all">
                            @php
                                $categories = ['Food', 'Transport', 'Logistics', 'Food & Refreshments', 'Internet Bundle', 'Utilities', 'Entertainment', 'Others'];
                            @endphp
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ (old('category', $expense->category) == $cat) ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400">
                            <svg class="w-5 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Date -->
                <div>
                    <label for="date" class="block text-[13px] font-bold text-slate-700 mb-2">Date</label>
                    <div class="relative">
                        <input type="date" name="date" id="date" required 
                               value="{{ old('date', $expense->created_at->format('Y-m-d')) }}"
                               class="custom-date-input w-full pl-4 pr-10 py-3.5 bg-white border border-slate-200 rounded-[16px] text-[15px] font-semibold text-slate-800 focus:outline-none focus:ring-4 focus:ring-[#0fa968]/5 focus:border-[#0fa968] transition-all">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Note -->
                <div>
                    <label for="description" class="block text-[13px] font-bold text-slate-700 mb-2">Note (optional)</label>
                    <input type="text" name="description" id="description" 
                           value="{{ old('description', $expense->description) }}"
                           placeholder="What was this for?"
                           class="w-full px-4 py-3.5 bg-white border border-slate-200 rounded-[16px] text-[15px] font-semibold text-slate-800 placeholder-slate-300 focus:outline-none focus:ring-4 focus:ring-[#0fa968]/5 focus:border-[#0fa968] transition-all">
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-4 pt-4">
                    <button type="submit" class="flex-1 px-6 py-4 bg-[#0fa968] hover:bg-[#0d9258] text-white text-[15px] font-bold rounded-[16px] transition-all shadow-sm hover:shadow-md cursor-pointer">
                        Update expense
                    </button>
                    <a href="{{ route('expenses.index') }}" class="px-6 py-4 bg-slate-100 hover:bg-slate-200 text-slate-600 text-[15px] font-bold rounded-[16px] transition-all cursor-pointer">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <p class="text-center text-[13px] font-medium text-slate-400">
            Securely updating your financial records.
        </p>
    </div>
</body>
</html>