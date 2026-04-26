<div>
    {{-- HEADER SECTION --}}
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Data Karyawan</h2>
            <p class="text-sm text-gray-500">Manajemen daftar lengkap seluruh karyawan perusahaan.</p>
        </div>
        <button wire:click="create" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-lg shadow-blue-200 transition-all active:scale-95">
            + Tambah Karyawan
        </button>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-800 rounded-r-md shadow-sm">
            {{ session('message') }}
        </div>
    @endif

    {{-- TABLE SECTION --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <div class="w-full max-w-md relative">
                <input type="text" placeholder="Cari NIK atau Nama..." wire:model.live.debounce.300ms="search"
                    class="w-full pl-4 pr-10 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-sm">
                <span class="absolute right-3 top-2.5 text-gray-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 uppercase text-[11px] font-bold tracking-wider">
                        <th class="px-6 py-4 border-b border-gray-100">NIK</th>
                        <th class="px-6 py-4 border-b border-gray-100">Nama</th>
                        <th class="px-6 py-4 border-b border-gray-100">Departemen</th>
                        <th class="px-6 py-4 border-b border-gray-100">Jabatan</th>
                        <th class="px-6 py-4 border-b border-gray-100 text-center">Status</th>
                        <th class="px-6 py-4 border-b border-gray-100 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($karyawans as $karyawan)
                        <tr class="hover:bg-blue-50/30 transition-colors">
                            <td class="px-6 py-4 text-sm font-bold text-gray-700">{{ $karyawan->nik }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $karyawan->nama }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $karyawan->departemen->nama ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $karyawan->jabatan->nama ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                @if (strtolower($karyawan->status) == 'aktif')
                                    <span class="bg-green-100 text-green-700 py-1 px-3 rounded-full text-[10px] font-bold uppercase">Aktif</span>
                                @else
                                    <span class="bg-red-100 text-red-700 py-1 px-3 rounded-full text-[10px] font-bold uppercase">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-center font-medium">
                                <div class="flex justify-center gap-3">
                                    <button wire:click="showDetail({{ $karyawan->id }})" class="text-blue-600 hover:underline">Detail</button>
                                    <button wire:click="edit({{ $karyawan->id }})" class="text-orange-500 hover:underline">Edit</button>
                                    <button wire:click="delete({{ $karyawan->id }})" wire:confirm="Hapus data ini?" class="text-red-600 hover:underline">Hapus</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-10 text-sm text-gray-400 text-center italic">Data tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100 bg-gray-50/30">
            {{ $karyawans->links() }}
        </div>
    </div>

    {{-- MODAL FORM - VERSI FIX --}}
    @if ($isFormModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="closeFormModal"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-6xl overflow-hidden z-10 flex flex-col max-h-[92vh]">
                
                <div class="px-8 py-5 border-b border-gray-100 flex justify-between items-center bg-white">
                    <h3 class="text-xl font-extrabold text-gray-800">{{ $karyawan_id ? '📝 Edit Data Karyawan' : '✨ Tambah Karyawan Baru' }}</h3>
                    <button wire:click="closeFormModal" class="text-gray-400 hover:text-gray-600"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12" /></svg></button>
                </div>

                <form wire:submit.prevent="store" class="overflow-y-auto p-8 bg-gray-50/30">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        
                        {{-- 1. DATA PRIBADI --}}
                        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm min-h-[520px]">
                            <h4 class="font-bold text-gray-700 mb-6 border-b pb-3 uppercase text-xs tracking-widest">1. Data Pribadi</h4>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-1.5">NIK</label>
                                    <input type="text" wire:model="nik" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                    @error('nik') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-1.5">NAMA LENGKAP</label>
                                    <input type="text" wire:model="nama" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-1.5">EMAIL</label>
                                    <input type="email" wire:model="email" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-1.5">TELEPON</label>
                                    <input type="text" wire:model="telepon" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-1.5">JENIS KELAMIN</label>
                                    <select wire:model="jenis_kelamin" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm">
                                        <option value="">-- Pilih --</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- 2. DATA PEKERJAAN --}}
                        <div class="bg-blue-50/50 p-6 rounded-xl border border-blue-100 shadow-sm min-h-[520px]">
                            <h4 class="font-bold text-blue-700 mb-6 border-b border-blue-100 pb-3 uppercase text-xs tracking-widest">2. Pekerjaan</h4>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-blue-600 mb-1.5">DEPARTEMEN</label>
                                    <select wire:model.live="departemen_id" class="w-full px-4 py-2.5 border border-blue-200 rounded-lg text-sm bg-white focus:border-blue-500">
                                        <option value="">-- Pilih Departemen --</option>
                                        @foreach ($departemens_dropdown as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-blue-600 mb-1.5">JABATAN</label>
                                    <select wire:model.live="jabatan_id" class="w-full px-4 py-2.5 border border-blue-200 rounded-lg text-sm bg-white focus:border-blue-500">
                                        <option value="">-- Pilih Jabatan --</option>
                                        @foreach ($jabatans_dropdown as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                        @endforeach
                                    </select>
                                    @if(empty($departemen_id)) <span class="text-[10px] text-blue-400 mt-1 block italic">* Pilih departemen dulu</span> @endif
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-blue-600 mb-1.5">TANGGAL MASUK</label>
                                    <input type="date" wire:model="tanggal_masuk" class="w-full px-4 py-2.5 border border-blue-200 rounded-lg text-sm bg-white">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-blue-600 mb-1.5">STATUS KERJA</label>
                                    <select wire:model="status" class="w-full px-4 py-2.5 border border-blue-200 rounded-lg text-sm bg-white">
                                        <option value="aktif">Aktif</option>
                                        <option value="non-aktif">Non-Aktif</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- 3. DATA FINANSIAL --}}
                        <div class="bg-green-50/50 p-6 rounded-xl border border-green-100 shadow-sm min-h-[520px]">
                            <h4 class="font-bold text-green-700 mb-6 border-b border-green-100 pb-3 uppercase text-xs tracking-widest">3. Finansial</h4>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-green-600 mb-1.5">BANK</label>
                                    <input type="text" wire:model="bank" placeholder="BCA / Mandiri" class="w-full px-4 py-2.5 border border-green-200 rounded-lg text-sm bg-white">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-green-600 mb-1.5">NOMOR REKENING</label>
                                    <input type="text" wire:model="no_rekening" class="w-full px-4 py-2.5 border border-green-200 rounded-lg text-sm bg-white">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-green-600 mb-1.5">GAJI POKOK (Rp)</label>
                                    <input type="number" wire:model="gaji_pokok" class="w-full px-4 py-2.5 border border-green-200 rounded-lg text-sm bg-white font-bold text-green-700">
                                    <span class="text-[9px] text-green-500 italic">* Otomatis terisi sesuai jabatan</span>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-green-600 mb-1.5">TUNJANGAN (Rp)</label>
                                    <input type="number" wire:model="tunjangan" class="w-full px-4 py-2.5 border border-green-200 rounded-lg text-sm bg-white font-bold text-green-700">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- BUTTONS --}}
                    <div class="mt-10 flex justify-end gap-3 border-t border-gray-100 pt-6">
                        <button type="button" wire:click="closeFormModal" class="px-6 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 font-bold transition-all">Batal</button>
                        <button type="submit" class="px-8 py-2.5 bg-blue-600 border border-transparent rounded-lg text-white hover:bg-blue-700 font-bold shadow-lg shadow-blue-100 transition-all">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- MODAL DETAIL --}}
    @if ($isDetailModalOpen && $karyawanDetail)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="closeDetailModal"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl z-10 overflow-hidden">
                <div class="p-8">
                    <div class="flex items-center gap-5 mb-8">
                        <div class="h-20 w-20 bg-blue-600 text-white rounded-2xl flex items-center justify-center text-3xl font-black shadow-lg">{{ substr($karyawanDetail->nama, 0, 1) }}</div>
                        <div>
                            <h4 class="text-3xl font-black text-gray-900 tracking-tight">{{ $karyawanDetail->nama }}</h4>
                            <p class="text-gray-500 font-bold">NIK: {{ $karyawanDetail->nik }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Info Kontak</span>
                            <p class="text-sm font-bold text-gray-700">{{ $karyawanDetail->email }}</p>
                            <p class="text-sm text-gray-500">{{ $karyawanDetail->telepon }}</p>
                        </div>
                        <div class="p-4 bg-blue-50 rounded-xl border border-blue-100">
                            <span class="text-[10px] font-black text-blue-400 uppercase tracking-widest block mb-2">Penempatan</span>
                            <p class="text-sm font-bold text-blue-700">{{ $karyawanDetail->departemen->nama ?? '-' }}</p>
                            <p class="text-sm text-blue-500">{{ $karyawanDetail->jabatan->nama ?? '-' }}</p>
                        </div>
                    </div>
                    <button wire:click="closeDetailModal" class="mt-8 w-full py-3 bg-gray-900 text-white font-bold rounded-xl hover:bg-black transition-all">Tutup Profil</button>
                </div>
            </div>
        </div>
    @endif
</div>