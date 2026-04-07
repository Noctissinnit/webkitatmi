<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
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
            padding: 40px;
        }
        
        .report-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
        }
        
        .report-header h1 {
            color: #007bff;
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .report-meta {
            font-size: 12px;
            color: #666;
        }
        
        .summary-section {
            margin-bottom: 30px;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
        }
        
        .summary-section h3 {
            color: #007bff;
            margin-bottom: 15px;
            font-size: 16px;
            text-transform: uppercase;
        }
        
        .summary-stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }
        
        .stat-box {
            text-align: center;
            padding: 15px;
            background-color: white;
            border-radius: 3px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            flex: 1;
            margin: 0 10px;
        }
        
        .stat-box .stat-value {
            font-size: 24px;
            color: #007bff;
            font-weight: bold;
        }
        
        .stat-box .stat-label {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
            text-transform: uppercase;
        }
        
        table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }
        
        thead {
            background-color: #007bff;
            color: white;
        }
        
        th {
            padding: 15px;
            text-align: left;
            font-weight: bold;
            font-size: 13px;
            text-transform: uppercase;
        }
        
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            font-size: 12px;
        }
        
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        tbody tr:hover {
            background-color: #f0f0f0;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-active {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-inactive {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .footer-section {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
            text-align: center;
            font-size: 11px;
            color: #999;
        }
        
        .footer-section p {
            margin: 5px 0;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Report Header -->
        <div class="report-header">
            <h1>{{ $title }}</h1>
            <div class="report-meta">
                <p>Generated: {{ $generated_at->format('M d, Y H:i:s') }}</p>
                <p>Report Type: User Summary Report</p>
            </div>
        </div>

        <!-- Summary Statistics -->
        <div class="summary-section">
            <h3>Summary Statistics</h3>
            <div class="summary-stats">
                <div class="stat-box">
                    <div class="stat-value">{{ $total_users }}</div>
                    <div class="stat-label">Total Users</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value">{{ $users->where('created_at', '>=', now()->startOfMonth())->count() }}</div>
                    <div class="stat-label">New This Month</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value">{{ $users->count() }}</div>
                    <div class="stat-label">Active Users</div>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="summary-section">
            <h3>User Details</h3>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">ID</th>
                        <th style="width: 30%;">Name</th>
                        <th style="width: 35%;">Email</th>
                        <th style="width: 20%;">Created Date</th>
                        <th style="width: 10%;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            <span class="status-badge status-active">Active</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Report Notes -->
        <div class="summary-section">
            <h3>Report Notes</h3>
            <ul style="margin-left: 20px; font-size: 12px;">
                <li>This report shows all registered users in the system</li>
                <li>Status indicates whether the user account is active</li>
                <li>Created date shows when the user account was created</li>
                <li>For more detailed information, please contact the system administrator</li>
            </ul>
        </div>

        <!-- Footer -->
        <div class="footer-section">
            <p>&copy; {{ now()->year }} Your Company Name. All rights reserved.</p>
            <p>This is an automatically generated report. For questions, contact the system administrator.</p>
            <p>Generated on {{ now()->format('M d, Y \a\t H:i:s') }}</p>
        </div>
    </div>
</body>
</html>
