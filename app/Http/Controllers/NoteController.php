<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Note;
use Lubix\Core\Http\Request;
use Lubix\Core\Http\Response;

final class NoteController
{
    public function index(Request $request): Response
    {
        $notes = Note::all();
        return Response::json([
            'ok' => true,
            'data' => array_map(fn($note) => $note->toArray(), $notes)
        ]);
    }

    public function store(Request $request): Response
    {
        $data = $request->json();
        if (!$data || !isset($data['title'])) {
            return Response::json(['ok' => false, 'error' => 'Title is required'], 422);
        }

        $note = new Note([
            'title' => $data['title'],
            'body' => $data['body'] ?? ''
        ]);
        $note->save();

        return Response::json(['ok' => true, 'data' => $note->toArray()], 201);
    }

    public function show(Request $request, array $params): Response
    {
        $id = $params['id'] ?? null;
        $note = Note::find($id);

        if (!$note) {
            return Response::json(['ok' => false, 'error' => 'Note not found'], 404);
        }

        return Response::json(['ok' => true, 'data' => $note->toArray()]);
    }
}
