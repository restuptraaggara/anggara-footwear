@extends('admin.layouts.app')

@section('title', 'Dashboard Overview')
@section('header', 'Dashboard Overview')

@section('content')
<div class="space-y-8">

    {{-- 4 Metric Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        {{-- Card 1: Total Revenue --}}
        <div class="bg-white rounded-3xl p-6 border border-earth-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-semibold uppercase tracking-wider text-earth-600">Total Pendapatan</span>
                <div class="w-10 h-10 rounded-2xl bg-earth-50 flex items-center justify-center text-lg">💰</div>
            </div>
            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 font-light mt-1">Dari pesanan lunas & terproses</p>
        </div>

        {{-- Card 2: Total Orders --}}
        <div class="bg-white rounded-3xl p-6 border border-earth-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-semibold uppercase tracking-wider text-earth-600">Total Pesanan</span>
                <div class="w-10 h-10 rounded-2xl bg-earth-50 flex items-center justify-center text-lg">📦</div>
            </div>
            <div class="flex items-baseline gap-2">
                <p class="text-2xl font-bold text-gray-900">{{ $totalOrders }}</p>
                @if($pendingOrders > 0)
                <span class="text-xs text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full font-medium">{{ $pendingOrders }} pending</span>
                @endif
            </div>
            <p class="text-xs text-gray-400 font-light mt-1">Seluruh riwayat transaksi</p>
        </div>

        {{-- Card 3: Total Active Products --}}
        <div class="bg-white rounded-3xl p-6 border border-earth-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-semibold uppercase tracking-wider text-earth-600">Produk Aktif</span>
                <div class="w-10 h-10 rounded-2xl bg-earth-50 flex items-center justify-center text-lg">👟</div>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $totalProducts }} Model</p>
            <p class="text-xs text-gray-400 font-light mt-1">Tersedia di katalog online</p>
        </div>

        {{-- Card 4: Low Stock Alert --}}
        <div class="bg-white rounded-3xl p-6 border border-earth-100 shadow-sm {{ $lowStockCount > 0 ? 'ring-2 ring-amber-400/50' : '' }}">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-semibold uppercase tracking-wider text-amber-600">Stok Menipis (<5)</span>
                <div class="w-10 h-10 rounded-2xl bg-amber-50 flex items-center justify-center text-lg">⚠️</div>
            </div>
            <p class="text-2xl font-bold {{ $lowStockCount > 0 ? 'text-amber-600' : 'text-gray-900' }}">{{ $lowStockCount }} Varian</p>
            <p class="text-xs text-gray-400 font-light mt-1">Perlu restock segera</p>
        </div>

    </div>

    {{-- Two Columns Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Recent Orders (2 Cols) --}}
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 border border-earth-100 shadow-sm space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-earth-100">
                <div>
                    <h2 class="text-base font-semibold text-gray-900">Pesanan Terbaru</h2>
                    <p class="text-xs text-gray-400 font-light mt-0.5">5 transaksi pesanan masuk terakhir</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold uppercase tracking-wider text-earth-700 hover:text-gray-900">
                    Lihat Semua →
                </a>
            </div>

            @if($recentOrders->isEmpty())
                <div class="text-center py-12 text-gray-400 text-xs">Belum ada transaksi pesanan masuk.</div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-gray-400 uppercase tracking-wider border-b border-earth-50 pb-2">
                                <th class="pb-3 font-semibold">No. Order</th>
                                <th class="pb-3 font-semibold">Pelanggan</th>
                                <th class="pb-3 font-semibold">Total</th>
                                <th class="pb-3 font-semibold">Status</th>
                                <th class="pb-3 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-earth-50">
                            @foreach($recentOrders as $ord)
                            <tr>
                                <td class="py-3.5 font-semibold text-gray-900">
                                    <a href="{{ route('admin.orders.show', $ord->id) }}" class="hover:underline">
                                        {{ $ord->order_number }}
                                    </a>
                                </td>
                                <td class="py-3.5 text-gray-600">
                                    <p class="font-medium text-gray-900">{{ $ord->customer_name }}</p>
                                    <p class="text-[11px] text-gray-400">{{ $ord->city }}</p>
                                </td>
                                <td class="py-3.5 font-semibold text-gray-900">
                                    Rp {{ number_format($ord->total, 0, ',', '.') }}
                                </td>
                                <td class="py-3.5">
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
                                    <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-semibold uppercase tracking-wider border {{ $badgeColors[$ord->status] ?? 'bg-gray-50 text-gray-700' }}">
                                        {{ $ord->status }}
                                    </span>
                                </td>
                                <td class="py-3.5 text-right">
                                    <a href="{{ route('admin.orders.show', $ord->id) }}"
                                       class="text-xs font-semibold text-gray-900 hover:text-earth-700 underline underline-offset-2">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Low Stock Alert Box (1 Col) --}}
        <div class="bg-white rounded-3xl p-6 border border-earth-100 shadow-sm space-y-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-earth-100">
                    <div>
                        <h2 class="text-base font-semibold text-gray-900">Peringatan Stok</h2>
                        <p class="text-xs text-gray-400 font-light mt-0.5">Varian dengan sisa stok < 5</p>
                    </div>
                    <span class="text-xs px-2 py-0.5 bg-amber-100 text-amber-800 rounded-full font-bold">
                        {{ $lowStockCount }}
                    </span>
                </div>

                @if($lowStockVariants->isEmpty())
                    <div class="py-12 text-center text-xs text-gray-400">
                        <p class="text-2xl mb-2">✅</p>
                        Semua stok varian dalam kondisi aman.
                    </div>
                @else
                    <div class="space-y-3 mt-4 overflow-y-auto max-h-72">
                        @foreach($lowStockVariants->take(6) as $v)
                        <div class="flex items-center justify-between p-3 rounded-2xl bg-earth-50 border border-earth-100">
                            <div class="truncate mr-2">
                                <p class="text-xs font-semibold text-gray-900 truncate">{{ $v->product->name }}</p>
                                <p class="text-[11px] text-gray-500 font-light">{{ $v->color_name }} &bull; Size {{ $v->size }}</p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <span class="px-2 py-1 rounded-lg text-xs font-bold {{ $v->stock === 0 ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $v->stock }} pasang
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="pt-4 border-t border-earth-100">
                <a href="{{ route('admin.products.index') }}"
                   class="block w-full py-3 bg-gray-900 hover:bg-earth-800 text-white text-center text-xs tracking-widest uppercase font-semibold rounded-xl transition-colors">
                    Kelola Stok Katalog
                </a>
            </div>
        </div>

    </div>

</div>
@endsection
