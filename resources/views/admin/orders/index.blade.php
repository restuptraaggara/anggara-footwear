@extends('admin.layouts.app')

@section('title', 'Daftar Pesanan Masuk')
@section('header', 'Manajemen Pesanan')

@section('content')
<div class="space-y-6">

    {{-- Filter & Search Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        
        {{-- Search Form --}}
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari No. Order / Nama / Email…"
                       class="bg-white border border-earth-200 rounded-xl pl-9 pr-4 py-2.5 text-xs text-gray-900 focus:outline-none focus:border-gray-900 shadow-sm w-64">
                <div class="absolute left-3 top-3 text-gray-400">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            <select name="status" onchange="this.form.submit()"
                    class="bg-white border border-earth-200 rounded-xl px-3 py-2.5 text-xs text-gray-700 focus:outline-none focus:border-gray-900 shadow-sm">
                <option value="">Semua Status</option>
                @foreach($statuses as $st)
                    <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>
                        {{ strtoupper($st) }}
                    </option>
                @endforeach
            </select>

            @if(request('search') || request('status'))
                <a href="{{ route('admin.orders.index') }}" class="text-xs text-red-500 hover:underline">Reset</a>
            @endif
        </form>

                {{-- Export CSV Action --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.orders.export') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-forest-800 hover:bg-forest-900 text-white rounded-xl text-xs font-semibold uppercase tracking-wider transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Export CSV</span>
            </a>
        </div>

        {{-- Status Badges Summary --}}
        <div class="flex items-center gap-2 overflow-x-auto pb-1">
            @foreach(['pending' => 'bg-amber-50 text-amber-800 border-amber-200',
                      'paid'    => 'bg-blue-50 text-blue-800 border-blue-200',
                      'shipped' => 'bg-indigo-50 text-indigo-800 border-indigo-200',
                      'completed' => 'bg-green-50 text-green-800 border-green-200'] as $sName => $sColor)
                <a href="{{ route('admin.orders.index', ['status' => $sName]) }}"
                   class="px-3 py-1 rounded-full text-[10px] font-semibold uppercase tracking-wider border {{ $sColor }} hover:opacity-80 transition-opacity">
                    {{ $sName }}
                </a>
            @endforeach
        </div>

    </div>

    {{-- Orders Table --}}
    <div class="bg-white rounded-3xl border border-earth-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-earth-50/50 text-gray-400 uppercase tracking-wider border-b border-earth-100">
                    <tr>
                        <th class="py-4 px-6 font-semibold">No. Order</th>
                        <th class="py-4 px-4 font-semibold">Tanggal</th>
                        <th class="py-4 px-4 font-semibold">Pelanggan</th>
                        <th class="py-4 px-4 font-semibold">Item & Qty</th>
                        <th class="py-4 px-4 font-semibold">Metode Bayar</th>
                        <th class="py-4 px-4 font-semibold">Total</th>
                        <th class="py-4 px-4 font-semibold">Status</th>
                        <th class="py-4 px-6 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-earth-100">
                    @forelse($orders as $ord)
                    @php
                        $badgeColors = [
                            'pending'    => 'bg-amber-50 text-amber-700 border-amber-200',
                            'paid'       => 'bg-blue-50 text-blue-700 border-blue-200',
                            'processing' => 'bg-purple-50 text-purple-700 border-purple-200',
                            'shipped'    => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                            'completed'  => 'bg-green-50 text-green-700 border-green-200',
                            'cancelled'  => 'bg-red-50 text-red-700 border-red-200',
                        ];
                    @endphp
                    <tr class="hover:bg-earth-50/40 transition-colors">
                        
                        {{-- Order Number --}}
                        <td class="py-4 px-6 font-bold text-gray-900">
                            <a href="{{ route('admin.orders.show', $ord->id) }}" class="hover:text-earth-700">
                                {{ $ord->order_number }}
                            </a>
                        </td>

                        {{-- Date --}}
                        <td class="py-4 px-4 text-gray-500 font-light">
                            {{ $ord->created_at->format('d M Y, H:i') }}
                        </td>

                        {{-- Customer --}}
                        <td class="py-4 px-4 text-gray-700">
                            <p class="font-semibold text-gray-900">{{ $ord->customer_name }}</p>
                            <p class="text-[11px] text-gray-400 truncate">{{ $ord->customer_email }}</p>
                        </td>

                        {{-- Items Summary --}}
                        <td class="py-4 px-4 text-gray-600">
                            <p class="font-medium">{{ $ord->items->sum('quantity') }} pasang</p>
                            <p class="text-[11px] text-gray-400 truncate max-w-xs">
                                {{ $ord->items->pluck('product_name')->unique()->join(', ') }}
                            </p>
                        </td>

                        {{-- Payment Method --}}
                        <td class="py-4 px-4 font-medium uppercase text-gray-700">
                            <span class="inline-flex items-center gap-1">
                                <span>{{ $ord->payment_method === 'qris' ? '⬡' : '🏦' }}</span>
                                <span>{{ strtoupper($ord->payment_method) }}</span>
                            </span>
                        </td>

                        {{-- Total Amount --}}
                        <td class="py-4 px-4 font-bold text-gray-900 text-sm">
                            Rp {{ number_format($ord->total, 0, ',', '.') }}
                        </td>

                        {{-- Status Badge --}}
                        <td class="py-4 px-4">
                            <span class="inline-block px-3 py-1 rounded-full text-[10px] font-semibold uppercase tracking-wider border {{ $badgeColors[$ord->status] ?? 'bg-gray-50 text-gray-700' }}">
                                {{ $ord->status }}
                            </span>
                        </td>

                        {{-- Action Button --}}
                        <td class="py-4 px-6 text-right">
                            <a href="{{ route('admin.orders.show', $ord->id) }}"
                               class="inline-block px-3.5 py-1.5 bg-gray-900 hover:bg-earth-800 text-white rounded-lg text-xs font-semibold uppercase tracking-wider transition-colors shadow-sm">
                                Detail Pesanan
                            </a>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-gray-400 text-xs">
                            Tidak ada pesanan masuk yang sesuai.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
        <div class="p-6 border-t border-earth-100">
            {{ $orders->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
