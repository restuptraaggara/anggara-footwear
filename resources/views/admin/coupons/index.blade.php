@extends('admin.layouts.app')

@section('title', 'Manajemen Kupon & Diskon')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Kupon & Voucher Diskon</h1>
            <p class="text-xs text-gray-500 font-light mt-0.5">Kelola kode promo dan potongan harga untuk pelanggan</p>
        </div>
        <a href="{{ route('admin.coupons.create') }}" 
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 hover:bg-forest-800 text-white rounded-xl text-xs font-semibold uppercase tracking-wider transition-colors shadow-sm">
            <span>+ Tambah Kupon Baru</span>
        </a>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
    <div class="bg-forest-50 border border-forest-200 text-forest-800 px-4 py-3 rounded-xl text-xs flex items-center justify-between">
        <span>✓ {{ session('success') }}</span>
    </div>
    @endif

    {{-- Coupons Table --}}
    <div class="bg-white rounded-2xl border border-earth-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-earth-50 text-gray-700 uppercase tracking-wider font-semibold border-b border-earth-100 text-[11px]">
                    <tr>
                        <th class="px-6 py-4">Kode Kupon</th>
                        <th class="px-6 py-4">Tipe & Nilai Diskon</th>
                        <th class="px-6 py-4">Min. Belanja</th>
                        <th class="px-6 py-4">Pemakaian</th>
                        <th class="px-6 py-4">Kedaluwarsa</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-earth-50">
                    @forelse($coupons as $coupon)
                    <tr class="hover:bg-earth-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-mono font-bold text-sm text-gray-900 bg-earth-100 px-2.5 py-1 rounded-lg border border-earth-200">
                                {{ $coupon->code }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-forest-800">
                                {{ $coupon->formatted_discount }}
                            </span>
                            <span class="text-[10px] text-gray-400 block uppercase">
                                {{ $coupon->type === 'percentage' ? 'Persentase' : 'Potongan Tetap' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $coupon->min_order_amount > 0 ? 'Rp ' . number_format($coupon->min_order_amount, 0, ',', '.') : 'Tanpa Minimum' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-medium text-gray-900">{{ $coupon->used_count }}</span>
                            <span class="text-gray-400">/ {{ $coupon->max_uses ?? '∞' }} kali</span>
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            {{ $coupon->expires_at ? $coupon->expires_at->format('d M Y') : 'Selamanya' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <form action="{{ route('admin.coupons.toggle', $coupon->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider transition-all {{ $coupon->is_active ? 'bg-forest-100 text-forest-800 hover:bg-forest-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                    {{ $coupon->is_active ? '● Aktif' : '○ Nonaktif' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="text-earth-700 hover:text-gray-900 font-semibold underline">Edit</a>
                            <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kupon ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 font-semibold underline ml-1">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400 font-light">
                            Belum ada data kupon voucher. Klik tombol "+ Tambah Kupon Baru" untuk membuat promo pertama Anda.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($coupons->hasPages())
        <div class="px-6 py-4 border-t border-earth-100 bg-earth-50">
            {{ $coupons->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
