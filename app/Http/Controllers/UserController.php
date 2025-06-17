<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redis;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        // Optionally cache the user list in Redis for 60 seconds
        $cacheKey = 'users:all';
        $users = Redis::get($cacheKey);

        if (!$users) {
            $users = User::paginate(15);
            Redis::setex($cacheKey, 60, $users->toJson());
        } else {
            $users = json_decode($users);
        }

        return response()->json($users);
    }

    /**
     * Store a newly created user in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::create($validated);

        return response()->json(['message' => 'User created', 'data' => $user], 201);
    }

    /**
     * Display the specified user.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user);
    }

    /**
     * Update the specified user in storage.
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', 'max:255', Rule::unique('users')->ignore($user->_id)],
            'password' => ['sometimes', 'string', 'min:8'],
        ]);

        $user->fill($validated);
        $user->save();

        return response()->json(['message' => 'User updated', 'data' => $user]);
    }

    /**
     * Remove the specified user from storage.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted']);
    }
    
    /**
     * Pokaż formularz edycji profilu zalogowanego użytkownika.
     */
    public function editProfile()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }
    /**
     * Zapisz zmiany profilu zalogowanego użytkownika.
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->_id)],
            'password'              => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // Nie nadpisuj hasła, jeśli pole puste
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $user->fill($validated);
        $user->save();

        // Opcjonalnie: wyczyść cache listy użytkowników
        Redis::del('users:all');

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Profil został zaktualizowany.');
    }
}
