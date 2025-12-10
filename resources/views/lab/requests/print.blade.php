<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Result - {{ $labTest->test_name }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 40px;
            font-size: 14px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            max-width: 80px;
            margin-bottom: 10px;
        }
        .hospital-name {
            font-size: 24px;
            font-weight: bold;
            color: #1a1a1a;
            margin: 0;
            text-transform: uppercase;
        }
        .hospital-address {
            color: #666;
            font-size: 12px;
            margin-top: 5px;
        }
        
        .patient-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
        }
        .info-col h4 {
            font-size: 12px;
            text-transform: uppercase;
            color: #888;
            margin: 0 0 5px 0;
        }
        .info-col p {
            font-size: 16px;
            font-weight: 600;
            margin: 0;
        }

        .test-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #2c3e50;
            text-decoration: underline;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        th {
            background-color: #f8f9fa;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            color: #666;
        }
        tr:last-child td {
            border-bottom: 2px solid #333;
        }

        .summary-box {
            margin-top: 20px;
            padding: 15px;
            border: 1px dashed #ccc;
            border-radius: 5px;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            color: #999;
            font-size: 12px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        
        .signature-lines {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
        }
        .sig-box {
            width: 200px;
            border-top: 1px solid #333;
            padding-top: 10px;
            text-align: center;
        }

        @media print {
            body { padding: 0; }
            .no-print { display: none; }
            @page { margin: 2cm; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <!-- Assuming generic logo if not set -->
        <img src="{{ asset('rizz-assets/images/logo-sm.png') }}" alt="Logo" class="logo">
        <h1 class="hospital-name">{{ config('hms.hospital.name', 'CyberHausa Hospital') }}</h1>
        <p class="hospital-address">
            123 Medical Drive, Tech City, Nigeria<br>
            contact@cyberhausa-hms.com | +234 800 123 4567
        </p>
    </div>

    <div class="patient-info">
        <div class="info-col">
            <h4>Patient Details</h4>
            <p>{{ $labTest->visit->patient->first_name }} {{ $labTest->visit->patient->last_name }}</p>
            <span style="font-size:12px; color:#666">{{ $labTest->visit->patient->gender }} | {{ \Carbon\Carbon::parse($labTest->visit->patient->date_of_birth)->age }} Years</span>
        </div>
        <div class="info-col">
            <h4>Patient ID</h4>
            <p>{{ $labTest->visit->patient->hospital_id }}</p>
        </div>
        <div class="info-col">
            <h4>Request Info</h4>
            <p>ID: #REQ-{{ $labTest->id }}</p>
            <span style="font-size:12px; color:#666">Dr. {{ $labTest->visit->doctor->name ?? 'Unknown' }}</span>
        </div>
    </div>

    <div class="content">
        <h3 class="test-title">{{ $labTest->test_name }} Result</h3>

        <table>
            <thead>
                <tr>
                    <th>Parameter</th>
                    <th>Result</th>
                    <th>Unit</th>
                    <th>Reference Range</th>
                </tr>
            </thead>
            <tbody>
                @if(is_array($labTest->result_data))
                    @foreach($labTest->result_data as $row)
                        @if(is_array($row))
                        <tr>
                            <td>{{ $row['parameter'] ?? '' }}</td>
                            <td style="font-weight: bold;">{{ $row['value'] ?? '' }}</td>
                            <td>{{ $row['unit'] ?? '' }}</td>
                            <td>{{ $row['range'] ?? '' }}</td>
                        </tr>
                        @endif
                    @endforeach
                @else
                    <tr>
                        <td colspan="4" style="text-align: center">No structured data available.</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div class="summary-box">
            <strong>Pathologist's Remarks:</strong><br>
            {{ $labTest->result_summary }}
        </div>
    </div>

    <div class="signature-lines">
        <div class="sig-box">
            <span style="font-weight: bold">{{ $labTest->technician_name ?? 'Lab Scientist' }}</span><br>
            <span style="font-size: 12px; color: #666">Laboratory Scientist</span>
        </div>
        <div class="sig-box">
            <span style="font-size: 12px; color: #666">Pathologist Signature</span>
        </div>
    </div>

    <div class="footer">
        Generated electronically on {{ now()->format('d M Y, h:i A') }} by {{ auth()->user()->name }}.<br>
        This is a computer-generated document.
    </div>

</body>
</html>
