<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pocket - Simple expense tracker</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #fafafa;
        }
    </style>
</head>
<body class="text-slate-800 antialiased min-h-screen flex flex-col">

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
            
            <a href="{{ route('expenses.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#0fa968] hover:bg-[#0d9258] text-white text-[14px] font-semibold rounded-full transition-colors">
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
                    <div class="text-[32px] font-extrabold text-slate-800 tracking-tight">$0.00</div>
                </div>
                <div class="bg-white rounded-[20px] border border-gray-100 p-6 shadow-sm">
                    <h2 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">All Time</h2>
                    <div class="text-[32px] font-extrabold text-slate-800 tracking-tight">$487.50</div>
                </div>
                <div class="bg-white rounded-[20px] border border-gray-100 p-6 shadow-sm">
                    <h2 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Entries</h2>
                    <div class="text-[32px] font-extrabold text-slate-800 tracking-tight">6</div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="bg-[#f0f3f6] rounded-[16px] p-1.5 flex mb-8 max-w-[480px] mx-auto">
                <button class="flex-1 flex items-center justify-center gap-2 py-2.5 text-[14px] font-semibold text-slate-500 hover:text-slate-700 transition-colors rounded-[12px]">
                    <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"></path>
                    </svg>
                    Recent
                </button>
                <button class="flex-1 flex items-center justify-center gap-2 py-2.5 text-[14px] font-semibold text-[#0fa968] bg-white rounded-[12px] shadow-sm ring-1 ring-gray-100/50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    Insights
                </button>
            </div>

            <!-- Insights / Categories List -->
            <div class="bg-white rounded-[20px] border border-gray-100 p-8 shadow-sm mb-12">
                <h3 class="text-[16px] font-bold text-slate-800 mb-8">Spending by category</h3>
                
                <div class="space-y-7">
                    <!-- Category Item -->
                    <div>
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-[14px] font-bold text-slate-700">transpot</span>
                            <span class="text-[14px] font-bold text-slate-800">$447.50</span>
                        </div>
                        <div class="w-full bg-[#f1f5f9] rounded-full h-[10px]">
                            <div class="bg-[#2ace93] h-[10px] rounded-full" style="width: 90.7%"></div>
                        </div>
                    </div>

                    <!-- Category Item -->
                    <div>
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-[14px] font-bold text-slate-700">Housing</span>
                            <span class="text-[14px] font-bold text-slate-800">$40.00</span>
                        </div>
                        <div class="w-full bg-[#f1f5f9] rounded-full h-[10px]">
                            <div class="bg-[#2ace93] h-[10px] rounded-full" style="width: 8.2%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <p class="text-center text-[13px] font-medium text-slate-400">
                Data synced securely with your database.
            </p>
        </div>
    </main>
</body>
</html>
