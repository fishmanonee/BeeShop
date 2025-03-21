<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class ZaloPayController extends Controller
{
    private $config;

    public function __construct()
    {
        $this->config = config('zalopay'); // Lấy thông tin cấu hình từ config/zalopay.php
    }

    /**
     * Tạo thanh toán ZaloPay
     */
    public function createPayment(Request $request)
    {
        try {
            $amount = $request->input('amount', 10000); // Mặc định 10,000 VND
            $transID = rand(100000, 999999); // Mã giao dịch ngẫu nhiên
            $callbackUrl = route('zalopay.callback'); 
    
            // ✅ Lấy URL frontend từ request, nếu không có thì dùng URL mặc định
            $redirectUrl = $request->input('redirect_url', 'https://yourfrontend.com/payment-result'); 
    
            // Embed data với callback_url & redirect_url
            $embed_data = json_encode([
                "merchantinfo" => "embeddata123",
                "callback_url" => $callbackUrl,
                "redirect_url" => $redirectUrl
            ]);
    
            $items = json_encode([
                ["itemid" => "knb", "itemname" => "Kim Nguyên Bảo", "itemprice" => $amount, "itemquantity" => 1]
            ]);
    
            $order = [
                "app_id" => $this->config["app_id"],
                "app_time" => round(microtime(true) * 1000),
                "app_trans_id" => date("ymd") . "_" . $transID,
                "app_user" => "user123",
                "item" => $items,
                "embed_data" => $embed_data,
                "amount" => $amount,
                "description" => "Thanh toán đơn hàng #$transID",
                "bank_code" => "",
                "redirect_url" => $redirectUrl // ✅ Chuyển hướng về frontend sau khi thanh toán
            ];
    
            // Tạo chữ ký (MAC)
            $data = implode("|", [
                $order["app_id"], $order["app_trans_id"], $order["app_user"],
                $order["amount"], $order["app_time"], $order["embed_data"], $order["item"]
            ]);
            $order["mac"] = hash_hmac("sha256", $data, $this->config["key1"]);
    
            // Gửi request tới ZaloPay
            $response = Http::asForm()->post($this->config["endpoint"] . "/create", $order);
            $result = $response->json();
    
            Log::info("📌 Embed Data Sent: " . json_encode($embed_data));
    
            if (isset($result['order_url'])) {
                return response()->json([
                    "success" => true,
                    "payment_url" => $result['order_url'], // Link thanh toán
                    "qr_code" => $result['qr_code'],
                    "zp_trans_token" => $result['zp_trans_token']
                ]);
            }
    
            return response()->json(["error" => "Không thể tạo thanh toán!", "details" => $result], 400);
        } catch (\Exception $e) {
            Log::error("⚠️ ZaloPay Payment Error: " . $e->getMessage());
            return response()->json(["error" => "Lỗi hệ thống!", "message" => $e->getMessage()], 500);
        }
    }
    

    /**
     * Xử lý callback từ ZaloPay sau khi thanh toán
     */
    public function paymentCallback(Request $request)
    {
        try {
            $data = $request->all();
            Log::info("📌 Nhận callback từ ZaloPay:", $data);
    
            // Kiểm tra trạng thái giao dịch
            $status = $data['return_code'] ?? -1;
    
            // ✅ Lấy `redirect_url` từ embed_data
            $embedData = json_decode($data['data'] ?? '{}', true);
            $redirectUrl = $embedData['redirect_url'] ?? 'https://yourfrontend.com/payment-result';
    
            // ✅ Thêm trạng thái giao dịch vào URL frontend
            $redirectUrl .= "?status=" . ($status == 1 ? "success" : "failed");
    
            Log::info("🔄 Chuyển hướng về: " . $redirectUrl);
    
            return Redirect::away($redirectUrl);
        } catch (\Exception $e) {
            Log::error("⚠️ ZaloPay Callback Error: " . $e->getMessage());
            return response()->json(["error" => "Lỗi hệ thống!", "message" => $e->getMessage()], 500);
        }
    }
    
}
