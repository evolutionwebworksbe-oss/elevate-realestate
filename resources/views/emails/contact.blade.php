<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #2B6B7F; color: white; padding: 20px; text-align: center; }
        .content { background: #f9f9f9; padding: 20px; }
        .field { margin-bottom: 15px; }
        .label { font-weight: bold; color: #2B6B7F; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Nieuw Contact Formulier</h2>
        </div>
        <div class="content">
            <div class="field">
                <span class="label">Naam:</span> {{ $name }}
            </div>
            <div class="field">
                <span class="label">Email:</span> {{ $email }}
            </div>
            @if($phone)
            <div class="field">
                <span class="label">Telefoon:</span> {{ $phone }}
            </div>
            @endif
            <div class="field">
                <span class="label">Onderwerp:</span> {{ $subject }}
            </div>
            @if(isset($property_id))
            <div class="field">
                <span class="label">Object ID:</span> {{ $property_id }}
            </div>
            @endif
            <div class="field">
                <span class="label">Bericht:</span>
                <p>{{ $message }}</p>
            </div>
        </div>
    </div>
</body>
</html>