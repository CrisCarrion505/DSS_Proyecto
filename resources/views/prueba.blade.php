<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Rendir Examen - EduSecure</title>
    <style>
        body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial; background: #0b1220; color: #e5e7eb; margin: 0; padding: 24px; }
        .wrap { max-width: 980px; margin: 0 auto; }
        .card { background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); border-radius: 16px; overflow: hidden; }
        .head { padding: 22px 22px; background: linear-gradient(135deg, rgba(79,70,229,.55), rgba(124,58,237,.55)); }
        .head h1 { margin: 0; font-size: 22px; }
        .meta { margin-top: 8px; display: flex; gap: 14px; flex-wrap: wrap; font-size: 14px; opacity: 0.95; }
        .body { padding: 22px; }
        .q { padding: 18px; border-radius: 14px; border: 1px solid rgba(255,255,255,0.12); margin-bottom: 14px; background: rgba(0,0,0,0.25); }
        .q h3 { margin: 0 0 10px 0; font-size: 16px; }
        .opt { display: flex; gap: 10px; align-items: flex-start; padding: 10px 12px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.12); margin: 8px 0; cursor: pointer; }
        .opt input { margin-top: 3px; }
        .actions { display: flex; justify-content: space-between; gap: 12px; margin-top: 18px; flex-wrap: wrap; }
        .btn { border: 0; padding: 12px 16px; border-radius: 12px; font-weight: 700; cursor: pointer; }
        .btn-primary { background: #10b981; color: #062014; }
        .btn-secondary { background: #334155; color: #e5e7eb; text-decoration: none; display: inline-flex; align-items: center; }
        .warn { padding: 16px; border-radius: 14px; background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.35); }
        .grid { display: grid; grid-template-columns: 1.2fr 1fr; gap: 14px; }
        .panel { padding: 14px; border-radius: 14px; border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.04); }
        .panel h2 { margin: 0 0 10px 0; font-size: 14px; opacity: .9; }
        .small { font-size: 12px; opacity: .8; }
        iframe { width: 100%; height: 360px; border: 0; border-radius: 14px; background: rgba(0,0,0,0.25); }

        /* Barra de estado */
        .statusbar{
            display:flex; align-items:center; justify-content:space-between;
            gap:12px; padding:10px 12px; border-radius:14px;
            border:1px solid rgba(255,255,255,0.12);
            background: rgba(0,0,0,0.25);
            margin-bottom: 14px;
        }
        .pill{ padding:6px 10px; border-radius:999px; font-size:12px; font-weight:800; }
        .pill-ok{ background: rgba(16,185,129,.18); border:1px solid rgba(16,185,129,.35); }
        .pill-warn{ background: rgba(245,158,11,.16); border:1px solid rgba(245,158,11,.35); }
        .pill-bad{ background: rgba(239,68,68,.18); border:1px solid rgba(239,68,68,.35); }

        /* Modal reglas */
        .overlay{
            position:fixed; inset:0; background: rgba(0,0,0,.65);
            display:flex; align-items:center; justify-content:center;
            padding: 18px;
        }
        .modal{
            width:min(860px, 100%);
            background: rgba(12,18,32,.95);
            border:1px solid rgba(255,255,255,0.12);
            border-radius: 16px;
            overflow:hidden;
        }
        .modal-head{ padding:16px 18px; background: rgba(79,70,229,.30); }
        .modal-body{ padding: 18px; display:grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .modal ul{ margin: 0; padding-left: 18px; }
        .modal .actions{ padding: 0 18px 18px 18px; justify-content: flex-end; }
        .hidden{ display:none !important; }

        @media(max-width: 900px){
            .grid{ grid-template-columns: 1fr; }
            .modal-body{ grid-template-columns: 1fr; }
            iframe{ height: 320px; }
        }
    </style>
</head>
<body>
<div class="wrap">

@if(!isset($exam) || !$exam)
    <div class="card">
        <div class="head">
            <h1>Sin examen activo</h1>
            <div class="meta">
                <span>Curso: {{ $course->course_id }} — {{ $course->name }}</span>
            </div>
        </div>
        <div class="body">
            <div class="warn">No hay un examen publicado para este curso todavía.</div>
            <div class="actions">
                <a class="btn btn-secondary" href="{{ route('estudiante.dashboard') }}">← Volver</a>
            </div>
        </div>
    </div>
@else

    {{-- ✅ Modal de Reglas + Chequeo cámara (el iframe se ve aquí) --}}
    <div class="overlay" id="rulesOverlay">
        <div class="modal">
            <div class="modal-head">
                <h2 style="margin:0;font-size:16px;">Antes de iniciar</h2>
                <div class="small" style="margin-top:6px;">
                    Verifica que tu cámara funcione. El sistema registrará cambios de pestaña, pérdida de rostro y conducta anómala.
                </div>
            </div>

            <div class="modal-body">
                <div class="panel">
                    <h2>Reglas rápidas</h2>
                    <ul class="small" style="line-height:1.7;">
                        <li>No cambies de pestaña ni minimices.</li>
                        <li>Mantén tu rostro visible.</li>
                        <li>No uses copiar/pegar.</li>
                        <li>Si llegas a <b>5 advertencias</b>, el examen se cerrará y se registrará como inválido.</li>
                    </ul>
                </div>

                <div class="panel">
                    <h2>Chequeo de cámara</h2>
                    <div class="small">Si te ves en el video, estás listo.</div>
                    <iframe id="monitorFrame" src="{{ route('examen.show') }}"></iframe>
                </div>
            </div>

            <div class="actions">
                <button class="btn btn-secondary" type="button" onclick="location.href='{{ route('estudiante.dashboard') }}'">Cancelar</button>
                <button class="btn btn-primary" type="button" id="btnStartExam">Iniciar examen</button>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="head">
            <h1>{{ $exam->titulo }}</h1>
            <div class="meta">
                <span>Curso: {{ $course->course_id }} — {{ $course->name }}</span>
                <span>Preguntas: {{ is_array($exam->preguntas) ? count($exam->preguntas) : 0 }}</span>
                <span>Máx: {{ $exam->score_max }} pts</span>
            </div>
        </div>

        <div class="body">
            <div class="statusbar">
                <div class="small">
                    Estado: <span id="txtState" class="pill pill-ok">Listo</span>
                    <span style="margin-left:10px;">Advertencias: <b id="txtWarnings">0</b>/5</span>
                </div>
                <div class="small" id="txtHint">Inicia el examen para comenzar el monitoreo.</div>
            </div>

            <div class="grid">
                <div class="panel">
                    <h2>Preguntas</h2>

                    <form method="POST" action="{{ route('courses.examen.submit', $course) }}" id="examForm">
                        @csrf

                        <input type="hidden" name="exam_id" value="{{ $exam->id }}">
                        <input type="hidden" name="proctoring_metrics" id="proctoring_metrics" value="{}">

                        {{-- Para cierre automático --}}
                        <input type="hidden" name="terminated" id="terminated" value="0">
                        <input type="hidden" name="termination_reason" id="termination_reason" value="">

                        <div id="questionsWrap" class="hidden">
                            @foreach(($exam->preguntas ?? []) as $idx => $q)
                                <div class="q">
                                    <h3>
                                        {{ $idx + 1 }}. {{ $q['texto'] ?? '' }}
                                        <span style="opacity:.7">({{ $q['puntaje'] ?? 1 }} pts)</span>
                                    </h3>

                                    @foreach(($q['opciones'] ?? []) as $i => $opt)
                                        <label class="opt">
                                            <input type="radio" name="answers[{{ $idx }}]" value="{{ $i }}" required>
                                            <div><b>{{ chr(65 + $i) }}.</b> {{ $opt }}</div>
                                        </label>
                                    @endforeach
                                </div>
                            @endforeach

                            <div class="actions">
                                <a class="btn btn-secondary" href="{{ route('estudiante.dashboard') }}">← Volver</a>
                                <button type="submit" class="btn btn-primary" id="btnSubmit">Enviar examen</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="panel">
                    <h2>Monitoreo</h2>
                    <div class="small">
                        No se muestran métricas detalladas. Solo advertencias cuando se detecta un evento.
                    </div>

                    {{-- El monitor ya está arriba en el overlay. Aquí lo dejamos oculto o puedes volverlo a mostrar --}}
                    <div class="small" style="margin-top:10px; opacity:.85;">
                        * El monitoreo corre en segundo plano mientras respondes.
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
/**
 * =========================
 * CONFIGURACIÓN DE UMBRALES
 * =========================
 * Ajusta aquí sin tocar el resto.
 */
const LIMIT_WARNINGS = 5;

// Umbrales (los que tú decías)
const THRESHOLDS = {
  tab_hidden_warn: 1,        // 1 cambio pestaña = 1 warning
  blur_warn: 2,              // 2 blur = warning
  rostro_perdido_consec: 6,  // 6 frames seguidos sin rostro => warning
  desvio_total_warn: 2000,   // +2000 desviaciones => warning (lo que dijiste)
};

// Para no spamear warnings del mismo tipo
const COOLDOWN_MS = {
  tab: 8000,
  blur: 8000,
  rostro: 8000,
  desvio: 12000,
  copy: 12000,
  paste: 12000,
  contextmenu: 12000,
};

let examStarted = false;
let warningCount = 0;
let lastWarnAt = {}; // por tipo
let rostroPerdidoConsec = 0;

// Estado proctoring (reporte)
const proctoring = {
  ui: {
    tab_hidden_count: 0,
    blur_count: 0,
    copy_count: 0,
    paste_count: 0,
    contextmenu_count: 0,
    started_at: null,
    duration_sec: 0
  },
  last_metrics: null,
  metrics_history: [],
  warnings: [],        // {t,type,message,payload}
  terminated: false,   // true si se cerró
  termination_reason: null
};

const $warnings = document.getElementById('txtWarnings');
const $state = document.getElementById('txtState');
const $hint = document.getElementById('txtHint');

function setState(text, kind){
  $state.textContent = text;
  $state.className = 'pill ' + (kind === 'bad' ? 'pill-bad' : kind === 'warn' ? 'pill-warn' : 'pill-ok');
}

function canWarn(type){
  const now = Date.now();
  const cd = COOLDOWN_MS[type] ?? 8000;
  if (!lastWarnAt[type] || (now - lastWarnAt[type]) > cd){
    lastWarnAt[type] = now;
    return true;
  }
  return false;
}

function addWarning(type, message, payload = null){
  if (!examStarted) return;
  if (!canWarn(type)) return;

  warningCount++;
  $warnings.textContent = warningCount;

  proctoring.warnings.push({
    t: Date.now(),
    type,
    message,
    payload
  });

  setState('Advertencia', 'warn');
  $hint.textContent = message;

  // UI simple (alerta)
  alert(`⚠️ Advertencia (${warningCount}/${LIMIT_WARNINGS})\n${message}`);

  if (warningCount >= LIMIT_WARNINGS){
    terminateExam(`Se superó el límite de ${LIMIT_WARNINGS} advertencias`);
  }
}

function terminateExam(reason){
  if (proctoring.terminated) return;

  proctoring.terminated = true;
  proctoring.termination_reason = reason;

  setState('Examen cerrado', 'bad');
  $hint.textContent = reason;

  // Aviso final
  alert(`⛔ Examen cerrado\n${reason}\nTu intento será registrado.`);

  // Marcar hidden fields
  document.getElementById('terminated').value = "1";
  document.getElementById('termination_reason').value = reason;

  // Pedir al iframe que detenga WS
  const frame = document.getElementById('monitorFrame');
  if (frame && frame.contentWindow) {
    frame.contentWindow.postMessage({ type: "proctoring_command", action: "stop" }, window.location.origin);
  }

  // Auto-enviar (se guardan métricas + respuestas actuales si existieran)
  // Si quieres forzar nota 0, lo manejas en el Controller cuando terminated=1
  document.getElementById('btnSubmit')?.click();
}

/**
 * =========================
 * RECIBIR MÉTRICAS DEL IFRAME
 * =========================
 * El iframe (examen.blade monitor) debe mandar:
 * postMessage({type:"proctoring_metrics", payload:{...}}, origin)
 */
window.addEventListener("message", (event) => {
  if (event.origin !== window.location.origin) return;
  if (!event.data || event.data.type !== "proctoring_metrics") return;

  const m = event.data.payload;
  proctoring.last_metrics = m;

  // Guardar historial limitado
  proctoring.metrics_history.push({ t: Date.now(), ...m });
  if (proctoring.metrics_history.length > 250) proctoring.metrics_history.shift();

  // Lógica de alertas por monitor
  if (m.status === 'rostro_perdido'){
    rostroPerdidoConsec++;
  } else {
    rostroPerdidoConsec = 0;
  }

  if (rostroPerdidoConsec >= THRESHOLDS.rostro_perdido_consec){
    addWarning('rostro', 'No se detecta tu rostro. Mantente frente a la cámara.', { rostroPerdidoConsec });
    rostroPerdidoConsec = 0; // reset después de warning
  }

  // Desvíos (acumulado del servidor python)
  const desvios = m.desvios_mirada ?? 0;
  if (desvios >= THRESHOLDS.desvio_total_warn){
    addWarning('desvio', 'Se detectó un nivel anormal de desvío de mirada.', { desvios });
    // NO reseteamos el contador del server, solo evitamos spam con cooldown
  }
});

/**
 * =========================
 * EVENTOS UI (pestaña / blur / copy paste)
 * =========================
 */
document.addEventListener("visibilitychange", () => {
  if (!examStarted) return;
  if (document.hidden){
    proctoring.ui.tab_hidden_count++;
    if (proctoring.ui.tab_hidden_count >= THRESHOLDS.tab_hidden_warn){
      addWarning('tab', 'No cambies de pestaña durante el examen.', { tab_hidden_count: proctoring.ui.tab_hidden_count });
    }
  }
});

window.addEventListener("blur", () => {
  if (!examStarted) return;
  proctoring.ui.blur_count++;
  if (proctoring.ui.blur_count >= THRESHOLDS.blur_warn){
    addWarning('blur', 'Evita cambiar de ventana (Alt+Tab) durante el examen.', { blur_count: proctoring.ui.blur_count });
  }
});

document.addEventListener("copy", () => {
  if (!examStarted) return;
  proctoring.ui.copy_count++;
  addWarning('copy', 'Copiar está restringido durante el examen.', { copy_count: proctoring.ui.copy_count });
});

document.addEventListener("paste", () => {
  if (!examStarted) return;
  proctoring.ui.paste_count++;
  addWarning('paste', 'Pegar está restringido durante el examen.', { paste_count: proctoring.ui.paste_count });
});

document.addEventListener("contextmenu", (e) => {
  if (!examStarted) return;
  proctoring.ui.contextmenu_count++;
  addWarning('contextmenu', 'El menú contextual está restringido durante el examen.', { contextmenu_count: proctoring.ui.contextmenu_count });
  e.preventDefault();
});

/**
 * =========================
 * INICIAR EXAMEN (desde modal)
 * =========================
 */
document.getElementById('btnStartExam').addEventListener('click', () => {
  examStarted = true;
  proctoring.ui.started_at = Date.now();
  setState('Monitoreando', 'ok');
  $hint.textContent = 'Responde con calma. Se registrarán eventos anómalos.';

  // Mostrar preguntas
  document.getElementById('questionsWrap').classList.remove('hidden');

  // Cerrar overlay
  document.getElementById('rulesOverlay').classList.add('hidden');

  // Ordenar al iframe que inicie WS (si quieres control total)
  const frame = document.getElementById('monitorFrame');
  if (frame && frame.contentWindow) {
    frame.contentWindow.postMessage({ type: "proctoring_command", action: "start" }, window.location.origin);
  }
});

/**
 * =========================
 * SERIALIZAR MÉTRICAS AL ENVIAR
 * =========================
 */
document.getElementById('examForm').addEventListener('submit', function (e) {
  // duración
  if (proctoring.ui.started_at){
    proctoring.ui.duration_sec = Math.round((Date.now() - proctoring.ui.started_at) / 1000);
  }

  const payload = {
    ...proctoring,
    warning_count: warningCount,
    thresholds: THRESHOLDS
  };

  document.getElementById('proctoring_metrics').value = JSON.stringify(payload);
});
</script>

@endif
</div>
</body>
</html>
