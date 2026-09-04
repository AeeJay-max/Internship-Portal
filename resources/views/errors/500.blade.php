<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>500 — Server Error | MOSRAC</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet"/>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(135deg, #1a0505 0%, #7f1d1d 60%, #dc2626 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        nav {
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        nav img { width: 38px; height: 38px; object-fit: contain; }
        nav strong { color: white; font-size: 0.95rem; font-weight: 700; }
        nav span { color: #fca5a5; font-size: 0.68rem; display: block; font-weight: 400; }
        .container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .card {
            text-align: center;
            max-width: 460px;
            width: 100%;
        }
        .error-code {
            font-size: 5.5rem;
            font-weight: 800;
            line-height: 1;
            color: rgba(255,255,255,0.1);
            letter-spacing: -0.03em;
            margin-bottom: -0.5rem;
        }
        .logo-wrap {
            width: 80px;
            height: 80px;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
        }
        .logo-wrap img { width: 52px; height: 52px; object-fit: contain; }
        .divider {
            width: 36px; height: 3px;
            background: rgba(255,255,255,0.25);
            border-radius: 2px;
            margin: 0 auto 1.25rem;
        }
        h1 { font-size: 1.6rem; font-weight: 700; color: white; margin-bottom: 0.6rem; }
        p {
            color: rgba(255,255,255,0.6);
            font-size: 0.9rem;
            line-height: 1.65;
            margin-bottom: 2rem;
            max-width: 340px;
            margin-left: auto;
            margin-right: auto;
        }
        .actions { display: flex; gap: 0.65rem; justify-content: center; flex-wrap: wrap; }
        .btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 0.7rem 1.4rem; border-radius: 12px;
            text-decoration: none; font-weight: 600; font-size: 0.85rem;
            transition: all 0.2s;
        }
        .btn-primary { background: white; color: #7f1d1d; }
        .btn-primary:hover { opacity: 0.92; transform: translateY(-1px); }
        .btn-ghost {
            background: rgba(255,255,255,0.12); color: white;
            border: 1px solid rgba(255,255,255,0.2);
        }
        .btn-ghost:hover { background: rgba(255,255,255,0.2); transform: translateY(-1px); }
    </style>
</head>
<body>
<nav>
    <img src="/images/branding/MOSRAC.svg" alt="MOSRAC Logo">
    <div>
        <strong>MOSRAC</strong>
        <span>National Internship Portal of Armenia</span>
    </div>
</nav>
<div class="container">
    <div class="card">
        <div class="error-code">500</div>
        <div class="logo-wrap">
            <img src="/images/branding/MOSRAC.svg" alt="MOSRAC">
        </div>
        <div class="divider"></div>
        <h1>Something Went Wrong</h1>
        <p>Our server encountered an unexpected error. Please try again in a few moments or go back.</p>
        <div class="actions">
            <a href="javascript:history.back()" class="btn btn-primary">← Go Back</a>
            <a href="javascript:location.reload()" class="btn btn-ghost">Try Again</a>
        </div>
    </div>
</div>
</body>
</html>
