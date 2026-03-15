<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Medical Record</title>
    <style>
        @page { margin: 14mm; }
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; color: #111827; font-size: 12px; }
        .header { border-bottom: 2px solid #0ea5a4; padding-bottom: 12px; margin-bottom: 14px; }
        .header-table { width: 100%; border-collapse: collapse; }
        .logo { width: 56px; height: 56px; border-radius: 999px; border: 1px solid #e5e7eb; }
        .title { font-size: 18px; font-weight: 800; margin: 0; }
        .subtitle { margin: 4px 0 0; color: #6b7280; font-size: 11px; }
        .grid { width: 100%; border-collapse: collapse; margin-top: 12px; }
        .cell { width: 50%; padding: 6px 6px 6px 0; vertical-align: top; }
        .card { border: 1px solid #e5e7eb; border-radius: 10px; padding: 10px 12px; }
        .label { font-size: 10px; text-transform: uppercase; letter-spacing: 0.08em; color: #6b7280; margin-bottom: 4px; }
        .value { font-size: 12px; font-weight: 700; color: #111827; }
        .muted { color: #6b7280; font-size: 11px; }
        .section { margin-top: 14px; }
        .section-title { font-size: 12px; font-weight: 800; margin: 0 0 8px; }
        .note { border: 1px solid #e5e7eb; border-radius: 10px; padding: 10px 12px; white-space: pre-wrap; line-height: 1.5; }
        .table { width: 100%; border-collapse: collapse; }
        .table th { text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: 0.08em; color: #6b7280; padding: 8px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; }
        .table td { padding: 8px; border-bottom: 1px solid #f1f5f9; vertical-align: top; }
        .strong { font-weight: 800; }
        .sig { width: 100%; border-collapse: collapse; margin-top: 18px; }
        .sig td { width: 50%; padding-top: 22px; }
        .sigline { border-top: 1px solid #111827; padding-top: 6px; font-size: 11px; }
        .footer { margin-top: 14px; padding-top: 10px; border-top: 1px solid #e5e7eb; font-size: 10px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="header">
        <table class="header-table">
            <tr>
                <td style="width:70px; vertical-align: top;">
                    @if($clinicLogoDataUri)
                        <img class="logo" src="{{ $clinicLogoDataUri }}" alt="Clinic">
                    @endif
                </td>
                <td style="vertical-align: top;">
                    <div class="title">Medical Record & Prescription</div>
                    <div class="subtitle">PetApp • Generated document</div>
                </td>
            </tr>
        </table>
    </div>

    <table class="grid">
        <tr>
            <td class="cell">
                <div class="card">
                    <div class="label">Clinic</div>
                    <div class="value">{{ $clinic->clinic_name ?? 'N/A' }}</div>
                </div>
            </td>
            <td class="cell">
                <div class="card">
                    <div class="label">Visit Date</div>
                    <div class="value">{{ $appointment->appointment_date ?? 'N/A' }}</div>
                </div>
            </td>
        </tr>
        <tr>
            <td class="cell">
                <div class="card">
                    <div class="label">Pet</div>
                    <div class="value">{{ $pet->name ?? 'N/A' }}</div>
                    <div class="muted">{{ $pet->species ?? '' }}</div>
                </div>
            </td>
            <td class="cell">
                <div class="card">
                    <div class="label">Weight</div>
                    <div class="value">{{ $record?->weight ? ($record->weight . ' kg') : 'N/A' }}</div>
                </div>
            </td>
        </tr>
        <tr>
            <td class="cell">
                <div class="card">
                    <div class="label">Health Condition</div>
                    <div class="value">{{ $record->health_condition ?? 'N/A' }}</div>
                </div>
            </td>
            <td class="cell">
                <div class="card">
                    <div class="label">Vaccination</div>
                    <div class="value">{{ $record->vaccine_status ?? 'N/A' }}</div>
                    <div class="muted">{{ $record->vaccination_dates ?? 'N/A' }}</div>
                </div>
            </td>
        </tr>
    </table>

    <div class="section">
        <div class="section-title">Veterinarian's Notes</div>
        <div class="note">{{ $record->vet_notes ?? 'N/A' }}</div>
    </div>

    <div class="section">
        <div class="section-title">Prescription</div>
        @if($medications && $medications->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 35%;">Medicine</th>
                        <th style="width: 35%;">Administration</th>
                        <th style="width: 30%;">Schedule</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($medications as $m)
                        <tr>
                            <td><span class="strong">{{ $m->medicine_name ?? 'N/A' }}</span></td>
                            <td>{{ $m->dosage ?? 'N/A' }}</td>
                            <td>{{ $m->schedule ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="muted">No prescription recorded for this visit.</div>
        @endif
    </div>

    <table class="sig">
        <tr>
            <td><div class="sigline">Veterinarian Signature</div></td>
            <td><div class="sigline">Pet Owner Signature</div></td>
        </tr>
    </table>

    <div class="footer">
        Generated: {{ now()->format('Y-m-d H:i') }} • &copy; {{ now()->year }} PetApp
    </div>
</body>
</html>

