<?php

namespace App\Livewire\Transaksi;

use App\Models\Karyawan;
use App\Models\Penggajian;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Proses Penggajian')]


class PenggajianIndex extends Component
{
    use WithPagination;

    public $bulan;
    public $tahun;
    public $search = '';

    public function mount()
    {
        $this->bulan = now()->format('m');
        $this->tahun = now()->format('Y');
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }

    //reset page jika user mengganti bulan dan tahun
    public function updatedBulan(){$this->resetPage();}
    public function updatedTahun(){$this->resetPage();}
    public function render()
    {
        $penggajians = Penggajian::with('karyawan.departemen', 'karyawan.jabatan')
            ->where('bulan', $this->bulan)
            ->where('tahun', $this->tahun)
            ->when($this->search, function ($query){
                $query->whereHas('karyawan', function ($q) {
                    $q->where('nama', 'like', '%'.$this->search.'%')
                      ->orWhere('nik', 'like', '%'.$this->search.'%');
                });

            })
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('livewire.transaksi.penggajian-index', compact('penggajians'));
    }

    //fungsi untuk membuka detaiul penggajian
    public function generatePayroll()
    {
        // 1. cek apakah sudah ada penggajian untuk bulan/tahun
        $sudahAda = Penggajian::where('bulan', $this->bulan)
        ->where('tahun', $this->tahun)
        ->exists();

        if ($sudahAda) {
            session()->flash('error', 'Gagal gaji untuk periode' . $this->bulan . '/' . $this->tahun . ' karena sudah ada data penggajian');
            return;
        }

        // 2 Ambil semua karyawan aktif
        $karyawans = Karyawan::where('status', 'aktfi')->get();
        if($karyawans->isEmpty()) {
            session()->flash('error', 'Gagal gaji untuk periode' . $this->bulan . '/' . $this->tahun . ' karena tidak ada karyawan aktif');
            return;
        }

        //3. Proses penggajian untuk setiap karyawan(looping/massal)
        $count = 0;
        foreach ($karyawans as $karyawan) {
            $potongan = ($karyawan->gaji_pokok + $karyawan->tunjangan) * 0.03;
            Penggajian::create([
                'karyawan_id' => $karyawan->id,
                'bulan' => $this->bulan,
                'tahun' => $this->tahun,
                'tanggal_proses' =>date('Y-m-d'),
                'gaji_pokok' => $karyawan->gaji_pokok,
                'tunjangan' => $karyawan->tunjangan,
                'potongan' => $potongan,
                'total_gaji' => ($karyawan->gaji_pokok + $karyawan->tunjangan - $potongan)
            ]);
            $count++;
        }
        session()->flash('success', 'Berhasil memproses ' . $count . ' penggajian untuk periode ' . $this->bulan . '/' . $this->tahun);
    }

    public function delete($id)
    {
        Penggajian::findOrFail($id)->delete();
        session()->flash('message', 'Data penggajian berhasil dihapus');
    }
}
