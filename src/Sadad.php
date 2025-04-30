<?php

namespace YosefIb\SadadPGW;

use Illuminate\Support\Facades\Http;

class Sadad
{
    protected $baseUrl;
    protected $token;

    public function __construct()
    {
        $this->baseUrl = config('sadad.base_url', 'https://pgw-test.almadar.ly');
        $this->token = config('sadad.token');
    }

    protected function headers()
    {
        return [
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ];
    }

    protected function formatResponse($response, $defaultSuccessMessage = 'تمت العملية بنجاح')
    {
        if (!$response->ok() || $response->json('statusCode') !== 0) {
            return [
                'success' => false,
                'message' => $response->json('message') ?? 'فشل في تنفيذ العملية',
                'status_code' => $response->status(),
                'data' => $response->json()
            ];
        }

        return [
            'success' => true,
            'message' => $defaultSuccessMessage,
            'status_code' => $response->status(),
            'data' => $response->json()
        ];
    }

    public function validateCustomer($msisdn, $birthYear, $invoiceNo, $amount, $category)
    {
        $response = Http::withHeaders($this->headers())->post("{$this->baseUrl}/api/validate", [
            'Msisdn' => $msisdn,
            'BirthYear' => $birthYear,
            'InvoiceNo' => $invoiceNo,
            'Amount' => $amount,
            'Category' => $category,
        ]);

        return $this->formatResponse($response, 'تم التحقق من هوية العميل');
    }

    public function payInvoice($transactionId, $otp)
    {
        $response = Http::withHeaders($this->headers())->post("{$this->baseUrl}/api/pay", [
            'TransactionId' => $transactionId,
            'OTP' => $otp,
        ]);

        return $this->formatResponse($response, 'تم الدفع بنجاح');
    }

    public function resendOtp($transactionId)
    {
        $response = Http::withHeaders($this->headers())->post("{$this->baseUrl}/api/otp-resend", [
            'TransactionId' => $transactionId,
        ]);

        return $this->formatResponse($response, 'تم إرسال رمز OTP مرة أخرى');
    }

    public function transactionStatus($invoiceNo)
    {
        $response = Http::withHeaders($this->headers())->post("{$this->baseUrl}/api/enquiry", [
            'InvoiceNo' => $invoiceNo,
        ]);

        return $this->formatResponse($response, 'تم جلب حالة المعاملة');
    }
}
