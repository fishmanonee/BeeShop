<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\OrderDetail;
use App\Models\Promotion;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    // Lấy danh sách đơn hàng
    public function index(): JsonResponse
    {
        $orders = Order::with('status')->get(); // Load quan hệ status

        // Chuyển đổi dữ liệu trước khi trả về
        $orders = $orders->map(function ($order) {
            return [
                'id' => $order->id,
                'user_id' => $order->user_id,
                'total_amount' => $order->total_amount,
                //'status_id' => $order->status_id,
                'status_name' => $order->status->name ?? null, // Lấy tên status
                'subtotal' => $order->subtotal,
                'promotion_id' => $order->promotion_id,
                'created_at' => $order->created_at,
                'updated_at' => $order->updated_at
            ];
        });

        return response()->json($orders, 200, [], JSON_PRETTY_PRINT);
    }


    // Lấy thông tin đơn hàng theo ID
    public function show($id): JsonResponse
    {
        $order = Order::with('status')->find($id); // Load quan hệ status

        if (!$order) {
            return response()->json(['message' => 'Đơn hàng không tồn tại'], 404);
        }

        // Chuẩn hóa dữ liệu trả về
        $orderData = [
            'id' => $order->id,
            'user_id' => $order->user_id,
            'total_amount' => $order->total_amount,
            //'status_id' => $order->status_id,
            'status_name' => $order->status->name ?? null, // Lấy tên status
            'subtotal' => $order->subtotal,
            'promotion_id' => $order->promotion_id,
            'created_at' => $order->created_at,
            'updated_at' => $order->updated_at
        ];

        return response()->json($orderData, 200, [], JSON_PRETTY_PRINT);
    }


    // Lấy danh sách đơn hàng theo user_id
    public function getOrdersByUser($user_id): JsonResponse
    {
        $orders = Order::with('status')->where('user_id', $user_id)->get(); // Load quan hệ status

        if ($orders->isEmpty()) {
            return response()->json(['message' => 'Không có đơn hàng nào cho user này'], 404);
        }

        // Chuẩn hóa dữ liệu trả về
        $orderData = $orders->map(function ($order) {
            return [
                'id' => $order->id,
                'user_id' => $order->user_id,
                'total_amount' => $order->total_amount,
                'status_id' => $order->status_id,
                'status_name' => $order->status->name ?? null, // Lấy tên status
                'subtotal' => $order->subtotal,
                'promotion_id' => $order->promotion_id,
                'created_at' => $order->created_at,
                'updated_at' => $order->updated_at
            ];
        });

        return response()->json($orderData, 200, [], JSON_PRETTY_PRINT);
    }



    // Tạo đơn hàng mới
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'products' => 'required|array',
            'products.*.productvariant_id' => 'required|exists:product_variants,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            // 1. Tạo đơn hàng ban đầu với subtotal = 0
            $paymentMethod = $request->payment_method; // Lấy phương thức thanh toán từ request
            $statusId = ($paymentMethod === 'ZaloPay') ? 2 : 1;
            $order = Order::create([
                'user_id' => $request->user_id,
                'subtotal' => 0, // Tạm thời 0, lát cập nhật lại
                'total_amount' => 0, // Tạm thời 0
                'status_id' => $statusId,
                'payment_method' => $paymentMethod,
                'promotion_id' => $request->promotion_id ?? null,
                'created_at' => now(),
                'updated_at' => now(),
                'user_address' => $request->user_address,
                'user_phone' => $request->user_phone,
            ]);

            $subtotal = 0; // Biến để tính tổng tiền hàng
            $orderDetails = [];

            // 2. Lưu chi tiết đơn hàng và kiểm tra kho hàng
            foreach ($request->products as $product) {
                $variant = ProductVariant::find($product['productvariant_id']);

                if (!$variant || $variant->stock_quantity < $product['quantity']) {
                    DB::rollBack(); // Hủy giao dịch nếu có sản phẩm không đủ hàng
                    return response()->json(['message' => 'Sản phẩm không đủ hàng'], 400);
                }

                $total_price = $variant->price * $product['quantity'];
                $subtotal += $total_price;

                $orderDetails[] = [
                    'order_id' => $order->id,
                    'productvariant_id' => $product['productvariant_id'],
                    'quantity' => $product['quantity'],
                    'total_price' => $total_price,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // 3. Trừ kho hàng
                $variant->decrement('stock_quantity', $product['quantity']);
            }

            // 4. Chèn tất cả OrderDetail một lần (tối ưu hiệu suất)
            OrderDetail::insert($orderDetails);

            // 5. Tính lại khuyến mãi dựa trên tổng tiền hàng
            $discount = 0;
            if (!empty($request->promotion_id)) {
                $promotion = Promotion::find($request->promotion_id);
                if ($promotion) {
                    $discount = ($subtotal * $promotion->discount_percent) / 100;
                }
            }

            // 6. Cập nhật lại subtotal và total_amount trong đơn hàng
            $order->update([
                'subtotal' => $subtotal,
                'total_amount' => max(0, $subtotal - $discount)
            ]);

            DB::commit();
            return response()->json(['message' => 'Đơn hàng đã được tạo thành công!', 'order' => $order], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Đã xảy ra lỗi khi tạo đơn hàng!', 'error' => $e->getMessage()], 500);
        }
    }


    // Cập nhật đơn hàng
    public function update(Request $request, $id): JsonResponse
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Đơn hàng không tồn tại'], 404);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'sometimes|required|integer|exists:users,id',
            'total_amount' => 'sometimes|required|numeric',
            'status_id' => 'sometimes|required|integer',
            'subtotal' => 'sometimes|required|numeric',
            'promotion_id' => 'nullable|integer|exists:promotions,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $order->update($request->all());

        // Load quan hệ status để lấy name
        $order->load('status');

        return response()->json([
            'message' => 'Cập nhật đơn hàng thành công!',
            'order' => [
                'id' => $order->id,
                'user_id' => $order->user_id,
                'total_amount' => $order->total_amount,
                'status_id' => $order->status_id,
                'status_name' => $order->status->name ?? null, // Trả về tên status
                'subtotal' => $order->subtotal,
                'promotion_id' => $order->promotion_id,
                'created_at' => $order->created_at,
                'updated_at' => $order->updated_at,
            ]
        ], 200);
    }


    // Xóa đơn hàng
    public function destroy($id): JsonResponse
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Đơn hàng không tồn tại'], 404);
        }

        $order->delete();

        return response()->json(['message' => 'Xóa đơn hàng thành công!'], 200);
    }

    //Lấy dữ liệu show trang thống kê
    public function getOrderStats(): JsonResponse
    {
        $stats = [
            'completed' => Order::where('status_id', 5)->count(),
            'shipping'  => Order::where('status_id', 6)->count() ?? 0,
            'canceled'  => Order::where('status_id', 7)->count(),
        ];
        return response()->json($stats);
    }

    //Lấy dữ liệu show trang thống kê đơn hàng theo tháng
    public function getOrdersByMonth(): JsonResponse
    {
        $orders = Order::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $stats = array_fill(1, 12, 0); // Mảng 12 tháng, mặc định là 0

        foreach ($orders as $order) {
            $stats[$order->month] = $order->total;
        }

        return response()->json($stats);
    }
}
