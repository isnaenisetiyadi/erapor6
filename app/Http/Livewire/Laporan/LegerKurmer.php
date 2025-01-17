<?php

namespace App\Http\Livewire\Laporan;

use Livewire\Component;
use App\Models\Peserta_didik;
use App\Models\Pembelajaran;
use App\Models\Rombongan_belajar;

class LegerKurmer extends Component
{
    public $show = FALSE;
    public $semester_id;
    public $tingkat;
    public $rombongan_belajar_id;
    public $rombongan_belajar = [];
    public $data_siswa = [];
    public $data_pembelajaran = [];

    public function render()
    {
        $this->semester_id = session('semester_id');
        if($this->loggedUser()->hasRole('waka', session('semester_id'))){
            $tombol_add = NULL;
        } elseif($this->check_walas()){
            $rombel = $this->loggedUser()->guru->rombongan_belajar;
            $this->rombongan_belajar_id = ($rombel) ? $this->loggedUser()->guru->rombongan_belajar->rombongan_belajar_id : NULL;
            $link = ($rombel) ? route('unduhan.unduh-leger-nilai-kurmer', ['rombongan_belajar_id' => $this->rombongan_belajar_id]) : 'javascript:void(0)';
            $tombol_add = [
                'wire' => '',
                'link' => $link,
                'color' => 'success',
                'text' => 'Unduh Legger'
            ];
        }
        return view('livewire.laporan.leger-kurmer', [
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Laporan'], ['name' => "Unduh Leger"]
            ],
            'tombol_add' => $tombol_add,
        ]);
    }
    public function mount(){
        if($this->loggedUser()->hasRole('waka', session('semester_id'))){
            $this->show = FALSE;
        } elseif($this->check_walas()){
            $this->data_siswa = Peserta_didik::whereHas('anggota_rombel', function($query){
                $query->where('rombongan_belajar_id', $this->loggedUser()->guru->rombongan_belajar->rombongan_belajar_id);
            })->with(['anggota_rombel' => function($query){
                $query->where('rombongan_belajar_id', $this->loggedUser()->guru->rombongan_belajar->rombongan_belajar_id);
            }])->orderBy('nama')->get();
            $this->data_pembelajaran = Pembelajaran::where(function($query){
                $query->where('rombongan_belajar_id', $this->loggedUser()->guru->rombongan_belajar->rombongan_belajar_id);
                $query->whereNotNull('kelompok_id');
                $query->whereNotNull('no_urut');
            })->orderBy('kelompok_id', 'asc')->orderBy('no_urut', 'asc')->get();
            $this->show = TRUE;
        }
    }
    private function loggedUser(){
        return auth()->user();
    }
    private function check_walas($rombongan_belajar_id = NULL){
        if($rombongan_belajar_id){
            $rombongan_belajar = Rombongan_belajar::find($rombongan_belajar_id);
            if($rombongan_belajar->guru_id == $this->loggedUser()->guru_id){
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            if($this->loggedUser()->hasRole('wali', session('semester_id'))){
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }
    public function updatedTingkat(){
        $this->reset(['rombongan_belajar_id', 'data_siswa']);
        if($this->tingkat){
            $data_rombongan_belajar = Rombongan_belajar::select('rombongan_belajar_id', 'nama')->where(function($query){
                $query->where('tingkat', $this->tingkat);
                $query->where('semester_id', session('semester_aktif'));
                $query->where('sekolah_id', session('sekolah_id'));
                $query->where('jenis_rombel', 1);
                $query->whereHas('kurikulum', function($query){
                    $query->where('nama_kurikulum', 'ILIKE', '%Merdeka%');
                });
            })->get();
            $this->dispatchBrowserEvent('data_rombongan_belajar', ['data_rombongan_belajar' => $data_rombongan_belajar]);
        }
    }
    public function updatedRombonganBelajarId(){
        $this->reset(['data_siswa', 'data_pembelajaran']);
        $this->data_siswa = Peserta_didik::whereHas('anggota_rombel', function($query){
            $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
        })->with(['anggota_rombel' => function($query){
            $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
        }])->orderBy('nama')->get();
        $this->data_pembelajaran = Pembelajaran::where(function($query){
            $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            $query->whereNotNull('kelompok_id');
            $query->whereNotNull('no_urut');
        })->orderBy('kelompok_id', 'asc')->orderBy('no_urut', 'asc')->get();
        $this->show = TRUE;
    }
}
