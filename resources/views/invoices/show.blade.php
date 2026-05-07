<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Invoice #{{ $invoice->id }}</title>

<style>
/* =========================
   BASE RESET
========================= */
* {
    box-sizing: border-box;
    font-family: "Inter", "Segoe UI", Arial, sans-serif;
}

body {
    margin: 0;
    background: #f4f6f8;
    color: #111;
}

/* =========================
   PRINT CONFIG
========================= */
@page {
    size: A4;
    margin: 18mm;
}

@media print {
    body {
        background: #fff;
    }
    .no-print {
        display: none !important;
    }
}

/* =========================
   INVOICE WRAPPER
========================= */
.invoice-wrapper {
    max-width: 820px;
    margin: 30px auto;
    background: #fff;
    border-radius: 12px;
    padding: 32px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
}

/* =========================
   HEADER
========================= */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
}

.brand h1 {
    margin: 0;
    font-size: 28px;
    letter-spacing: 1px;
}

.brand small {
    color: #666;
    font-size: 13px;
}

.invoice-meta {
    text-align: right;
    font-size: 13px;
}

.invoice-meta div {
    margin-bottom: 4px;
}

/* =========================
   DIVIDER
========================= */
.divider {
    height: 1px;
    background: #e5e7eb;
    margin: 24px 0;
}

/* =========================
   INFO GRID
========================= */
.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.info-card {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 14px;
    font-size: 13px;
}

.info-title {
    font-weight: 600;
    font-size: 11px;
    text-transform: uppercase;
    color: #6b7280;
    margin-bottom: 6px;
}

/* =========================
   PURPOSE
========================= */
.purpose-box {
    margin-top: 20px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 14px;
    font-size: 13px;
}

/* =========================
   TABLE
========================= */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 24px;
    font-size: 13px;
}

th {
    text-align: left;
    background: #f9fafb;
    padding: 12px;
    border-bottom: 2px solid #e5e7eb;
}

td {
    padding: 12px;
    border-bottom: 1px solid #e5e7eb;
}

.amount {
    text-align: right;
}

/* =========================
   TOTAL
========================= */
.total-row td {
    font-weight: 700;
    font-size: 14px;
}

/* =========================
   FOOTER
========================= */
.footer {
    display: flex;
    justify-content: space-between;
    margin-top: 48px;
    font-size: 13px;
    color: #444;
}

.signature {
    text-align: right;
}

.signature-line {
    width: 200px;
    border-top: 1px solid #111;
    margin-top: 40px;
}

/* =========================
   PRINT BUTTON
========================= */
.no-print {
    text-align: center;
    margin: 25px 0;
}

.print-btn {
    padding: 12px 26px;
    border-radius: 8px;
    border: none;
    background: #111827;
    color: #fff;
    font-size: 14px;
    cursor: pointer;
}

.print-btn:hover {
    opacity: 0.9;
}
</style>
</head>

<body>

<div class="invoice-wrapper">

    {{-- HEADER --}}
    <div class="header">
        <div class="brand">
            <h1>INVOICE</h1>
            <small>Medical Consultation</small>
        </div>
        <div class="invoice-meta">
            <div><strong>Invoice #</strong>{{ $invoice->id }}</div>
            <div><strong>Date:</strong> {{ optional($invoice->date)->format('d M Y') }}</div>
        </div>
    </div>

    <div class="divider"></div>

    {{-- INFO --}}
    <div class="info-grid">
        <div class="info-card">
            <div class="info-title">Doctor</div>
            <strong>{{ $invoice->doctor?->name }}</strong><br>
            {{ $invoice->doctor?->email }}
        </div>

        <div class="info-card">
            <div class="info-title">Patient</div>
            <strong>{{ $invoice->patient?->name }}</strong><br>
            {{ $invoice->patient?->email }}
        </div>
    </div>

    {{-- PURPOSE --}}
    <div class="purpose-box">
        <div class="info-title">Purpose</div>
        {{ $invoice->purpose ?? 'Consultation Fee' }}
    </div>

    {{-- TABLE --}}
    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th class="amount">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $invoice->purpose ?? 'Consultation Fee' }}</td>
                <td class="amount">{{ number_format($invoice->amount,2) }}</td>
            </tr>
            <tr class="total-row">
                <td>Total</td>
                <td class="amount">{{ number_format($invoice->amount,2) }}</td>
            </tr>
        </tbody>
    </table>

    {{-- FOOTER --}}
    <div class="footer">
        <div>
            <strong>Notes</strong><br>
            This invoice is system generated.<br>
            Thank you for your visit.
        </div>
        <div class="signature">
            Authorized Signature
            <div class="signature-line"></div>
        </div>
    </div>

</div>

{{-- PRINT --}}
<div class="no-print">
    <button class="print-btn" onclick="window.print()">Print Invoice</button>
</div>

</body>
</html>
