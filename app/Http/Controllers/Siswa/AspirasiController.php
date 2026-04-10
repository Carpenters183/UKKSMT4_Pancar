<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use App\Models\Kategori;
use App\Models\Progres;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AspirasiController extends Controller
{
    public function create()
    {
        $kategoris = Kategori::all();
        return view('siswa.aspirasi.create', compact('kategoris'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'lokasi' => 'required|string|max:100',
            'keterangan' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $path = $file->storeAs('public/aspirasi_foto', $filename);
            $fotoPath = 'aspirasi_foto/' . $filename;
        }
        
        Aspirasi::create([
            'user_id' => auth()->id(),
            'id_kategori' => $request->id_kategori,
            'lokasi' => $request->lokasi,
            'keterangan' => $request->keterangan,
            'foto' => $fotoPath,
            'status' => 'Menunggu',
        ]);
        
        return redirect()->route('siswa.aspirasi.status')
            ->with('success', 'Aspirasi berhasil dikirim!');
    }
    
    // Halaman Status - Hanya menampilkan yang BELUM SELESAI (Menunggu dan Proses)
    public function status()
    {
        $aspirasi = Aspirasi::with('kategori')
            ->where('user_id', auth()->id())
            ->where('status', '!=', 'Selesai') // Hanya yang belum selesai
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('siswa.aspirasi.status', compact('aspirasi'));
    }
    
    public function detail($id)
    {
        $aspirasi = Aspirasi::with(['kategori', 'progres.user', 'historyStatus.user'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);
            
        return view('siswa.aspirasi.detail', compact('aspirasi'));
    }
    
    // Halaman History - Hanya menampilkan yang SUDAH SELESAI
    public function history()
    {
        $aspirasi = Aspirasi::with('kategori')
            ->where('user_id', auth()->id())
            ->where('status', 'Selesai') // Hanya yang sudah selesai
            ->orderBy('updated_at', 'desc')
            ->paginate(10);
            
        return view('siswa.aspirasi.history', compact('aspirasi'));
    }
    
    public function feedback()
    {
        $aspirasi = Aspirasi::with(['kategori', 'progres' => function($q) {
            $q->where('keterangan_progres', 'like', 'Feedback:%');
        }])
        ->where('user_id', auth()->id())
        ->whereHas('progres', function($q) {
            $q->where('keterangan_progres', 'like', 'Feedback:%');
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10);
        
        return view('siswa.aspirasi.feedback', compact('aspirasi'));
    }
}