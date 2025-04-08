<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Type;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class TypeController extends Controller
{
    public function getTypes(): JsonResponse
    {
        $types = Type::all();
        return response()->json($types, 200, [], JSON_PRETTY_PRINT);
    }

    public function getTypeById($id): JsonResponse
    {
        $type = Type::find($id);

        if (!$type) {
            return response()->json([
                'status' => false,
                'message' => 'Loại không tồn tại'
            ], 404);
        }

        return response()->json($type, 200, [], JSON_PRETTY_PRINT);
    }

    public function getProductsByTypeId($id): JsonResponse
    {
        $type = Type::find($id);

        if (!$type) {
            return response()->json([
                'status' => false,
                'message' => 'Loại không tồn tại'
            ], 404);
        }

        $products = Product::where('type_id', $id)->get();

        return response()->json([
            'status' => true,
            'message' => 'Lấy danh sách sản phẩm theo loại thành công',
            'data' => $products
        ], 200, [], JSON_PRETTY_PRINT);
    }
}
