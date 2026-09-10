<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\StoreEventRequest;
use App\Http\Requests\Event\UpdateEventRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{
    /**
     * GET /api/event
     */
    public function index(Request $request)
    {
        $query = Event::query()
            ->where('is_active', true)
            ->orderBy('tanggal_mulai', 'asc');

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $events = $query->get();

        return EventResource::collection($events);
    }

    /**
     * GET /api/event/{event}
     */
    public function show(Event $event)
    {
        if (!$event->is_active) {
            return response()->json([
                'message' => 'Event tidak ditemukan.',
            ], 404);
        }

        return new EventResource($event);
    }

    /**
     * POST /api/event
     */
    public function store(StoreEventRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')
                ->store('event', 'public');
        }

        $event = Event::create($data);

        return response()->json([
            'message' => 'Event berhasil ditambahkan.',
            'data' => new EventResource($event),
        ], 201);
    }

    /**
     * PUT /api/event/{event}
     */
    public function update(
        UpdateEventRequest $request,
        Event $event
    ): JsonResponse {
        $data = $request->validated();

        if ($request->hasFile('gambar')) {
            if ($event->gambar && Storage::disk('public')->exists($event->gambar)) {
                Storage::disk('public')->delete($event->gambar);
            }

            $data['gambar'] = $request->file('gambar')
                ->store('event', 'public');
        }

        $event->update($data);

        return response()->json([
            'message' => 'Event berhasil diperbarui.',
            'data' => new EventResource($event->fresh()),
        ]);
    }

    /**
     * DELETE /api/event/{event}
     */
    public function destroy(Event $event): JsonResponse
    {
        if ($event->gambar && Storage::disk('public')->exists($event->gambar)) {
            Storage::disk('public')->delete($event->gambar);
        }

        $event->delete();

        return response()->json([
            'message' => 'Event berhasil dihapus.',
        ]);
    }
}
