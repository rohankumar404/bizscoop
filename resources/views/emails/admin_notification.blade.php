<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; }
        .header { background: #e60000; color: #fff; padding: 15px; text-align: center; }
        .content { padding: 20px; }
        .label { font-weight: bold; color: #666; margin-top: 10px; display: block; text-transform: uppercase; font-size: 11px; }
        .value { font-size: 16px; margin-bottom: 15px; display: block; }
        .footer { font-size: 12px; color: #999; text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>New Lead: {{ ucfirst(str_replace('_', ' ', $lead->type)) }}</h1>
        </div>
        <div class="content">
            <span class="label">Name</span>
            <span class="value">{{ $lead->name }}</span>

            <span class="label">Email</span>
            <span class="value">{{ $lead->email }}</span>

            @if($lead->subject)
                <span class="label">Subject</span>
                <span class="value">{{ $lead->subject }}</span>
            @endif

            @if($lead->type === 'service_inquiry' && isset($lead->metadata['service']))
                <span class="label">Interested Service</span>
                <span class="value">{{ $lead->metadata['service'] }}</span>
            @endif

            <span class="label">Message</span>
            <span class="value">{{ $lead->message }}</span>

            <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
            <p class="footer">Received on {{ $lead->created_at->format('M d, Y H:i') }}</p>
        </div>
    </div>
</body>
</html>
