<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', 'Mirada Salvaje') · Gestión de alimentación</title>
    <style>
        :root { color-scheme: light; }
        * { box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            margin: 0;
            background: #f4f6f2;
            color: #1f2a1f;
        }
        header.topbar {
            background: #2f4d2f;
            color: #fff;
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: .5rem;
        }
        header.topbar a { color: #fff; text-decoration: none; }
        nav.modulo-nav { display: flex; gap: 1rem; font-size: .95rem; }
        nav.modulo-nav a { padding: .3rem .6rem; border-radius: 6px; }
        nav.modulo-nav a.activo, nav.modulo-nav a:hover { background: rgba(255,255,255,.15); }
        main { max-width: 960px; margin: 1.5rem auto; padding: 0 1rem 3rem; }
        .card { background: #fff; border: 1px solid #dde3d8; border-radius: 10px; padding: 1.25rem; margin-bottom: 1.25rem; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: .6rem .5rem; border-bottom: 1px solid #eee; font-size: .92rem; }
        th { color: #55624f; text-transform: uppercase; font-size: .75rem; letter-spacing: .04em; }
        .btn { display: inline-block; padding: .5rem .9rem; border-radius: 6px; background: #2f4d2f; color: #fff; text-decoration: none; font-size: .9rem; border: none; cursor: pointer; }
        .btn.secundario { background: #e7ece3; color: #2f4d2f; }
        .badge { display: inline-block; padding: .15rem .55rem; border-radius: 999px; font-size: .75rem; font-weight: 600; }
        .badge.ok { background: #dff2df; color: #276b27; }
        .badge.alerta { background: #fbe1e1; color: #9c2b2b; }
        .badge.pendiente { background: #fff3d6; color: #8a6414; }
        form.inline { display: inline; }
        label { display: block; font-size: .85rem; margin-bottom: .25rem; color: #4a564a; }
        input, select, textarea { width: 100%; padding: .5rem; border: 1px solid #cdd6c8; border-radius: 6px; margin-bottom: 1rem; font-size: .95rem; }
        .status { background: #eaf3e4; border: 1px solid #cfe3c4; color: #2f4d2f; padding: .75rem 1rem; border-radius: 8px; margin-bottom: 1rem; }
        .errores { background: #fbe9e9; border: 1px solid #f0c2c2; color: #8a2b2b; padding: .75rem 1rem; border-radius: 8px; margin-bottom: 1rem; }
        .toolbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: .5rem; }
        .muted { color: #778071; font-size: .85rem; }
        .btn-logout { background: none; border: none; color: inherit; text-decoration: underline; cursor: pointer; padding: 0; font: inherit; }
    </style>
</head>
<body>
    <header class="topbar">
        <div>
            <strong>🦁 Mirada Salvaje</strong>
            <span class="muted" style="color:#d9e3d3;">· Gestión de alimentación</span>
        </div>
        <nav class="modulo-nav">
            <a href="{{ route('dietas.index') }}" class="{{ request()->routeIs('dietas.*') ? 'activo' : '' }}">Dietas</a>
            <a href="{{ route('horarios-alimentacion.index') }}" class="{{ request()->routeIs('horarios-alimentacion.*') ? 'activo' : '' }}">Horarios</a>
            <a href="{{ route('inventario-alimentos.index') }}" class="{{ request()->routeIs('inventario-alimentos.*') ? 'activo' : '' }}">Inventario</a>
            <a href="{{ route('limpieza.index') }}" class="{{ request()->routeIs('limpieza.*') ? 'activo' : '' }}">Limpieza</a>
            <a href="{{ route('aplicaciones-clinicas.index') }}" class="{{ request()->routeIs('aplicaciones-clinicas.*') ? 'activo' : '' }}">Clínica</a>
                <a href="{{ route('entradas.publico') }}" class="{{ request()->routeIs('entradas.*') || request()->routeIs('admin.*') ? 'activo' : '' }}">Entradas</a>
        </nav>
        <div class="muted" style="color:#d9e3d3;">
            @auth
                {{ auth()->user()->name }} ({{ auth()->user()->rol?->nombre ?? 'sin rol' }})
                ·
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="btn-logout">Cerrar sesión</button>
                </form>
            @else
                sin sesión
                @if (app()->environment('local'))
                    · <a href="{{ route('dev-login', 'admin') }}">entrar como admin</a>
                    · <a href="{{ route('dev-login', 'cuidador') }}">como cuidador</a>
                @endif
            @endauth
        </div>
    </header>

    <main>
        @if (session('status'))
            <div class="status">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="errores">
                <strong>Revisa lo siguiente:</strong>
                <ul style="margin:.4rem 0 0; padding-left:1.2rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('contenido')
    </main>
</body>
</html>