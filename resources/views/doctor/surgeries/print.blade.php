<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surgery Report - {{ $surgery->patient->full_name }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            background: #fff;
            padding: 40px;
            max-width: 900px;
            margin: 0 auto;
        }
        .header-table { width: 100%; border-bottom: 3px double #333; padding-bottom: 20px; margin-bottom: 30px; }
        .logo { width: 80px; height: auto; }
        .hospital-name { font-size: 26px; font-weight: 800; text-transform: uppercase; margin: 0; color: #1a1a1a; letter-spacing: 0.5px; }
        .tagline { margin: 5px 0; font-size: 13px; font-style: italic; color: #666; }
        .contact-info { font-size: 12px; color: #555; }
        
        .report-title-box {
            text-align: center;
            margin: 20px 0 40px 0;
        }
        .report-title {
            background: #333;
            color: #fff;
            padding: 8px 30px;
            font-weight: bold;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 2px;
            display: inline-block;
            border-radius: 4px;
        }

        .section { margin-bottom: 30px; break-inside: avoid; }
        .section-header {
            border-bottom: 1px solid #ccc;
            color: #444;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 10px;
            padding-bottom: 5px;
        }
        
        .grid-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px 30px;
            font-size: 14px;
        }
        .grid-item span.label {
            font-weight: 600;
            color: #666;
            font-size: 12px;
            display: block;
            text-transform: uppercase;
        }
        .grid-item span.value {
            font-weight: 500;
            color: #000;
            font-size: 15px;
        }

        .notes-box {
            border: 1px solid #e0e0e0;
            background: #fcfcfc;
            padding: 15px;
            font-size: 14px;
            white-space: pre-wrap;
            min-height: 150px;
            border-radius: 4px;
        }

        .footer {
            margin-top: 60px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            font-size: 12px;
            color: #777;
        }
        .signature-section { text-align: center; }
        .signature-line {
            width: 250px;
            border-top: 1px solid #333;
            padding-top: 8px;
            margin-bottom: 5px;
        }

        @media print {
            body { padding: 0; margin: 0; max-width: 100%; }
            .no-print { display: none !important; }
            .report-title { background: #fff; color: #000; border: 2px solid #000; }
            .notes-box { background: #fff; border: 1px solid #ccc; }
        }
    </style>
</head>
<body onload="setTimeout(function(){window.print()}, 500)">

    <div class="no-print" style="position: fixed; top: 20px; right: 20px; z-index: 100;">
        <button onclick="window.print()" style="padding: 10px 25px; background: #222; color: #fff; border: none; cursor: pointer; border-radius: 4px; font-weight: bold;">Print / Save as PDF</button>
    </div>

    <!-- Header -->
    <table class="header-table">
        <tr>
            <td width="15%" style="vertical-align: middle;">
                <img src="{{ asset('rizz-assets/images/logo-sm.png') }}" class="logo" alt="Logo">
            </td>
            <td width="70%" style="text-align: center; vertical-align: middle;">
                <h1 class="hospital-name">CyberHausa New Clinic</h1>
                <p class="tagline">Compassionate Care, Advanced Medicine</p>
                <p class="contact-info">
                   123 Medical Drive, Lagos, Nigeria<br>
                   Tel: +234 800 123 4567 • Email: records@cyberhausa.com
                </p>
            </td>
            <td width="15%"></td>
        </tr>
    </table>

    <div class="report-title-box">
        <div class="report-title">Surgical Operation Report</div>
    </div>

    <!-- Patient Info -->
    <div class="section">
        <div class="section-header">Patient Demographics</div>
        <div class="grid-container">
            <div class="grid-item">
                <span class="label">Patient Name</span>
                <span class="value">{{ $surgery->patient->full_name }}</span>
            </div>
            <div class="grid-item">
                <span class="label">Hospital ID (MRN)</span>
                <span class="value">{{ $surgery->patient->hospital_id }}</span>
            </div>
            <div class="grid-item">
                <span class="label">Age / Sex</span>
                <span class="value">{{ \Carbon\Carbon::parse($surgery->patient->date_of_birth)->age }} Years / {{ ucfirst($surgery->patient->gender) }}</span>
            </div>
            <div class="grid-item">
                <span class="label">Blood Group</span>
                <span class="value">{{ $surgery->patient->blood_group ?? 'Not Recorded' }}</span>
            </div>
        </div>
    </div>

    <!-- Surgery Info -->
    <div class="section">
        <div class="section-header">Procedure Details</div>
        <div class="grid-container">
            <div class="grid-item">
                <span class="label">Procedure Performed</span>
                <span class="value">{{ $surgery->procedure_name }}</span>
            </div>
            <div class="grid-item">
                <span class="label">Date of Surgery</span>
                <span class="value">{{ $surgery->scheduled_at?->format('F d, Y') }}</span>
            </div>
            <div class="grid-item">
                <span class="label">Lead Surgeon</span>
                <span class="value">{{ $surgery->surgeon_name ?? 'Dr. ' . Auth::user()->name }}</span>
            </div>
            <div class="grid-item">
                <span class="label">Surgery Status</span>
                <span class="value" style="text-transform: capitalize;">{{ str_replace('_', ' ', $surgery->status) }}</span>
            </div>
            <div class="grid-item">
                <span class="label">Start Time</span>
                <span class="value">{{ $surgery->scheduled_at?->format('h:i A') }}</span>
            </div>
            <div class="grid-item">
                <span class="label">End Time</span>
                <span class="value">{{ $surgery->completed_at?->format('h:i A') ?? '--:--' }}</span>
            </div>
        </div>
    </div>

    <!-- Notes -->
    <div class="section">
        <div class="section-header">Operative Findings & Notes</div>
        <div class="notes-box">
{{ $surgery->notes ?? 'No intra-operative notes recorded for this procedure.' }}
        </div>
    </div>

    <!-- Post-Op -->
    <div class="section">
        <div class="section-header">Post-Operative Orders / Plan</div>
        <div class="notes-box" style="min-height: 80px;">
1. Transfer to Recovery Room.
2. Monitor Vitals q15min x 1hr, then q1h x 4hrs.
3. Analgesia and Antibiotics as per chart.
4. Notify Surgeon of any abnormalities.
        </div>
    </div>

    <!-- Warning / Footer -->
    <div class="footer">
        <div>
            <strong>Report Generated:</strong> {{ now()->format('d M Y, h:i A') }}<br>
            <span style="font-style: italic;">This is a computer-generated medical record.</span>
        </div>
        <div class="signature-section">
            <div class="signature-line">
                <span style="font-family: cursive; font-size: 18px;">{{ explode(' ', $surgery->surgeon_name ?? Auth::user()->name)[1] ?? 'Doctor' }}</span>
            </div>
            <div style="font-weight: bold;">{{ $surgery->surgeon_name ?? 'Dr. ' . Auth::user()->name }}</div>
            <div style="font-size: 11px; text-transform: uppercase;">Consultant Surgeon</div>
        </div>
    </div>

</body>
</html>
