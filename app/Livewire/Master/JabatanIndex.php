<?php

namespace App\Livewire\Master;

use App\Models\Departemen;
use App\Models\Jabatan;
use GuzzleHttp\Psr7\Query;
use Illuminate\Database\QueryException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

use function Symfony\Component\String\s;

#[Layout('components.layouts.app')]
#[Title('Manajemen Jabatan')]
class JabatanIndex extends Component
{
    use WithPagination;

    //properti form
    public $jabatan_id, $departemen_id, $nama, $gaji_pokok;

    //properti ui
    public $isOpen = false;
    public $search = '';
    
    public function render()
    {
        //Query dengan relasi Departemen

        $jabatans = Jabatan::with('departemen')
            ->where('nama', 'like', '%'.$this->search.'%')
            ->orWhereHas('departemen', function ($query){
                $query->where('nama', 'like', '%'.$this->search.'%');
            })->orderBy('id', 'desc')
            ->paginate(10);

            //Mngambil nama untuk dropdown
            $departemens = Departemen::orderBy('nama', 'asc')->get();

        return view('livewire.master.jabatan-index', compact('jabatans', 'departemens'));
    }
    // Membuka modal
    public function openModal()
    {
       $this->isOpen = true;
    }
    //Menutup modal
    public function closeModal()
    {
       $this->isOpen = false;
    }
    //reset form
    public function resetInputFields()
    {
       $this->jabatan_id = null;
       $this->departemen_id = '';
       $this->nama = '';
       $this->gaji_pokok = '';
    }
    //Membuka modal
    public function create()
    {
       $this->resetInputFields();
       $this->openModal();
    }

    //Reset pagination
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function store()
    {
        $this->validate([
            'departemen_id' => 'required|exists:departemen,id',
            'nama' => 'required|string|max:100',
            'gaji_pokok' => 'required|numeric|min:0',
        ]);

        Jabatan::updateOrCreate(
            ['id'=> $this->jabatan_id],
            [
                'departemen_id' => $this->departemen_id,
                'nama' => $this->nama,
                'gaji_pokok' => $this->gaji_pokok,
            ]
        );

        session()->flash('message', $this->jabatan_id ? 'Data Jabatan berhasil diperbarui.' : 'Data Jabatan berhasil dibuat.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $this->jabatan_id = $id;
        $this->departemen_id = $jabatan->departemen_id;
        $this->nama = $jabatan->nama;
        $this->gaji_pokok = $jabatan->gaji_pokok;

        $this->openModal();
    }

    public function delete($id)
    {
        try {
            $jabatan = Jabatan::findOrFail($id);
            $jabatan->delete();
            session()->flash('message', 'Data Jabatan berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') { // Kode error untuk integrity constraint violation
                session()->flash('error', 'Data Jabatan tidak dapat dihapus karena memiliki relasi dengan data Karyawan.');
            } else {
                session()->flash('error', 'Terjadi kesalahan saat menghapus data Jabatan.');
            }
        }
    }
}
