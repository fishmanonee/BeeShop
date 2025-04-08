<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Promotion;

class OrderController extends Controller
{

    public function index()
    {
        $promotion = Promotion::get();
        $orders = Order::where('status_id', 5)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.orders.index', compact('orders', 'promotion'));
    }
    public function pending()
    {
        $orders = Order::whereIn('status_id', [1, 2])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.orders.pending', compact('orders'));
    }
    public function confirmed()
    {
        $orders = Order::whereIn('status_id', [3, 4])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.orders.confirmed', compact('orders'));
    }
    public function cancel()
    {
        $orders = Order::where('status_id', 7)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.orders.cancel', compact('orders'));
    }
    public function shipping()
    {
        $orders = Order::where('status_id', 6)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.orders.shipping', compact('orders'));
    }

    public function show($id)
    {
        $product = Product::all();
        $order = Order::with(['user', 'orderDetails.productVariant'])->findOrFail($id);
        return view('admin.orders.show', compact('order', 'product'));
    }

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
                //'status' => $paymentMethod === 'COD' ? 'Chờ xác nhận' : 'Chờ thanh toán',
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

    public function updateOrder($id)
{
    $order = Order::find($id);

    if (!$order) {
        return redirect()->back()->with('error', 'Đơn hàng không hợp lệ!');
    }

    // Xác định trạng thái mới
    $newStatus = match ($order->status_id) {
        1 => 3, 
        2 => 4, 
        3, 4 => 6, 
        6 => 5, 
        default => null
    };

    if ($newStatus === null) {
        return redirect()->back()->with('error', 'Trạng thái đơn hàng không hợp lệ để xác nhận!');
    }

    // Cập nhật trạng thái và thời gian updated_at
    $order->update([
        'status_id' => $newStatus,
        'updated_at' => now() // Cập nhật thời gian hiện tại
    ]);

    if ($newStatus == 5) {
        $this->updateProductPurchaseCount($order);  // Gọi hàm để cập nhật số lượt mua
    }

    return redirect()->back()->with('success', 'Đơn hàng đã được cập nhật!');
}

public function cancelOrder(Request $request,$id)
{
    
    $order = Order::find($id);

    if (!$order) {
        return redirect()->back()->with('error', 'Đơn hàng không hợp lệ!');
    }

    if ($order->status_id != 1 && $order->status_id != 2) {
        return redirect()->back()->with('warning', 'Đơn hàng đã được xác nhận và không thể hủy!');
    }

    // Kiểm tra xem đơn hàng đã ở trạng thái "Đã hủy" hay chưa
    if ($order->status_id == 7) {
        return redirect()->back()->with('warning', 'Đơn hàng này đã bị hủy trước đó!');
    }

    // Cập nhật trạng thái thành "Đã hủy" (status_id = 7) và thời gian updated_at
    $order->update([
        'status_id' => 7,
        'cancel_reason' => $request->input('cancel_reason'), // Lưu lý do hủy
        'updated_at' => now() // Cập nhật thời gian hiện tại
    ]);
    
    // Giảm số lượng mua của sản phẩm khi hủy đơn
    $this->rollbackProductPurchaseCount($order);

    return redirect()->back()->with('success', 'Đơn hàng đã được hủy thành công!');

   
}

public function updateProductPurchaseCount($order)
{
    foreach ($order->orderDetails as $detail) {
        $product = $detail->productVariant->product;
        $product->increment('purchase_count', $detail->quantity);
    }
}
public function rollbackProductPurchaseCount($order)
{
    foreach ($order->orderDetails as $detail) {
        $product = $detail->productVariant->product;
        $product->decrement('purchase_count', $detail->quantity);
    }
}
}
