<!DOCTYPE html>
<html>
<head>
    <title>ESA Certificate</title>
    <style>
        body { font-family: sans-serif; text-align: center; padding: 50px; }
        .border { border: 5px solid #444; padding: 20px; height: 90%; }
        h1 { color: #2c3e50; margin-bottom: 10px; }
        h2 { color: #555; margin-top: 0; }
        .content { margin-top: 50px; font-size: 18px; line-height: 1.6; }
        .pets { margin-top: 30px; font-weight: bold; }
        .footer { margin-top: 100px; font-size: 14px; color: #777; }
    </style>
</head>
<body>
    <div class="border">
        <h1>Emotional Support Animal</h1>
        <h2>Official Certificate of Registration</h2>

        <div class="content">
            <p>This certifies that the emotional support animal(s) listed below</p>
            <p>are essential to the well-being of:</p>
            
            <h3>{{ $request->certificate_name }}</h3>
            
            <div class="pets">
                @foreach($pets as $pet)
                    <p>{{ $pet->name }} ({{ $pet->type }} - {{ $pet->breed ?? 'Unknown' }})</p>
                @endforeach
            </div>

            <p>Registered on: {{ $date }}</p>
        </div>

        <div class="footer">
            <p>US Service Animals & Dog Academy</p>
            <p>Authorized by Licensed Therapist</p>
            <p style="margin-top: 12px; font-size: 11px; color: #aaa;">
                This document is part of a demonstration project and has no legal or real value.
            </p>
        </div>
    </div>
</body>
</html>