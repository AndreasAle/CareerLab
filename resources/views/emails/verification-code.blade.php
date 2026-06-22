<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"></head>
<body style="margin:0;background:#f1f5f9;font-family:Arial,Helvetica,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:32px 12px;">
        <tr><td align="center">
            <table width="100%" cellpadding="0" cellspacing="0" style="max-width:480px;background:#ffffff;border-radius:18px;overflow:hidden;box-shadow:0 8px 30px rgba(2,6,23,.08);">
                {{-- header --}}
                <tr><td style="background:linear-gradient(135deg,#4f46e5,#7c3aed,#9333ea);padding:28px 32px;">
                    <span style="color:#fff;font-size:20px;font-weight:bold;">CareerLab<span style="color:#6ee7b7;">AI</span></span>
                </td></tr>
                {{-- body --}}
                <tr><td style="padding:32px;">
                    <h1 style="margin:0 0 8px;font-size:20px;color:#0f172a;">Verifikasi email kamu</h1>
                    <p style="margin:0 0 22px;font-size:14px;line-height:1.6;color:#475569;">
                        Hai {{ $user->name }}, makasih udah daftar di CareerLab AI! Masukkan kode di bawah ini untuk mengaktifkan akunmu.
                    </p>

                    <div style="text-align:center;margin:8px 0 22px;">
                        <div style="display:inline-block;background:#eef2ff;border:1px dashed #c7d2fe;border-radius:14px;padding:18px 28px;">
                            <span style="font-size:34px;font-weight:bold;letter-spacing:10px;color:#4338ca;">{{ $code }}</span>
                        </div>
                    </div>

                    <p style="margin:0 0 6px;font-size:13px;color:#64748b;">⏱️ Kode ini berlaku selama 15 menit.</p>
                    <p style="margin:0;font-size:13px;color:#64748b;">Kalau kamu nggak merasa mendaftar, abaikan email ini.</p>
                </td></tr>
                {{-- footer --}}
                <tr><td style="padding:20px 32px;background:#f8fafc;border-top:1px solid #e2e8f0;">
                    <p style="margin:0;font-size:12px;color:#94a3b8;">© {{ date('Y') }} CareerLab AI · Data kamu aman & privat.</p>
                </td></tr>
            </table>
        </td></tr>
    </table>
</body>
</html>
