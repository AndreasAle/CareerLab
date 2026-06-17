<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * { font-family: DejaVu Sans, sans-serif; }
        body { margin: 0; color: #1e293b; font-size: 12px; }
        .cover { background: #0f172a; color: #fff; padding: 60px 40px; }
        .brand { font-size: 13px; letter-spacing: 2px; color: #34d399; text-transform: uppercase; }
        .cover h1 { font-size: 30px; margin: 14px 0 6px; }
        .cover .sub { color: #cbd5e1; font-size: 13px; }
        .scorebox { margin-top: 30px; background: #1e293b; border-radius: 10px; padding: 20px; width: 200px; text-align: center; }
        .scorebox .num { font-size: 44px; font-weight: bold; color: #34d399; }
        .scorebox .lbl { color: #94a3b8; font-size: 11px; }
        .section { padding: 22px 40px; }
        .section h2 { font-size: 15px; color: #0f172a; border-bottom: 2px solid #10b981; padding-bottom: 5px; margin-bottom: 12px; }
        ul { margin: 0; padding-left: 18px; }
        li { margin-bottom: 5px; line-height: 1.5; }
        .grid { width: 100%; }
        .grid td { vertical-align: top; width: 50%; padding-right: 14px; }
        .pill { display: inline-block; background: #ecfdf5; color: #047857; padding: 3px 10px; border-radius: 20px; font-size: 11px; }
        .muted { color: #64748b; }
        .closing { background: #ecfdf5; border-radius: 10px; padding: 18px; color: #065f46; }
        .meta td { padding: 4px 0; }
    </style>
</head>
<body>
    <div class="cover">
        <div class="brand">CareerLab AI · Career Diagnosis Report</div>
        <h1>{{ $user->name }}</h1>
        <div class="sub">Target Posisi: {{ $user->target_position ?: '—' }} · {{ now()->format('d F Y') }}</div>
        <div class="scorebox">
            <div class="num">{{ $data['overall_score'] ?? 0 }}</div>
            <div class="lbl">OVERALL CAREER READINESS</div>
            <div style="margin-top:8px;"><span class="pill" style="background:#34d399;color:#064e3b;">{{ strtoupper($data['career_readiness_level'] ?? 'developing') }}</span></div>
        </div>
        <p class="sub" style="margin-top:24px; max-width: 460px;">{{ $data['headline_summary'] ?? '' }}</p>
    </div>

    <div class="section">
        <table class="grid">
            <tr>
                <td>
                    <h2>Kekuatan Utama</h2>
                    <ul>@foreach ($data['top_strengths'] ?? [] as $s)<li>{{ $s }}</li>@endforeach</ul>
                </td>
                <td>
                    <h2>Yang Perlu Diperbaiki</h2>
                    <ul>@foreach ($data['top_weaknesses'] ?? [] as $w)<li>{{ $w }}</li>@endforeach</ul>
                </td>
            </tr>
        </table>
    </div>

    @if (!empty($data['main_red_flags']))
        <div class="section">
            <h2>Red Flag Summary</h2>
            <ul>@foreach ($data['main_red_flags'] as $r)<li>{{ $r }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="section">
        <h2>Prioritas Perbaikan</h2>
        <ul>@foreach ($data['priority_fixes'] ?? [] as $p)<li>{{ $p }}</li>@endforeach</ul>
    </div>

    <div class="section">
        <table class="grid">
            <tr>
                <td>
                    <h2>Action Plan 7 Hari</h2>
                    <ul>@foreach ($data['seven_day_action_plan'] ?? [] as $a)<li>{{ $a }}</li>@endforeach</ul>
                </td>
                <td>
                    <h2>Action Plan 14 Hari</h2>
                    <ul>@foreach ($data['fourteen_day_action_plan'] ?? [] as $a)<li>{{ $a }}</li>@endforeach</ul>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2>Rekomendasi</h2>
        <p class="muted"><strong>Template:</strong> {{ implode(', ', $data['recommended_templates'] ?? []) }}</p>
        <p class="muted"><strong>Fitur berikutnya:</strong> {{ implode(', ', $data['recommended_next_features'] ?? []) }}</p>
    </div>

    <div class="section">
        <div class="closing">{{ $data['closing_message'] ?? 'Terus semangat ya!' }}</div>
        <p class="muted" style="margin-top:16px; font-size:10px;">Dibuat oleh CareerLab AI. Data CV digunakan hanya untuk analisis career. Report ini bersifat membantu, bukan jaminan diterima kerja.</p>
    </div>
</body>
</html>
