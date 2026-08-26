<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('code', 'Error') — @yield('title', 'Error') | {{ config('app.name', 'IAM Fleet') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=geist:400,500,600|geist-mono:400,500|inter:400,500,600" rel="stylesheet" />
    <style>
        :root {
            /* Colors */
            --color-canvas-white: #ffffff;
            --color-ghost-gray: #f2f2f2;
            --color-subtle-ash: #e5e5e5;
            --color-midtone-gray: #737373;
            --color-rich-black: #0a0a0a;
            --color-deep-black: #000000;
            --color-callout-red: #c22b10;
            --color-success-green: #10c22b;

            /* Typography */
            --font-geist: 'Geist', 'Inter', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            --font-geist-mono: 'Geist Mono', ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;

            /* Typography Scale */
            --text-caption: 12px;
            --leading-caption: 1.5;
            --text-body: 14px;
            --leading-body: 1.43;
            --text-heading: 18px;
            --leading-heading: 1.33;
            --tracking-heading: -0.45px;
            --text-display: 48px;
            --leading-display: 1;
            --tracking-display: -2.4px;

            /* Radii */
            --radius-cards: 14px;
            --radius-buttons: 10px;
            --radius-badge: 26px;
            --radius-pill: 9999px;

            /* Elevation */
            --shadow-subtle: 0 0 0 2px #ffffff;
            --shadow-card: 0px 0px 0px 1px rgba(0, 0, 0, 0.08), 0px 1px 2px 0px rgba(0, 0, 0, 0.04);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-geist);
            background-color: var(--color-canvas-white);
            color: var(--color-rich-black);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .error-wrapper {
            width: 100%;
            max-width: 480px;
            margin: 0 auto;
        }

        .error-card {
            background-color: var(--color-canvas-white);
            border: 1px solid var(--color-subtle-ash);
            border-radius: var(--radius-cards);
            padding: 32px 24px;
            box-shadow: var(--shadow-card);
            text-align: left;
            position: relative;
        }

        .error-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--color-subtle-ash);
        }

        .error-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background-color: var(--color-ghost-gray);
            color: var(--color-rich-black);
            font-family: var(--font-geist-mono);
            font-size: var(--text-caption);
            line-height: var(--leading-caption);
            font-weight: 500;
            padding: 2px 10px;
            border-radius: var(--radius-badge);
            border: 1px solid var(--color-subtle-ash);
        }

        .error-badge-dot {
            width: 6px;
            height: 6px;
            border-radius: var(--radius-pill);
            background-color: var(--color-callout-red);
        }

        .system-tag {
            font-family: var(--font-geist-mono);
            font-size: 11px;
            color: var(--color-midtone-gray);
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .error-code {
            font-size: var(--text-display);
            font-weight: 600;
            line-height: var(--leading-display);
            letter-spacing: var(--tracking-display);
            color: var(--color-deep-black);
            margin-bottom: 8px;
        }

        .error-title {
            font-size: var(--text-heading);
            font-weight: 600;
            line-height: var(--leading-heading);
            letter-spacing: var(--tracking-heading);
            color: var(--color-deep-black);
            margin-bottom: 12px;
        }

        .error-message {
            font-size: var(--text-body);
            line-height: var(--leading-body);
            color: var(--color-midtone-gray);
            font-weight: 400;
            margin-bottom: 28px;
        }

        .error-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background-color: var(--color-deep-black);
            color: var(--color-canvas-white);
            font-family: var(--font-geist);
            font-size: var(--text-body);
            font-weight: 500;
            line-height: var(--leading-body);
            padding: 8px 24px;
            border-radius: var(--radius-buttons);
            text-decoration: none;
            border: 1px solid var(--color-deep-black);
            cursor: pointer;
            transition: opacity 0.15s ease;
        }

        .btn-primary:hover {
            opacity: 0.88;
        }

        .btn-ghost {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background-color: transparent;
            color: var(--color-rich-black);
            font-family: var(--font-geist);
            font-size: var(--text-body);
            font-weight: 500;
            line-height: var(--leading-body);
            padding: 8px 16px;
            border-radius: var(--radius-pill);
            text-decoration: none;
            border: 1px solid transparent;
            cursor: pointer;
            transition: background-color 0.15s ease;
        }

        .btn-ghost:hover {
            background-color: var(--color-ghost-gray);
        }

        .error-footer {
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-family: var(--font-geist-mono);
            font-size: var(--text-caption);
            color: var(--color-midtone-gray);
            padding: 0 4px;
        }

        .error-footer a {
            color: var(--color-midtone-gray);
            text-decoration: none;
            transition: color 0.15s ease;
        }

        .error-footer a:hover {
            color: var(--color-deep-black);
        }
    </style>
</head>
<body>
    <div class="error-wrapper">
        <div class="error-card">
            <div class="error-header">
                <div class="error-badge">
                    <span class="error-badge-dot"></span>
                    <span>HTTP @yield('code', '500')</span>
                </div>
                <div class="system-tag">
                    MLI · FLEET
                </div>
            </div>

            <div class="error-code">
                @yield('code', '500')
            </div>

            <h1 class="error-title">
                @yield('title', 'Error')
            </h1>

            <p class="error-message">
                @yield('message', 'An unexpected error occurred.')
            </p>

            <div class="error-actions">
                <a href="{{ url('/fleet') }}" class="btn-primary">
                    Dashboard
                </a>

                <a href="{{ url()->previous() !== url()->current() ? url()->previous() : url('/fleet') }}" class="btn-ghost" onclick="if (history.length > 1) { history.back(); return false; }">
                    Go Back
                </a>
            </div>
        </div>

        <div class="error-footer">
            <span>&copy; {{ date('Y') }} MLI · ALL RIGHTS RESERVED.</span>
            
        </div>
    </div>
</body>
</html>
