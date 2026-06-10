<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pocket - Simple expense tracker</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #fafafa;
        }
        /* Make the entire date input container clickable for date picker */
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
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>
<body class="text-slate-800 antialiased min-h-screen flex flex-col" x-data="{ tab: 'recent', openAddModal: {{ (session('openAddModal') || $errors->any()) ? 'true' : 'false' }} }">

    <!-- Navbar -->
    <header class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-[72px] flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-[#0fa968] rounded-xl flex items-center justify-center text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                <div class="flex flex-col">
                    <h1 class="text-[17px] font-bold text-slate-800 leading-tight">Pocket</h1>
                    <p class="text-[12px] text-slate-400 font-medium tracking-wide leading-tight">Simple expense tracker</p>
                </div>
            </div>

            <a href="{{ route('expenses.create') }}" @click.prevent="openAddModal = true" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#0fa968] hover:bg-[#0d9258] text-white text-[14px] font-semibold rounded-full transition-colors cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                Add expense
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow pt-12 pb-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-11">
                <div class="bg-white rounded-[20px] border border-gray-100 p-6 shadow-sm">
                    <h2 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">This Month</h2>
                    <div class="text-[32px] font-extrabold text-slate-800 tracking-tight">{{$totalAllTime}}</div>
                </div>
                <div class="bg-white rounded-[20px] border border-gray-100 p-6 shadow-sm">
                    <h2 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">All Time</h2>
                    <div class="text-[32px] font-extrabold text-slate-800 tracking-tight">{{$totalAllTime}}</div>
                </div>
                <div class="bg-white rounded-[20px] border border-gray-100 p-6 shadow-sm">
                    <h2 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Entries</h2>
                    <div class="text-[32px] font-extrabold text-slate-800 tracking-tight">{{$totalEntries}}</div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="bg-[#f0f3f6] rounded-[16px] p-1.5 flex mb-8 max-w-[480px] mx-auto">
                <button @click="tab = 'recent'" :class="tab === 'recent' ? 'text-[#0fa968] bg-white shadow-sm ring-1 ring-gray-100/50' : 'text-slate-500 hover:text-slate-700'" class="flex-1 flex items-center justify-center gap-2 py-2.5 text-[14px] font-semibold transition-colors rounded-[12px]">
                    <svg class="w-4 h-4" :class="tab === 'recent' ? '' : 'opacity-70'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"></path>
                    </svg>
                    Recent
                </button>
                <button @click="tab = 'insights'" :class="tab === 'insights' ? 'text-[#0fa968] bg-white shadow-sm ring-1 ring-gray-100/50' : 'text-slate-500 hover:text-slate-700'" class="flex-1 flex items-center justify-center gap-2 py-2.5 text-[14px] font-semibold transition-colors rounded-[12px]">
                    <svg class="w-4 h-4" :class="tab === 'insights' ? '' : 'opacity-70'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    Insights
                </button>
            </div>

            <!-- Recent Tab Content -->
            <div x-show="tab === 'recent'" style="display: none;">
                <!-- Search & Filter -->
                <form method="GET" action="{{ route('expenses.index') }}" class="flex flex-col sm:flex-row gap-3 mb-6">
                    <div class="flex-1 relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search expenses by note or category..." class="w-full pl-4 pr-4 py-3 bg-white border border-gray-200 rounded-[12px] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#0fa968]/20 focus:border-[#0fa968] transition-all placeholder-slate-400">
                    </div>
                    <div class="sm:w-48 relative">
                        <select name="category" onchange="this.form.submit()" class="w-full pl-4 pr-10 py-3 bg-white border border-gray-200 rounded-[12px] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#0fa968]/20 focus:border-[#0fa968] appearance-none transition-all">
                            <option value="">All Categories</option>
                            @foreach($allCategories as $cat)
                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    <button type="submit" class="hidden">Search</button>
                </form>

                <!-- Expenses List -->
                <div class="space-y-4 mb-8">
                    @forelse($recentExpenses as $expense)
                    <div class="bg-white rounded-[16px] border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow flex items-center justify-between">
                        <div class="flex flex-col gap-2">
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-1 bg-[#e0f5eb] text-[#0d9258] text-[11px] font-bold uppercase tracking-wider rounded-md">
                                    {{ $expense->category }}
                                </span>
                                <span class="text-[14px] font-medium text-slate-500">{{ $expense->description ?: 'No note' }}</span>
                            </div>
                            <div class="text-[12px] font-semibold text-slate-400">
                                {{ $expense->created_at->format('n/j/Y') }}
                            </div>
                        </div>
                        <div class="text-[18px] font-bold text-slate-800">
                            ${{ number_format($expense->amount, 2) }}
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12 bg-white rounded-[16px] border border-gray-100">
                        <p class="text-slate-500 font-medium">No expenses found.</p>
                    </div>
                    @endforelse
                </div>

                <!-- Custom Pagination -->
                @if ($recentExpenses->hasPages())
                <div class="flex items-center justify-center gap-4 mb-12">
                    @if ($recentExpenses->onFirstPage())
                        <span class="px-4 py-2 text-[14px] font-semibold text-slate-400 bg-white border border-gray-100 rounded-xl cursor-not-allowed">Previous</span>
                    @else
                        <a href="{{ $recentExpenses->previousPageUrl() }}" class="px-4 py-2 text-[14px] font-semibold text-slate-600 bg-white border border-gray-200 rounded-xl hover:bg-slate-50 transition-colors">Previous</a>
                    @endif

                    <span class="text-[14px] font-semibold text-slate-600">
                        Page {{ $recentExpenses->currentPage() }} of {{ $recentExpenses->lastPage() }}
                    </span>

                    @if ($recentExpenses->hasMorePages())
                        <a href="{{ $recentExpenses->nextPageUrl() }}" class="px-4 py-2 text-[14px] font-semibold text-slate-700 bg-white border border-gray-200 rounded-xl hover:bg-slate-50 transition-colors shadow-sm">Next</a>
                    @else
                        <span class="px-4 py-2 text-[14px] font-semibold text-slate-400 bg-white border border-gray-100 rounded-xl cursor-not-allowed">Next</span>
                    @endif
                </div>
                @endif
            </div>

            <!-- Insights Tab Content -->
            <div x-show="tab === 'insights'" style="display: none;" class="bg-white rounded-[20px] border border-gray-100 p-8 shadow-sm mb-12">
                <h3 class="text-[16px] font-bold text-slate-800 mb-8">Spending by category</h3>

                <div class="space-y-7">
                    @foreach($categoryBalances as $category)
                    <!-- Category Item -->
                    <div>
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-[14px] font-bold text-slate-700">{{ ucfirst($category->category) }}</span>
                            <span class="text-[14px] font-bold text-slate-800">${{ number_format($category->total, 2) }}</span>
                        </div>
                        <div class="w-full bg-[#f1f5f9] rounded-full h-[10px]">
                            <div class="bg-[#2ace93] h-[10px] rounded-full" style="width: {{ $category->percentage }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <p class="text-center text-[13px] font-medium text-slate-400">
                Data synced securely with your database.
            </p>
        </div>
    </main>

    <!-- Add Expense Modal -->
    <div x-show="openAddModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak>
         
        <!-- Backdrop Blur and Overlay -->
        <div class="fixed inset-0 bg-[#0f172a]/30 backdrop-blur-[4px] transition-opacity" @click="openAddModal = false"></div>

        <!-- Modal Content Box -->
        <div class="relative bg-white rounded-[24px] shadow-2xl border border-slate-100 max-w-[440px] w-full p-8 overflow-hidden transform transition-all"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4">
             
            <!-- Close Button -->
            <button @click="openAddModal = false" class="absolute top-8 right-8 w-7 h-7 flex items-center justify-center rounded-full border border-slate-200/60 hover:bg-slate-50 text-slate-400 hover:text-slate-600 transition-colors cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <!-- Header -->
            <div class="mb-6 pr-8">
                <h3 class="text-[20px] font-bold text-[#0f172a] leading-tight">New expense</h3>
                <p class="text-[13px] text-slate-500 font-medium mt-1 leading-normal">
                    Track a single purchase. Synchronizes securely to your database.
                </p>
            </div>

            <!-- Validation Errors inside the modal -->
            @if ($errors->any())
                <div class="mb-5 p-4 bg-red-50 border border-red-100 rounded-2xl text-red-600 text-[13px] font-semibold">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('expenses.store') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Amount -->
                <div>
                    <label for="amount" class="block text-[13px] font-bold text-slate-700 mb-2">Amount</label>
                    <input type="number" name="amount" id="amount" required min="1" step="1" placeholder="0.00"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-[12px] text-[14px] text-slate-800 placeholder-[#cbd5e1] focus:outline-none focus:ring-4 focus:ring-[#0fa968]/5 focus:border-[#0fa968] transition-all">
                </div>

                <!-- Category -->
                <div>
                    <label for="category" class="block text-[13px] font-bold text-slate-700 mb-2">Category</label>
                    <div class="relative">
                        <select name="category" id="category" required
                                class="w-full pl-4 pr-10 py-3 bg-white border border-slate-200 rounded-[12px] text-[14px] text-[#0f172a] focus:outline-none focus:ring-4 focus:ring-[#0fa968]/5 focus:border-[#0fa968] appearance-none transition-all">
                            <option value="Food">Food</option>
                            <option value="Transport">Transport</option>
                            <option value="Logistics">Logistics</option>
                            <option value="Food & Refreshments">Food & Refreshments</option>
                            <option value="Internet Bundle">Internet Bundle</option>
                            <option value="Utilities">Utilities</option>
                            <option value="Entertainment">Entertainment</option>
                            <option value="Others">Others</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Date -->
                <div>
                    <label for="date" class="block text-[13px] font-bold text-slate-700 mb-2">Date</label>
                    <div class="relative">
                        <input type="date" name="date" id="date" required value="{{ date('Y-m-d') }}"
                               class="custom-date-input w-full pl-4 pr-10 py-3 bg-white border border-slate-200 rounded-[12px] text-[14px] text-slate-800 focus:outline-none focus:ring-4 focus:ring-[#0fa968]/5 focus:border-[#0fa968] transition-all">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Note (optional) -->
                <div>
                    <label for="description" class="block text-[13px] font-bold text-slate-700 mb-2">Note (optional)</label>
                    <input type="text" name="description" id="description" placeholder="Lunch with team"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-[12px] text-[14px] text-slate-800 placeholder-[#cbd5e1] focus:outline-none focus:ring-4 focus:ring-[#0fa968]/5 focus:border-[#0fa968] transition-all">
                </div>

                <!-- Save button -->
                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-6 py-3.5 bg-[#0fa968] hover:bg-[#0d9258] text-white text-[14px] font-bold rounded-[12px] transition-colors cursor-pointer shadow-sm">
                        Save expense
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Toast Notification -->
    @if (session('success'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 4000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:translate-x-4"
             x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed bottom-5 right-5 z-50 bg-[#0fa968] text-white px-5 py-3 rounded-2xl shadow-lg flex items-center gap-3 border border-[#0d9258] max-w-sm">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-[14px] font-semibold">{{ session('success') }}</span>
            <button @click="show = false" class="ml-auto text-white/80 hover:text-white transition-colors cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    @endif
</body>
</html>
