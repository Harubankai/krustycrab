<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Password</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --kk-burgundy: #50061e;
            --kk-burgundy-2: #7a0a2e;
            --kk-gold: #fce206;
            --kk-deep-blue: #031b4e;
            --kk-ink: #16324f;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Poppins', system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            color: var(--kk-ink);
            background:
                linear-gradient(rgba(0, 40, 92, 0.40), rgba(0, 29, 92, 0.40)),
                url("{{ asset('images/bg4.jpg') }}") center / cover no-repeat;
        }

        .reset-wrap {
            min-height: 100vh;
            padding: 120px 20px 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .reset-wrap::before {
            content: "";
            position: absolute;
            inset: 0;
            background: url("{{ asset('images/logo.png') }}") center / 520px no-repeat;
            opacity: 0.08;
            pointer-events: none;
        }

        .reset-card {
            width: 100%;
            max-width: 420px;
            background: rgba(255, 255, 255, 0.94);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.35);
            border: 2px solid rgba(80, 6, 30, 0.12);
            backdrop-filter: blur(6px);
            padding: 44px 40px;
            position: relative;
            z-index: 1;
        }

        .reset-card::after {
            content: "";
            position: absolute;
            left: 18px;
            right: 18px;
            top: 16px;
            height: 6px;
            border-radius: 99px;
            background: linear-gradient(90deg, rgba(252, 226, 6, 0.2), rgba(252, 226, 6, 0.9), rgba(252, 226, 6, 0.2));
        }

        .reset-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            margin-bottom: 14px;
            text-align: center;
        }

        .reset-logo {
            height: 46px;
            width: auto;
        }

        .reset-header h2 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            color: var(--kk-burgundy);
            letter-spacing: 0.2px;
        }

        .reset-note {
            margin: 0 0 22px;
            text-align: center;
            font-size: 0.95rem;
            color: rgba(22, 50, 79, 0.9);
        }

        .alert {
            padding: 10px 12px;
            border-radius: 12px;
            margin: 0 0 14px;
            font-size: 0.92rem;
            line-height: 1.4;
        }

        .alert--error {
            background: #fff0f3;
            border: 1.5px solid rgba(122, 10, 46, 0.55);
            color: var(--kk-burgundy-2);
        }

        .alert--success {
            background: #f0fff7;
            border: 1.5px solid rgba(5, 110, 58, 0.35);
            color: #056e3a;
        }

        form { margin-top: 8px; }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 14px;
        }

        label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--kk-burgundy);
        }

        input {
            width: 100%;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1.5px solid rgba(80, 6, 30, 0.65);
            background: #ffffff;
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        }

        input:focus {
            border-color: var(--kk-burgundy-2);
            box-shadow: 0 0 0 4px rgba(252, 226, 6, 0.25);
            transform: translateY(-1px);
        }

        .btn {
            width: 100%;
            margin-top: 8px;
            padding: 12px 14px;
            border: none;
            border-radius: 12px;
            background-color: var(--kk-burgundy);
            color: #ffffff;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn:hover {
            background-color: var(--kk-burgundy-2);
            transform: translateY(-2px);
            box-shadow: 0 12px 22px rgba(0, 0, 0, 0.18);
        }

        .btn:active {
            transform: translateY(0);
            box-shadow: none;
        }

        .back-link {
            display: block;
            margin-top: 14px;
            text-align: center;
            font-size: 0.9rem;
            color: var(--kk-burgundy);
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
            color: var(--kk-burgundy-2);
        }

        @media (max-width: 420px) {
            .reset-card { padding: 38px 22px; }
            .reset-header h2 { font-size: 1.75rem; }
        }
    </style>
</head>
<body>
    <main class="reset-wrap">
        <div class="reset-card">
            <div class="reset-header">
                <img src="{{ asset('images/logo.png') }}" alt="Krusty Krab" class="reset-logo">
                <h2>Reset Password</h2>
            </div>

            @if(session('success'))
                <div class="alert alert--success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert--error">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert--error">
                    {{ $errors->first() }}
                </div>
            @endif

            <p class="reset-note">Enter your new password below. This link will expire in 1 hour.</p>

            <form action="{{ route('reset.password.post') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" name="password" id="password" required>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required>
                </div>

                <button type="submit" class="btn">Reset Password</button>
                <a class="back-link" href="{{ route('hexavers') }}#login">Back to Login</a>
            </form>
        </div>
    </main>
</body>
</html>
