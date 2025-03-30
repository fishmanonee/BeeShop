<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    // Lấy danh sách tất cả người dùng
    public function index(): JsonResponse
    {
        $users = User::all();
        return response()->json($users, 200, [], JSON_PRETTY_PRINT);
    }

    // Lấy thông tin người dùng theo ID
    public function show($id): JsonResponse
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Người dùng không tồn tại'], 404);
        }
        return response()->json($user, 200, [], JSON_PRETTY_PRINT);
    }

    // Tạo người dùng mới
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        return response()->json([
            'message' => 'Người dùng đã được tạo thành công!',
            'user' => $user
        ], 201);
    }

    // Cập nhật thông tin người dùng
    public function update(Request $request, $id): JsonResponse
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Người dùng không tồn tại'], 404);
        }
    
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|required|string|min:6',
            'role_id' => 'sometimes|required|integer'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        $user->update($request->only(['name', 'email', 'role_id']));
    
        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
            $user->save();
        }
    
        return response()->json([
            'message' => 'Cập nhật người dùng thành công!',
            'user' => $user
        ], 200);
    }
    
    
    // Xóa người dùng
    public function destroy($id): JsonResponse
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Người dùng không tồn tại'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'Xóa người dùng thành công!'], 200);
    }
}
