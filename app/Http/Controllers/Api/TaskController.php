<?php

namespace App\Http\Controllers\Api;

use App\Events\TaskCreated;
use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
// Pas besoin de 'use Illuminate\Support\Facades\Auth;'
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Affiche la liste des tâches de l'utilisateur connecté.
     */
    public function index(Request $request)
    {
        // On récupère l'utilisateur à partir de la requête authentifiée
        $tasks = $request->user()->tasks;
        return response()->json($tasks);
    }

    /**
     * Enregistre une nouvelle tâche pour l'utilisateur connecté.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // On crée la tâche via la relation de l'utilisateur de la requête
        $task = $request->user()->tasks()->create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        // --- C'EST LA MAGIE ---
        TaskCreated::dispatch($task); // <-- 2. DÉCLENCHEZ L'ÉVÉNEMENT
        // -----------------------

        return response()->json($task, 201);
    }


    /**
     * Affiche les détails d'une tâche spécifique.
     */
    public function show(Request $request, Task $task)
    {
        // Vérifie si la tâche appartient bien à l'utilisateur connecté
        if ($task->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        return response()->json($task);
    }

    /**
     * Met à jour une tâche existante.
     */
    public function update(Request $request, Task $task)
    {
        // Vérifie les droits de l'utilisateur
        if ($task->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'completed' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $task->update($request->all());

        return response()->json($task);
    }

    /**
     * Supprime une tâche.
     */
    public function destroy(Request $request, Task $task)
    {
        // Vérifie les droits de l'utilisateur
        if ($task->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $task->delete();

        return response()->json(null, 204);
    }
}
