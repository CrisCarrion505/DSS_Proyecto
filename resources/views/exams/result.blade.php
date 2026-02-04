<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Resultado del Examen</title>
  <style>
    body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial; background:#0b1220; color:#e5e7eb; margin:0; padding:24px; }
    .wrap { max-width: 900px; margin: 0 auto; }
    .card { background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); border-radius: 16px; overflow:hidden; }
    .head { padding: 22px; background: linear-gradient(135deg, rgba(79,70,229,.55), rgba(124,58,237,.55)); }
    .head h1 { margin:0; font-size: 22px; }
    .meta { margin-top: 8px; display:flex; gap:12px; flex-wrap:wrap; font-size: 14px; opacity:.95; }
    .body { padding: 22px; }
    .pill { display:inline-flex; align-items:center; gap:8px; padding: 6px 10px; border-radius: 999px; border:1px solid rgba(255,255,255,.15); background: rgba(0,0,0,.2); }
    .ok { color:#10b981; font-weight:800; }
    .bad { color:#ef4444; font-weight:800; }
    .grid { display:grid; grid-template-columns: repeat(auto-fit, minmax(220px,1fr)); gap:12px; margin-top:16px; }
    .box { padding:14px; border-radius:14px; border:1px solid rgba(255,255,255,0.12); background: rgba(0,0,0,0.25); }
    .k { font-size: 12px; opacity:.75; }
    .v { font-size: 22px; font-weight: 800; margin-top:6px; }
    .btn { display:inline-flex; align-items:center; justify-content:center; padding: 12px 16px; border-radius: 12px; font-weight: 800; text-decoration:none; border:1px solid rgba(255,255,255,.12); background:#334155; color:#e5e7eb; }
    .actions { margin-top: 18px; display:flex; gap:12px; flex-wrap:wrap; }
    pre { white-space: pre-wrap; word-break: break-word; background: rgba(0,0,0,.25); border:1px solid rgba(255,255,255,.12); padding:14px; border-radius: 14px; margin-top: 16px; }
  </style>
</head>
<body>
<div class="wrap">
  <div class="card">
    <div class="head">
      <h1>Resultado: {{ $exam->titulo ?? 'Examen' }}</h1>
      <div class="meta">
        <span class="pill">Curso: <b>{{ $course->course_id }}</b> — {{ $course->name }}</span>
        <span class="pill">Estado:
          @if($result->status === 'flagged')
            <span class="bad">FLAGGED</span>
          @else
            <span class="ok">COMPLETED</span>
          @endif
        </span>
      </div>
    </div>

    <div class="body">
      <div class="grid">
        <div class="box">
          <div class="k">Puntaje</div>
          <div class="v">{{ $result->score_obtained }} / {{ $result->score_max }}</div>
        </div>
        <div class="box">
          <div class="k">Porcentaje</div>
          <div class="v">{{ $result->percentage }}%</div>
        </div>
        <div class="box">
          <div class="k">Intento</div>
          <div class="v">1</div>
        </div>
      </div>

      <div class="actions">
        <a class="btn" href="{{ route('estudiante.dashboard') }}">← Volver al dashboard</a>
        <a class="btn" href="{{ route('courses.show', $course) }}">Ver curso</a>
      </div>

      <h3 style="margin-top:18px;">Métricas (proctoring)</h3>
      <pre>{{ json_encode($result->proctoring_metrics, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
    </div>
  </div>
</div>
</body>
</html>
