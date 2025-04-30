# yosefIb/sadad-pgw

**Laravel package for integrating with SADAD PGW (Payment Gateway)**  
This package simplifies the process of validating users, creating invoices, handling OTPs, and confirming payments using SADAD.

---

## 🚀 Features

- ✅ Validate customer identity and create invoice  
- 🔐 Confirm payment using OTP  
- 🔁 Resend OTP  
- 📊 Check transaction status  
- 🎯 Unified and formatted API responses  

---

## 📦 Installation (Local Usage in Laravel)

1. Move the package to your Laravel project:

```
packages/yosefib/sadad-pgw
```

2. Edit your Laravel project’s `composer.json` and add:

```json
"repositories": [
  {
    "type": "path",
    "url": "packages/yosefib/sadad-pgw"
  }
],
"require": {
  "yosefib/sadad-pgw": "*"
}
```

3. Run:

```bash
composer update
```

4. Add environment variables in your `.env`:

```
SADAD_TOKEN=your_token
SADAD_BASE_URL=https://pgw-test.almadar.ly
```

5. Add this config file in `config/sadad.php`:

```php
return [
    'base_url' => env('SADAD_BASE_URL'),
    'token' => env('SADAD_TOKEN'),
];
```

---

## 🧪 Usage Example

```php
use YosefIb\SadadPGW\Sadad;

public function initiate(Sadad $sadad)
{
    $response = $sadad->validateCustomer(
        '0921234567',
        1990,
        'INV-1001',
        75.00,
        7
    );

    return response()->json($response);
}
```

---

## ✅ Response Format

### Success

```json
{
  "success": true,
  "message": "تم التحقق من هوية العميل",
  "status_code": 200,
  "data": {
    "transactionId": "TX123456",
    "merchantNo": "M-001"
  }
}
```

### Failure

```json
{
  "success": false,
  "message": "العميل غير مسجل في خدمة سداد",
  "status_code": 400,
  "data": {
    "statusCode": 1,
    "message": "Invalid OTP"
  }
}
```

---

## 📄 License

MIT © 2025 Yosef Ibrahem Ali
