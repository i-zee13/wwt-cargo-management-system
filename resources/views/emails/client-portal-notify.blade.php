<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
</head>
<body style="font-family: Poppins, Arial, sans-serif; background-color: #f6f6f6; color: #333; margin: 0; padding: 30px 20px;">

<div style="max-width: 600px; margin: auto; background: #fff; border-radius: 0.75rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); overflow: hidden;">
    <div style="background-color: #eb973c; color: #fff; padding: 20px; text-align: center;">
        <h1 style="font-size: 1.5rem; margin: 0;">{{ config('brand.short_name', 'WWT') }} — Nuevo portal</h1>
    </div>

    <div style="padding: 24px 20px;">
        <div style="color:#92400e;font-weight:700;background:#fffbeb;padding:12px 14px;border-left:4px solid #f59e0b;border-radius:4px;margin:0 0 20px;font-size:0.9rem;line-height:1.5;">
            ⚠️ Importante: El enlace para restablecer / crear su contraseña expira en
            <strong>{{ (int) $expireMinutes }} minutos</strong>.
            Si expira, use “Olvidé mi contraseña” en el portal:
            <a href="{{ $portalUrl }}" style="color:#92400e;">{{ $portalUrl }}</a>
        </div>

        <div style="font-size: 0.95rem; line-height: 1.65; white-space: pre-wrap;">{!! nl2br(e($bodyContent)) !!}</div>

        <div style="text-align:center;margin-top:28px;">
            <a href="{{ $resetUrl }}"
               style="display:inline-block;padding:12px 28px;background:#eb973c;color:#fff;text-decoration:none;border-radius:0.75rem;font-weight:600;">
                Crear / restablecer contraseña
            </a>
        </div>
    </div>

    <div style="background-color: #f3f3f3; padding: 15px 20px; font-size: 0.75rem; text-align: center; color: #666;">
        <img src="{{ brandLogoUrl() }}" alt="{{ config('brand.name') }}" style="max-width: 180px; margin-bottom: 10px;">
        <p style="font-size: 0.75rem; color: #666; margin: 0;">{!! $footerContent !!}</p>
    </div>
</div>

</body>
</html>
