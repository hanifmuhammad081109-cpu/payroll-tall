<?php

namespace App\Livewire\Transaksi;

use App\Models\Penggajian;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.print')]
#[Title('Cetak Slip Transaksi')]
class CetakSlip extends Component
{
    public $penggajian;

    public function mount($id)
    {
        //ambil data gaji beserta relasi karyawan departemen dan jabatan
        $this->penggajian = Penggajian::with(['karyawan', 'departemen', 'jabatan'])->findOrFail($id);
    }
    public function render()
    {
        return view('livewire.transaksi.cetak-slip');
    }
}
