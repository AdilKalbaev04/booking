<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hall;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class HallController extends Controller
{
    // Конструктор для применения middleware
    public function __construct()
    {
        // Применение auth:sanctum для проверки токена для всех методов, кроме index
        $this->middleware('auth:sanctum')->except('index');
    }

    // Получение списка залов
    public function index()
    {
        try {
            $halls = Hall::all();
            return response()->json($halls);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch halls', 'error' => $e->getMessage()], 500);
        }
    }

    // Добавление нового зала
    public function store(Request $request)
    {
        try {
            // Проверка на наличие авторизации
            if (!$request->user()) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string'
            ]);

            $hall = Hall::create([
                'name' => $request->name,
                'description' => $request->description
            ]);

            return response()->json(['message' => 'Hall created', 'hall' => $hall], 201);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create hall', 'error' => $e->getMessage()], 500);
        }
    }

    // Обновление зала
    public function update(Request $request, $id)
    {
        try {
            $hall = Hall::findOrFail($id);

            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string'
            ]);

            $hall->update($request->only('name', 'description'));

            return response()->json(['message' => 'Hall updated', 'hall' => $hall]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Hall not found', 'error' => $e->getMessage()], 404);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update hall', 'error' => $e->getMessage()], 500);
        }
    }



    // Удаление зала
    public function destroy($id)
    {
        try {
            $hall = Hall::findOrFail($id);
            $hall->delete();

            return response()->json(['message' => 'Hall deleted']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Hall not found', 'error' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete hall', 'error' => $e->getMessage()], 500);
        }
    }
}
