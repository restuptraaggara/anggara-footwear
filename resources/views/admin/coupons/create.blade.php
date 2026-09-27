@extends('admin.layouts.app')

@section('title', 'Tambah Kupon Baru')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Kupon Baru</h1>
            <p class="text-xs text-gray-500 font-light">Buat voucher diskon belanja untuk promosi</p>
        </div>
        <a href="{{ route('admin.coupons.index') }}" class="text-xs text-gray-500 hover:text-gray-900 font-semibold">← Kembali</a>
    </div>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl text-xs space-y-1">
        @foreach($errors->all() as $err)
        <p>• {{ $err }}</p>
        @endforeach
    </div>
    @endif

    <div class="bg-white p-8 rounded-3xl border border-earth-100 shadow-sm">
        <form action="{{ route('admin.coupons.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                    Kode Kupon Promo *
                </label>
                <input type="text" name="code" value="{{ old('code') }}" required placeholder="Misal: PROMOLEBARAN / DISKON20"
                       class="w-full uppercase font-mono text-sm border border-earth-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-1 focus:ring-gray-900 bg-earth-50/50">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                        Tipe Diskon *
                    </label>
                    <select name="type" required class="w-full text-sm border border-earth-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-1 focus:ring-gray-900 bg-earth-50/50">
                        <option value="percentage" {{ old('type') === 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                        <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>Nominal Tetap (Rp)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                        Nilai Diskon *
                    </label>
                    <input type="number" name="value" value="{{ old('value') }}" required step="0.01" min="1" placeholder="Misal: 10 untuk 10% atau 50000"
                           class="w-full text-sm border border-earth-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-1 focus:ring-gray-900 bg-earth-50/50">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                        Minimal Belanja (Rp)
                    </label>
                    <input type="number" name="min_order_amount" value="{{ old('min_order_amount', 0) }}" step="1000" min="0" placeholder="0 jika tanpa syarat"
                           class="w-full text-sm border border-earth-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-1 focus:ring-gray-900 bg-earth-50/50">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                        Batas Maks. Pemakaian (Kuota)
                    </label>
                    <input type="number" name="max_uses" value="{{ old('max_uses') }}" min="1" placeholder="Kosongkan jika tak terbatas"
                           class="w-full text-sm border border-earth-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-1 focus:ring-gray-900 bg-earth-50/50">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                    Tanggal Kedaluwarsa (Opsional)
                </label>
                <input type="date" name="expires_at" value="{{ old('expires_at') }}"
                       class="w-full text-sm border border-earth-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-1 focus:ring-gray-900 bg-earth-50/50">
            </div>

            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                       class="rounded border-earth-300 text-gray-900 focus:ring-gray-900 h-4 w-4">
                <label for="is_active" class="text-xs font-semibold text-gray-700 cursor-pointer">
                    Aktifkan Kupon Ini Sekarang
                </label>
            </div>

            <div class="flex gap-3 pt-4 border-t border-earth-100">
                <button type="submit" class="px-8 py-3 bg-gray-900 hover:bg-forest-800 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition-colors shadow-sm">
                    Simpan Kupon
                </button>
                <a href="{{ route('admin.coupons.index') }}" class="px-6 py-3 bg-earth-100 hover:bg-earth-200 text-gray-700 rounded-xl text-xs font-semibold uppercase tracking-wider transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
