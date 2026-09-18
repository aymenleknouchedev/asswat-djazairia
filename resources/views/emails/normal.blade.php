<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $subject ?? 'رسالة' }}</title>
</head>
<body style="margin:0; padding:24px; background:#f6f7f9; font-family: Arial, Helvetica, sans-serif; direction: rtl; text-align: right; color:#222; line-height:1.7;">
    <div style="max-width:640px; margin:0 auto; background:#fff; border:1px solid #e5e7eb; border-radius:8px; padding:24px;">
        {{-- Body is HTML written by an authenticated admin in the dashboard editor. --}}
        <div>{!! $body !!}</div>

        <hr style="margin:24px 0 12px; border:0; border-top:1px solid #eee;">
        <p style="margin:0; font-size:12px; color:#888;">{{ config('mail.from.name') }} — {{ config('mail.from.address') }}</p>
    </div>
</body>
</html>
