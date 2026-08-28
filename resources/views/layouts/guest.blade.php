@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ? $title . ' - Línea 61' : config('app.name', 'Línea 61') }}</title>

        <!-- Google Fonts: Inter -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Bootstrap 5 CSS & Icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

        <style>
            :root {
                --primary-blue: #0B3C78;
                --primary-blue-dark: #07254A;
                --primary-blue-hover: #082E5C;
                --accent-guindo: #7B1E2B;
                --accent-guindo-hover: #9E2235;
                --text-dark: #0F172A;
                --text-muted: #64748B;
            }
            * {
                font-family: 'Inter', sans-serif;
                box-sizing: border-box;
            }
            html, body {
                height: 100%;
                margin: 0;
                padding: 0;
                background-color: #FFFFFF;
                overflow-x: hidden;
            }
            
            /* Full-screen auth split layout */
            .auth-fullscreen {
                min-height: 100vh;
                width: 100vw;
                display: flex;
            }

            .auth-hero-side {
                position: relative;
                overflow: hidden;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                padding: 3.5rem 3rem;
                color: #FFFFFF;
            }

            .auth-hero-overlay {
                position: absolute;
                inset: 0;
                background: linear-gradient(180deg, rgba(7, 30, 61, 0.72) 0%, rgba(11, 60, 120, 0.75) 50%, rgba(123, 30, 43, 0.8) 100%);
                z-index: 1;
            }

            .auth-hero-content {
                position: relative;
                z-index: 2;
            }

            .auth-form-side {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                background-color: #FFFFFF;
                padding: 2.5rem 2rem;
                overflow-y: auto;
            }

            .auth-form-container {
                width: 100%;
                max-width: 460px;
            }
            
            .auth-form-container-wide {
                width: 100%;
                max-width: 620px;
            }

            /* Single centered screen for forgot password / confirm / verify */
            .auth-single-screen {
                min-height: 100vh;
                width: 100vw;
                display: flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(135deg, #071E3D 0%, #0B3C78 60%, #162E52 100%);
                padding: 2rem 1rem;
            }
            .auth-single-box {
                width: 100%;
                max-width: 480px;
                background: #FFFFFF;
                border-radius: 20px;
                box-shadow: 0 20px 50px rgba(0,0,0,0.35);
                padding: 2.5rem 2.2rem;
            }

            /* Form Elements */
            .form-label-custom {
                font-size: 0.78rem;
                font-weight: 700;
                color: #334155;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                margin-bottom: 0.35rem;
            }
            .form-control-custom {
                border: 1.5px solid #E2E8F0;
                border-radius: 10px;
                padding: 0.72rem 1rem;
                font-size: 0.92rem;
                color: #0F172A;
                background-color: #F8FAFC;
                transition: all 0.2s ease;
            }
            .form-control-custom:focus {
                background-color: #FFFFFF;
                border-color: var(--primary-blue);
                box-shadow: 0 0 0 4px rgba(11, 60, 120, 0.12);
                color: #0F172A;
            }
            
            /* Botón Azul Primario (Alta visibilidad y contraste) */
            .btn-blue-auth {
                background: linear-gradient(135deg, #0B3C78 0%, #07254A 100%) !important;
                color: #FFFFFF !important;
                border: none !important;
                border-radius: 10px !important;
                font-weight: 700 !important;
                font-size: 0.98rem !important;
                padding: 0.8rem 1.4rem !important;
                transition: all 0.2s ease !important;
                box-shadow: 0 4px 14px rgba(11, 60, 120, 0.35) !important;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                text-decoration: none !important;
            }
            .btn-blue-auth:hover {
                background: linear-gradient(135deg, #0D4994 0%, #0A3266 100%) !important;
                color: #FFFFFF !important;
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(11, 60, 120, 0.45) !important;
            }

            /* Botón Azul Secundario */
            .btn-blue-outline {
                background-color: #F0F6FF !important;
                color: #0B3C78 !important;
                border: 1.5px solid #BFDBFE !important;
                border-radius: 10px !important;
                font-weight: 700 !important;
                font-size: 0.92rem !important;
                padding: 0.72rem 1.2rem !important;
                transition: all 0.2s ease !important;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                text-decoration: none !important;
            }
            .btn-blue-outline:hover {
                background-color: #0B3C78 !important;
                color: #FFFFFF !important;
                border-color: #0B3C78 !important;
                transform: translateY(-1px);
            }

            /* Enlaces en Guindo */
            .link-guindo {
                color: var(--accent-guindo) !important;
                font-weight: 700 !important;
                text-decoration: none !important;
                font-size: 0.88rem;
                transition: all 0.15s ease;
            }
            .link-guindo:hover {
                color: var(--accent-guindo-hover) !important;
                text-decoration: underline !important;
            }

            /* Separador */
            .auth-divider {
                display: flex;
                align-items: center;
                text-align: center;
                margin: 1.35rem 0;
                color: #94A3B8;
                font-size: 0.78rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.06em;
            }
            .auth-divider::before, .auth-divider::after {
                content: '';
                flex: 1;
                border-bottom: 1px solid #E2E8F0;
            }
            .auth-divider span {
                padding: 0 0.85rem;
            }
        </style>
    </head>
    <body>
        {{ $slot }}

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
