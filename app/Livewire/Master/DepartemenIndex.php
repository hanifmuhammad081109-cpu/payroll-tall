<?php

namespace App\Livewire\Master;

use App\Models\Departemen;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

use function Livewire\str;

#[Layout('components.layouts.app')]
#[Title('Manajemen Departemen')]
class DepartemenIndex extends Component
{
    use WithPagination;

    //Property form
    public $departemen_id, $kode, $nama;

    //Property ui
    public $isOpen = false;
    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    //Reset pagination
    public function render()
    {
        $departemens = Departemen::where('nama', 'like', '%' . $this->search . '%')
            ->orWhere('kode', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);
        return view('livewire.master.departemen-index', compact('departemens'));
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
        $this->departemen_id = null;
        $this->kode = '';
        $this->nama = '';
    }
    //Membuka modal
    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    //create dan update data
    public function store()
    {
        $this->validate([
            'kode' => 'required|unique:departemen,kode' . $this->departemen_id,
            'nama' => 'required|string|max:255',
        ]);

        Departemen::updateOrCreate(
            ['id' => $this->departemen_id],
            [
                'kode' => strtoupper($this->kode), //Memastikan kode di simpan ke db adalah kapital
                'nama' => $this->nama, 
            ]
        );

        session()->flash('message', $this->departemen_id ? 'Data Departemen berhasil diperbarui.' : 'Data Departemen berhasil dibuat.');

        $this->closeModal();
        $this->resetInputFields();
    }

    //membuka modal edit
    public function edit($id)
    {
        $departemen = Departemen::findOrFail($id);
        $this->departemen_id;
        $this->kode = $departemen->kode;
        $this->nama = $departemen->nama;

        $this->openModal();
    }
    //memnghapus data
    public function delete($id)
    {
        $departemen = Departemen::withCount('jabatan')->findOrFail($id);
        
        if($departemen->jabatan_count > 0) {
            session()->flash('error', 'Data Departemen tidak dapat dihapus karena memiliki relasi dengan data Jabatan.');
            return;
        } 
        $departemen->delete();
        session()->flash('message', 'Data Departemen berhasil dihapus.');
    }
}