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

1. Install the package in your Laravel project:

```
composer require yosefib/sadad-pgw
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
        '0921234567',  // Customer phone number
        '1990',        // Birth year as string
        'INV-1001',    // Invoice number
        75.00,         // Amount
        7              // Service category (example: Food & Beverages)
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

## 🏦 Complete Payment Flow (Full Transaction Steps)

To complete a transaction using SADAD PGW:

1. **Validate the Customer and Create Invoice**
   - Call the `validateCustomer` method.
   - This sends an OTP to the customer's phone.
   - Also creates a pending transaction in SADAD.

```php
$response = $sadad->validateCustomer(
    '0921234567', // Customer phone number
    1990,         // Birth year
    'INV-1001',   // Invoice number
    75.00,        // Amount
    7             // Service category (example: Food & Beverages)
);
```

2. **Pay the Invoice with OTP**
   - After the customer receives the OTP via SMS, use it to confirm payment.
   - You need the `transactionId` returned from `validateCustomer`.

```php
$response = $sadad->payInvoice(
    'TransactionId_From_ValidateCustomer',
    'OTP_Code_From_SMS'
);
```

3. **Resend OTP (if needed)**
   - If the customer did not receive the OTP, you can request to resend it.

```php
$response = $sadad->resendOtp(
    'TransactionId_From_ValidateCustomer'
);
```

4. **Check Transaction Status (Optional)**
   - To check if the transaction was successfully paid:

```php
$response = $sadad->transactionStatus(
    'INV-1001' // Invoice number
);
```

---

### 📋 Example Flow

- Step 1: `validateCustomer` ➔ OTP sent to user.
- Step 2: User receives OTP ➔ enters it.
- Step 3: `payInvoice` using OTP ➔ transaction completed.

✅ Done!

---

## 📄 License

MIT © 2025 Yosef Ibrahem Ali
---

## 🧾 Response Example for Completed Payment

After calling `payInvoice`, a successful response may look like:

```json
{
  "success": true,
  "message": "تم الدفع بنجاح",
  "status_code": 200,
  "data": {
    "result": "SD-20240430123456",
    "transactionId": "TX987654321",
    "merchantNo": "M-123456",
    "commission": 1.25,
    "total": 76.25
  }
}
```

On failure (e.g. invalid OTP):

```json
{
  "success": false,
  "message": "OTP غير صحيح",
  "status_code": 400,
  "data": {
    "statusCode": 2,
    "message": "OTP غير صحيح أو منتهي"
  }
}
```

---

## 🗂️ Service Categories

When creating an invoice via `validateCustomer`, you must pass a category ID.  
Use one of the following:

| Category Name (Arabic)                      | ID |
|--------------------------------------------|----|
| بدون تصنيف                                 | 0  |
| مواد صحية                                  | 5  |
| صيدليات                                    | 6  |
| مواد غذائية                                | 7  |
| مقاهي ومطاعم                               | 8  |
| ملابس وأحذية                               | 9  |
| قرطاسية                                    | 10 |
| إلكترونيات                                 | 11 |
| صيانة وقطع غيار                            | 12 |
| خدمات سياحية                               | 13 |
| مواد بناء                                  | 14 |
| خدمات تعليمية                              | 15 |
| خدمات دعائية                               | 16 |
| مواد منزلية                                | 17 |
| أثاث ومفروشات                              | 18 |
| إطارات وونشات                              | 19 |
| البيع الإلكتروني                           | 20 |
| العطور ومواد منزلية                        | 21 |
| المخابز والحلويات                          | 22 |
| البصريات والعدسات                          | 23 |
| ساعات ومجوهرات وإكسسوارات                 | 24 |
| مستلزمات الأم والطفل                      | 25 |
| ورش صيانة                                  | 26 |
| مواد تنظيف                                 | 27 |
| الألعاب                                    | 28 |
| لحوم ودواجن                                 | 29 |
| عيادات ومصحات                              | 30 |
| معامل تحاليل                                | 32 |
| الملابس التقليدية                          | 36 |

