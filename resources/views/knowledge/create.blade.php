<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Lectura de Refuerzo</title>
    <style>
        body{font-family:system-ui;background:linear-gradient(135deg,#667eea,#764ba2);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}
        .card{max-width:560px;width:100%;background:rgba(255,255,255,.95);border-radius:18px;overflow:hidden;box-shadow:0 20px 40px rgba(0,0,0,.12)}
        .head{background:linear-gradient(135deg,#4f46e5,#7c3aed);color:#fff;padding:28px}
        .head h1{margin:0;font-size:22px}
        .head p{margin:8px 0 0;opacity:.9}
        .body{padding:28px}
        .group{margin-bottom:16px}
        label{display:block;font-weight:700;color:#111827;margin-bottom:6px}
        select,input{width:100%;padding:12px 14px;border:2px solid #e5e7eb;border-radius:12px;background:#f8fafc}
        select:focus,input:focus{outline:none;border-color:#4f46e5;box-shadow:0 0 0 3px rgba(79,70,229,.12);background:#fff}
        .btn{width:100%;border:0;padding:14px 16px;border-radius:12px;font-weight:800;cursor:pointer;background:linear-gradient(135deg,#10b981,#059669);color:#fff}
        .error{background:#ef4444;color:#fff;padding:12px;border-radius:12px;margin-bottom:16px}
        .row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
        @media(max-width:520px){.row{grid-template-columns:1fr}}
    </style>
</head>
<body>
<div class="card">
    <div class="head">
        <h1>📚 Crear lectura de refuerzo</h1>
        <p>Generada con IA y basada en un modelo pedagógico real</p>
    </div>

    <div class="body">
        @if($errors->any())
            <div class="error">
                <ul style="margin:0;padding-left:18px">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('knowledge.store') }}">
            @csrf

            <div class="group">
                <label>Curso</label>
                <select name="course_id" required>
                    <option value="">-- Selecciona un curso --</option>
                    @foreach($courses as $c)
                        <option value="{{ $c->id }}" @selected(old('course_id')==$c->id)>
                            {{ $c->course_id }} - {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="row">
                <div class="group">
                    <label>Modelo pedagógico</label>
                    <select name="pedagogy_model" required>
                        <option value="arcs_keller" @selected(old('pedagogy_model')=='arcs_keller')>ARCS (Keller)</option>
                        <option value="gagne_9_events" @selected(old('pedagogy_model')=='gagne_9_events')>Gagné (9 eventos)</option>
                        <option value="blooms_taxonomy" @selected(old('pedagogy_model')=='blooms_taxonomy')>Bloom (Taxonomía)</option>
                        <option value="constructivism_scaffold" @selected(old('pedagogy_model')=='constructivism_scaffold')>Constructivismo + andamiaje</option>
                        <option value="spaced_retrieval" @selected(old('pedagogy_model')=='spaced_retrieval')>Recuperación + repetición espaciada</option>
                    </select>
                </div>

                <div class="group">
                    <label>Duración estimada (min)</label>
                    <input type="number" name="estimated_minutes" value="{{ old('estimated_minutes', 10) }}" min="5" max="60">
                </div>
            </div>

            <div class="group">
                <label>Tema</label>
                <input type="text" name="topic" value="{{ old('topic') }}" required placeholder="Ej: OAuth 2.0, Álgebra lineal, Programación web...">
            </div>

            <button class="btn" type="submit">✨ Generar lectura</button>
        </form>
    </div>
</div>
</body>
</html>
