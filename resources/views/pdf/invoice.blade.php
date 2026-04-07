<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 800px;
            padding: 40px;
            margin: 0 auto;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
        }
        
        .company-info h1 {
            color: #007bff;
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .invoice-info {
            text-align: right;
        }
        
        .invoice-info p {
            margin: 5px 0;
            font-weight: bold;
        }
        
        .customer-info {
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
        }
        
        .customer-details,
        .invoice-dates {
            width: 45%;
        }
        
        .customer-details h3,
        .invoice-dates h3 {
            color: #007bff;
            font-size: 14px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        
        .customer-details p,
        .invoice-dates p {
            margin: 5px 0;
            font-size: 13px;
        }
        
        table {
            width: 100%;
            margin: 30px 0;
            border-collapse: collapse;
        }
        
        thead {
            background-color: #f5f5f5;
            border-top: 2px solid #007bff;
            border-bottom: 2px solid #007bff;
        }
        
        th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
            color: #333;
            font-size: 13px;
            text-transform: uppercase;
        }
        
        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }
        
        tr:last-child td {
            border-bottom: 2px solid #007bff;
        }
        
        .text-right {
            text-align: right;
        }
        
        .totals {
            margin-top: 20px;
            margin-left: auto;
            width: 300px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 13px;
        }
        
        .total-row.subtotal {
            border-bottom: 1px solid #eee;
        }
        
        .total-row.tax {
            border-bottom: 2px solid #007bff;
        }
        
        .total-row.grand-total {
            font-weight: bold;
            font-size: 16px;
            color: #007bff;
            margin-top: 10px;
        }
        
        .notes {
            margin-top: 40px;
            padding: 20px;
            background-color: #f9f9f9;
            border-left: 4px solid #007bff;
            font-size: 12px;
        }
        
        .notes h4 {
            color: #007bff;
            margin-bottom: 8px;
        }
        
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 11px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <h1>INVOICE</h1>
                <p>Your Company Name</p>
            </div>
            <div class="invoice-info">
                <p>Invoice #: {{ $invoice_number }}</p>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="customer-info">
            <div class="customer-details">
                <h3>Bill To:</h3>
                <p>{{ $customer_name }}</p>
                <p>{{ $customer_email }}</p>
                <p>Your Company Address</p>
                <p>City, State, ZIP</p>
            </div>
            <div class="invoice-dates">
                <h3>Invoice Details:</h3>
                <p><strong>Issue Date:</strong> {{ $issue_date->format('M d, Y') }}</p>
                <p><strong>Due Date:</strong> {{ $due_date->format('M d, Y') }}</p>
                <p><strong>Status:</strong> <span style="color: #28a745; font-weight: bold;">Unpaid</span></p>
            </div>
        </div>

        <!-- Items Table -->
        <table>
            <thead>
                <tr>
                    <th style="width: 50%;">Description</th>
                    <th style="width: 15%; text-align: right;">Quantity</th>
                    <th style="width: 15%; text-align: right;">Unit Price</th>
                    <th style="width: 20%; text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>{{ $item['description'] }}</td>
                    <td class="text-right">{{ $item['quantity'] }}</td>
                    <td class="text-right">${{ number_format($item['unit_price'], 2) }}</td>
                    <td class="text-right">${{ number_format($item['total'], 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals">
            <div class="total-row subtotal">
                <span>Subtotal:</span>
                <span>${{ number_format($subtotal, 2) }}</span>
            </div>
            <div class="total-row tax">
                <span>Tax ({{ $tax_rate }}%):</span>
                <span>${{ number_format($tax_amount, 2) }}</span>
            </div>
            <div class="total-row grand-total">
                <span>Total Amount Due:</span>
                <span>${{ number_format($total, 2) }}</span>
            </div>
        </div>

        <!-- Notes -->
        <div class="notes">
            <h4>Notes:</h4>
            <p>Thank you for your business! Payment is due within 30 days of issue date. Please include the invoice number in your payment.</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Generated on {{ now()->format('M d, Y H:i:s') }} | This is an automated invoice | Page 1 of 1</p>
        </div>
    </div>
</body>
</html>
