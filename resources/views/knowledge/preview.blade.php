<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Preview lectura</title>
<style>
    body{font-family:system-ui;background:#0b1220;color:#e5e7eb;margin:0;padding:24px}
    .wrap{max-width:980px;margin:0 auto}
    .card{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);border-radius:16px;overflow:hidden}
    .head{padding:18px 18px;background:linear-gradient(135deg,rgba(79,70,229,.5),rgba(124,58,237,.5))}
    .head h1{margin:0;font-size:20px}
    .meta{margin-top:8px;display:flex;gap:12px;flex-wrap:wrap;font-size:13px;opacity:.9}
    .body{padding:18px;line-height:1.7}
    .pill{display:inline-flex;align-items:center;gap:8px;padding:6px 10px;border-radius:999px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12)}
    .btn{border:0;padding:10px 14px;border-radius:12px;font-weight:800;cursor:pointer}
    .btn-green{background:#10b981;color:#062014}
    .btn-gray{background:#334155;color:#e5e7eb;text-decoration:none;display:inline-flex}
    .grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
    @media(max-width:720px){.grid{grid-template-columns:1fr}}
</style>
</head>
<body>
<div class="wrap">

<div class="card">
    <div class="head">
        <h1>{{ $module->title }}</h1>
        <div class="meta">
            <span class="pill">📘 Curso: {{ $module->course->course_id }} - {{ $module->course->name }}</span>
            <span class="pill">🎯 Tema: {{ $module->topic }}</span>
            <span class="pill">⏱️ {{ $module->estimated_minutes }} min</span>
            <span class="pill">✅ Activo: {{ $module->is_active ? 'Sí' : 'No' }}</span>
        </div>
    </div>

    <div class="body">
        @if($module->summary)
            <p><b>Resumen:</b> {{ $module->summary }}</p>
        @endif

        <h3>Conceptos clave</h3>
        <ul>
            @foreach(($module->key_concepts ?? []) as $k)
                <li>{{ $k }}</li>
            @endforeach
        </ul>

        <h3>Lectura</h3>
        <div style="white-space:pre-wrap">{{ $module->content }}</div>

        <h3>Actividades</h3>
        <div class="grid">
            @foreach(($module->activities ?? []) as $a)
                <div class="card" style="border-radius:14px">
                    <div class="body">
                        <b>{{ $a['type'] ?? 'actividad' }}</b><br>
                        {{ $a['question'] ?? '' }}
                        @if(($a['type'] ?? '') === 'mcq')
                            <ol type="A">
                                @foreach(($a['options'] ?? []) as $op)<li>{{ $op }}</li>@endforeach
                            </ol>
                        @endif
                        <div style="opacity:.85"><b>Respuesta:</b> {{ is_array($a['answer'] ?? null) ? json_encode($a['answer']) : ($a['answer'] ?? '') }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:14px">
            <form method="POST" action="{{ route('knowledge.toggle', $module) }}">
                @csrf @method('PATCH')
                <button class="btn btn-green" type="submit">
                    {{ $module->is_active ? 'Desactivar' : 'Activar' }}
                </button>
            </form>

            <a class="btn btn-gray" href="{{ route('profesor.dashboard') }}">← Volver</a>
        </div>
    </div>
</div>

</div>
</body>
</html>
