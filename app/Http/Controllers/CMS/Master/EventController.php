<?php

namespace App\Http\Controllers\CMS\Master;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::latest()->paginate(10);

        return view('CMS.event.index', compact('events'));
    }

    public function create()
    {
        return view('CMS.event.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:events,nama',
            'deskripsi' => 'nullable|string',

            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',

            'lokasi' => 'nullable|string|max:255',
            'penyelenggara' => 'nullable|string|max:255',
            'kategori' => 'nullable|string|max:255',

            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'is_active' => 'nullable|boolean',
        ]);

        $event = Event::create([
            'nama' => $validated['nama'],
            'slug' => Str::slug($validated['nama']),
            'deskripsi' => $validated['deskripsi'] ?? null,

            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'] ?? null,

            'lokasi' => $validated['lokasi'] ?? null,
            'penyelenggara' => $validated['penyelenggara'] ?? null,
            'kategori' => $validated['kategori'] ?? null,

            'is_active' => $request->has('is_active'),

            'gambar' => null,
        ]);

        /*
        |--------------------------------------------------------------------------
        | UPLOAD GAMBAR
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('gambar')) {

            $path = $request->file('gambar')
                ->store('event', 'public');

            $event->update([
                'gambar' => $path,
            ]);
        }

        return redirect('/admin/event')
            ->with('success', 'Data event berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);

        return view('CMS.event.edit', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:255',
                Rule::unique('events', 'nama')->ignore($event->id),
            ],

            'deskripsi' => 'nullable|string',

            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',

            'lokasi' => 'nullable|string|max:255',
            'penyelenggara' => 'nullable|string|max:255',
            'kategori' => 'nullable|string|max:255',

            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'is_active' => 'nullable|boolean',
        ]);

        $event->update([
            'nama' => $validated['nama'],
            'slug' => Str::slug($validated['nama']),
            'deskripsi' => $validated['deskripsi'] ?? null,

            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'] ?? null,

            'lokasi' => $validated['lokasi'] ?? null,
            'penyelenggara' => $validated['penyelenggara'] ?? null,
            'kategori' => $validated['kategori'] ?? null,

            'is_active' => $request->has('is_active'),
        ]);

        /*
        |--------------------------------------------------------------------------
        | GANTI GAMBAR
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('gambar')) {

            $this->deleteGambar($event->gambar);

            $path = $request->file('gambar')
                ->store('event', 'public');

            $event->update([
                'gambar' => $path,
            ]);
        }

        return redirect('/admin/event')
            ->with('success', 'Data event berhasil diperbarui.');
    }

    public function delete($id)
    {
        $event = Event::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | HAPUS GAMBAR
        |--------------------------------------------------------------------------
        */

        $this->deleteGambar($event->gambar);

        /*
        |--------------------------------------------------------------------------
        | HAPUS DATA EVENT
        |--------------------------------------------------------------------------
        */

        $event->delete();

        return redirect('/admin/event')
            ->with('success', 'Data event berhasil dihapus.');
    }

    private function deleteGambar($gambar)
    {
        if (!$gambar) {
            return;
        }

        if (Storage::disk('public')->exists($gambar)) {
            Storage::disk('public')->delete($gambar);
            return;
        }

        $oldPath = 'event/' . basename($gambar);

        if (Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }
    }
}
