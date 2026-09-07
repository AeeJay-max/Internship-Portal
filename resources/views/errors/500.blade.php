<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>500 — Server Error | MoSRAC</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet"/>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(135deg, #064e3b 0%, #15803d 60%, #0f172a 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            color: white;
        }
        nav {
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.15);
            background: rgba(0,0,0,0.2);
        }
        nav img { width: 36px; height: 36px; object-fit: contain; }
        nav strong { color: white; font-size: 0.95rem; font-weight: 800; }
        nav span { color: #fde047; font-size: 0.7rem; display: block; font-weight: 600; }
        .container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .card {
            text-align: center;
            max-width: 480px;
            width: 100%;
        }
        .error-code {
            font-size: 6rem;
            font-weight: 900;
            line-height: 1;
            color: rgba(255,255,255,0.15);
            letter-spacing: -0.04em;
            margin-bottom: -0.5rem;
        }
        .logo-wrap {
            width: 84px;
            height: 84px;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.3);
        }
        .logo-wrap img { width: 56px; height: 56px; object-fit: contain; }
        .divider {
            width: 40px; height: 4px;
            background: #fde047;
            border-radius: 2px;
            margin: 0 auto 1.25rem;
        }
        h1 { font-size: 1.75rem; font-weight: 800; color: white; margin-bottom: 0.6rem; }
        p {
            color: rgba(255,255,255,0.75);
            font-size: 0.88rem;
            line-height: 1.6;
            margin-bottom: 2rem;
            max-width: 360px;
            margin-left: auto;
            margin-right: auto;
            font-weight: 500;
        }
        .actions { display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap; }
        .btn {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 0.75rem 1.5rem; border-radius: 12px;
            text-decoration: none; font-weight: 700; font-size: 0.85rem;
            transition: all 0.2s;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .btn-primary { background: white; color: #064e3b; }
        .btn-primary:hover { opacity: 0.95; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.2); }
        .btn-ghost {
            background: rgba(255,255,255,0.15); color: white;
            border: 1px solid rgba(255,255,255,0.25);
        }
        .btn-ghost:hover { background: rgba(255,255,255,0.25); transform: translateY(-2px); }
    </style>
</head>
<body>
<nav>
    <img src="/images/branding/lionfalcon.png" alt="Zimbabwe Emblem">
    <div>
        <strong>MoSRAC Internship Portal</strong>
        <span>Ministry of Sport, Recreation, Arts & Culture — Government of Zimbabwe</span>
    </div>
</nav>
<div class="container">
    <div class="card">
        <div class="error-code">500</div>
        <div class="logo-wrap">
            <img src="/images/branding/lionfalcon.png" alt="MoSRAC Emblem">
        </div>
        <div class="divider"></div>
        <h1>System Exception</h1>
        <p>The server encountered a temporary error while processing your request. Please try again in a few moments.</p>
        <div class="actions">
            <a href="javascript:history.back()" class="btn btn-primary">&larr; Return Back</a>
            <a href="javascript:location.reload()" class="btn btn-ghost">Try Again</a>
        </div>
    </div>
</div>
</body>
</html>

