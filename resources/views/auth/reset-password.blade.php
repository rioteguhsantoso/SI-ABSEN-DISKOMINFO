<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Kata Sandi Baru - SI-ABSEN</title>
    <style>
        * {
            box-sizing: border-box
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: #f1f5f9;
            font-family: Arial, sans-serif;
            color: #1e293b
        }

        .card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            padding: 32px;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(15, 23, 42, .08)
        }

        h1 {
            font-size: 24px;
            margin: 0 0 10px
        }

        p {
            font-size: 14px;
            line-height: 1.6;
            color: #64748b;
            margin: 0 0 24px
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 15px;
            margin-bottom: 16px
        }

        button {
            width: 100%;
            padding: 13px;
            border: 0;
            border-radius: 8px;
            background: #2563eb;
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #2563eb;
            text-decoration: none;
            font-size: 14px
        }

        .message {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 16px;
            background: #fee2e2;
            color: #991b1b;
            font-size: 14px;
            line-height: 1.5
        }
    </style>
</head>

<body>
    <main class="card">
        <h1>Atur Kata Sandi Baru</h1>
        <p>Buat kata sandi baru minimal 8 karakter untuk akun SI-ABSEN kamu.</p>
        @if ($errors->any())
        <div class="message">
            @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
            @endforeach
        </div>
        @endif
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <label for="email">Alamat Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $email) }}" required autocomplete="email">
            <label for="password">Kata Sandi Baru</label>
            <input id="password" type="password" name="password" minlength="8" required autocomplete="new-password">
            <label for="password_confirmation">Konfirmasi Kata Sandi</label>
            <input id="password_confirmation" type="password" name="password_confirmation" minlength="8" required autocomplete="new-password">
            <button type="submit">Simpan Kata Sandi Baru</button>
        </form>
        <a href="{{ route('password.request') }}">← Kembali</a>
    </main>
</body>

</html>