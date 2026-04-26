<!DOCTYPE html>
<html lang="fil">
<head>
  <meta charset="UTF-8"/>
  <style>
    body { font-family: 'Georgia', serif; background: #f5f0e8; margin: 0; padding: 40px 20px; }
    .card { max-width: 480px; margin: 0 auto; background: #fff; border-radius: 12px; border: 1px solid rgba(184,146,74,0.2); padding: 48px 40px; text-align: center; }
    .logo { font-size: 22px; letter-spacing: 0.2em; color: #b8924a; text-transform: uppercase; margin-bottom: 8px; }
    .subtitle { font-family: 'Arial', sans-serif; font-size: 11px; letter-spacing: 0.2em; color: #9a7a50; text-transform: uppercase; margin-bottom: 32px; }
    .divider { width: 40px; height: 1px; background: #b8924a; margin: 0 auto 32px; }
    h2 { font-size: 18px; color: #2c1a0e; font-weight: normal; margin: 0 0 12px; }
    p { font-family: 'Arial', sans-serif; font-size: 14px; color: #4a2e1a; line-height: 1.6; margin: 0 0 28px; }
    .code { font-family: 'Courier New', monospace; font-size: 42px; font-weight: bold; letter-spacing: 12px; color: #2c1a0e; background: #f5f0e8; border: 1px solid rgba(184,146,74,0.3); border-radius: 8px; padding: 16px 24px; display: inline-block; margin-bottom: 28px; }
    .note { font-family: 'Arial', sans-serif; font-size: 12px; color: #9a7a50; }
    .footer { font-family: 'Arial', sans-serif; font-size: 11px; color: #b8924a; margin-top: 32px; letter-spacing: 0.15em; text-transform: uppercase; }
  </style>
</head>
<body>
  <div class="card">
    <div class="logo">Andaya's</div>
    <div class="subtitle">Native Products</div>
    <div class="divider"></div>
    <h2>Email Verification</h2>
    <p>Gamitin ang code na ito para ma-verify ang iyong email address. Mag-e-expire ito sa loob ng <strong>10 minuto</strong>.</p>
    <div class="code">{{ $code }}</div>
    <p class="note">Kung hindi ikaw ang nag-register, huwag pansinin ang email na ito.</p>
    <div class="footer">© 2025 Andaya's Native Products</div>
  </div>
</body>
</html>
