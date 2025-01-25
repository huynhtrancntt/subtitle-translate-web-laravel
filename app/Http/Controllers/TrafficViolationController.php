<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class TrafficViolationController extends Controller
{
    //
    public function showCheckViolationPage() {
        try {
            return view('tra-cuu');
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    private function validatePlateNumber($bienso) {
        // Loại bỏ các ký tự không cần thiết như dấu gạch ngang, dấu chấm và khoảng trắng
        $bienso = str_replace(['-', '.', ' '], '', $bienso);
        // Kiểm tra định dạng biển số xe (chấp nhận cả chữ cái viết hoa và viết thường)
        return preg_match('/^\d{2}[A-Za-z]\d{5,6}$/', $bienso);
    }


    public function bienSoCheck(Request $request)
    {

        $bienso = $request->input('bienso');

         // Kiểm tra biển số xe hợp lệ
         if (!$this->validatePlateNumber($bienso)) {
            return response()->json(['error' => 'Biển số xe không hợp lệ'], 200);
        }

        try {
            // Địa chỉ API ngoài
            $url = 'https://api.checkphatnguoi.vn/phatnguoi';

            // Gửi yêu cầu POST sử dụng Http facade
            $response = Http::withHeaders([
                'Content-Type' => 'application/json', // Đặt Content-Type là application/json
            ])->post($url, [
                'bienso' => $bienso, // Gửi biển số xe như là tham số JSON
            ]);

            // Kiểm tra nếu response có dữ liệu
            $data = $response->json();

            if (isset($data['data'])) {
                return response()->json($data['data']);
            } else {
                return response()->json(['error' => 'Không có dữ liệu vi phạm'], 404);
            }

        } catch (\Exception $e) {
            // Xử lý lỗi nếu có
            return response()->json(['error' => 'Lỗi khi liên hệ với API ngoài: ' . $e->getMessage()], 500);
        }
    }

}
