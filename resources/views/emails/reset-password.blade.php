<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Redefinição de senha — Bolão Copa 2026</title>
</head>
<body style="margin:0;padding:0;background-color:#f1f5f9;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f1f5f9;padding:40px 16px;">
  <tr>
    <td align="center">
      <table width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;">

        {{-- Header com gradiente verde Brasil --}}
        <tr>
          <td style="background:linear-gradient(135deg,#009c3b 0%,#007a2f 100%);border-radius:16px 16px 0 0;padding:32px 40px;text-align:center;">
            <p style="margin:0;font-size:36px;line-height:1;">⚽</p>
            <p style="margin:8px 0 0;font-size:24px;font-weight:700;color:#ffffff;letter-spacing:0.5px;">
              Bolão <span style="color:#ffdf00;">Copa</span> 2026
            </p>
          </td>
        </tr>

        {{-- Faixa amarela --}}
        <tr>
          <td style="background-color:#ffdf00;height:5px;font-size:0;line-height:0;">&nbsp;</td>
        </tr>

        {{-- Body --}}
        <tr>
          <td style="background-color:#ffffff;padding:40px 40px 32px;">
            <h1 style="margin:0 0 8px;font-size:20px;font-weight:700;color:#1a1a1a;">
              Olá, {{ $name }}!
            </h1>
            <p style="margin:0 0 24px;font-size:15px;color:#64748b;line-height:1.6;">
              Recebemos uma solicitação para redefinir a senha da sua conta no <strong style="color:#1a1a1a;">Bolão Copa 2026</strong>.
              Clique no botão abaixo para criar uma nova senha.
            </p>

            {{-- CTA Button --}}
            <table width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 24px;">
              <tr>
                <td align="center">
                  <a href="{{ $url }}"
                     style="display:inline-block;background-color:#009c3b;color:#ffffff;font-size:15px;font-weight:700;
                            text-decoration:none;padding:14px 36px;border-radius:10px;letter-spacing:0.3px;">
                    Redefinir minha senha
                  </a>
                </td>
              </tr>
            </table>

            {{-- Expiry warning --}}
            <table width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 24px;">
              <tr>
                <td style="background-color:#fffbeb;border:1px solid #ffdf00;border-radius:8px;padding:12px 16px;">
                  <p style="margin:0;font-size:13px;color:#7a5f00;">
                    ⏱ Este link expira em <strong>60 minutos</strong>.
                  </p>
                </td>
              </tr>
            </table>

            <p style="margin:0;font-size:13px;color:#94a3b8;line-height:1.6;">
              Se você não solicitou a redefinição de senha, pode ignorar este e-mail com segurança — sua senha não será alterada.
            </p>
          </td>
        </tr>

        {{-- URL fallback --}}
        <tr>
          <td style="background-color:#f8fafc;border-top:1px solid #e2e8f0;padding:20px 40px;">
            <p style="margin:0 0 6px;font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:0.5px;">
              Se o botão não funcionar, copie e cole este link no navegador:
            </p>
            <p style="margin:0;font-size:11px;color:#009c3b;word-break:break-all;">
              {{ $url }}
            </p>
          </td>
        </tr>

        {{-- Footer --}}
        <tr>
          <td style="background:linear-gradient(135deg,#009c3b 0%,#007a2f 100%);border-radius:0 0 16px 16px;padding:20px 40px;text-align:center;">
            <p style="margin:0;font-size:12px;color:#d4f7e0;">
              Abraços, equipe <strong style="color:#ffdf00;">Bolão Copa 2026</strong> 🇧🇷
            </p>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>

</body>
</html>
