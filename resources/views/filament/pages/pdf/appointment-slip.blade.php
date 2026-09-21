<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <style>
        @page {
            margin: 30px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1f2937;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
        }

        .title {
            font-size: 22px;
            font-weight: bold;
            color: #1E50A1;
        }

        .subtitle {
            color: #6b7280;
            margin-top: 5px;
        }

        .card {
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 20px;
        }

        .section-title {
            font-size: 15px;
            font-weight: bold;
            color: #1E50A1;
            margin-bottom: 15px;
        }

        .row {
            margin-bottom: 12px;
        }

        .label {
            color: #6b7280;
            font-size: 10px;
            margin-bottom: 3px;
        }

        .value {
            font-size: 13px;
            font-weight: bold;
        }

        .notice {
            margin-top: 20px;
            padding: 12px;
            background: #f5f8ff;
            border: 1px solid #dbe7ff;
            border-radius: 6px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            color: #6b7280;
            font-size: 10px;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="title">
            SSS Online Appointment
        </div>

        <div class="subtitle">
            Appointment Slip
        </div>
    </div>

    <div class="card">

        <div class="section-title">
            Appointment Details
        </div>

        <div class="row">
            <div class="label">MEMBER NAME</div>
            <div class="value">
                {{ $user?->firstname }} {{ $user?->lastname }}
            </div>
        </div>

        <div class="row">
            <div class="label">EMAIL</div>
            <div class="value">
                {{ $user?->email }}
            </div>
        </div>

        <div class="row">
            <div class="label">SSS BRANCH</div>
            <div class="value">
                {{ $branch?->name }}
            </div>
        </div>

        <div class="row">
            <div class="label">TRANSACTION</div>
            <div class="value">
                {{ $transaction['name'] ?? '' }}
            </div>
        </div>

        <div class="row">
            <div class="label">APPOINTMENT DATE</div>
            <div class="value">
                {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}
            </div>
        </div>

        <div class="row">
            <div class="label">APPOINTMENT TIME</div>
            <div class="value">
                {{ \Carbon\Carbon::createFromFormat('H:i', $time)->format('h:i A') }}
            </div>
        </div>

    </div>

    <div class="notice">
        <strong>Important Notice</strong>
        <br><br>
        Please bring the necessary documents for your selected transaction
        and arrive at the branch before your scheduled appointment time.
    </div>

    <div class="footer">
        This is a system-generated appointment slip.
        <br>
        SSS Online Services
    </div>

</body>
</html>