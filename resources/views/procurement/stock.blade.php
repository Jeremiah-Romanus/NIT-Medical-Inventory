<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Stock - Procurement</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f5f5f5; }
        .navbar { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 20px; display: flex; justify-content: space-between; align-items: center; }
        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
        .back-btn { background: #f5576c; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; margin-bottom: 20px; display: inline-block; }
        .content { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); }
        h1 { color: #333; margin-bottom: 20px; }
        p { color: #666; font-size: 16px; line-height: 1.6; }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Update Stock</h1>
    </div>
    <div class="container">
        <a href="{{ route('procurement.dashboard') }}" class="back-btn">← Back to Dashboard</a>
        <div class="content">
            <h1>Inventory Stock Management</h1>
            <p>Add new medicines and update quantities in the inventory.</p>
            <p>Features coming soon:</p>
            <ul style="margin-left: 20px; margin-top: 15px;">
                <li>Add new medicine entries</li>
                <li>Update existing medicine quantities</li>
                <li>Record batch numbers and expiry dates</li>
                <li>Manage unit prices</li>
            </ul>
        </div>
    </div>
</body>
</html>
