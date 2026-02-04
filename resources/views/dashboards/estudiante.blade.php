<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">

        {{-- Header --}}
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold">Dashboard del Estudiante</h1>
            <p class="text-sm text-neutral-500">
                Revisa tus cursos, exámenes activos y tus últimos resultados.
            </p>
        </div>

        {{-- Cards de resumen --}}
        <div class="grid gap-4 md:grid-cols-4">

            <div class="rounded-xl border border-neutral-200 p-5 dark:border-neutral-700">
                <p class="text-sm text-neutral-500">Mis cursos</p>
                <p class="mt-2 text-3xl font-semibold">{{ $counts['my_courses'] ?? 0 }}</p>
            </div>

            <div class="rounded-xl border border-neutral-200 p-5 dark:border-neutral-700">
                <p class="text-sm text-neutral-500">Cursos disponibles</p>
                <p class="mt-2 text-3xl font-semibold">{{ $counts['available_courses'] ?? 0 }}</p>
            </div>

            <div class="rounded-xl border border-neutral-200 p-5 dark:border-neutral-700">
                <p class="text-sm text-neutral-500">Exámenes activos</p>
                <p class="mt-2 text-3xl font-semibold">
                    {{ $activeExamsCount ?? 0 }}
                </p>
            </div>

            <div class="rounded-xl border border-neutral-200 p-5 dark:border-neutral-700">
                <p class="text-sm text-neutral-500">Último resultado</p>
                @if(!empty($lastResult))
                    <p class="mt-2 text-3xl font-semibold">{{ $lastResult->percentage }}%</p>
                    <p class="mt-1 text-xs text-neutral-500">
                        {{ $lastResult->created_at?->format('d/m/Y H:i') }}
                    </p>
                @else
                    <p class="mt-2 text-sm text-neutral-500">Sin resultados aún</p>
                @endif
            </div>
        </div>

        {{-- Acceso rápido --}}
        <div class="rounded-xl border border-neutral-200 p-6 dark:border-neutral-700">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-semibold">Acceso rápido</h2>
                    <p class="text-sm text-neutral-500">
                        Acciones comunes para continuar tu trabajo.
                    </p>
                </div>

         <div class="flex flex-wrap gap-2">
                <a href="{{ route('courses.index') }}"
                class="rounded-lg bg-indigo-600 px-3 py-2 text-sm text-white hover:bg-indigo-700">
                    Ver cursos
                </a>

             <a href="{{ route('knowledge.index') }}"
                class="rounded-lg bg-slate-900 px-3 py-2 text-sm text-white hover:bg-slate-800">
                    📘 Lecturas de refuerzo
            </a>


            </div>


            </div>
        </div>

        {{-- Exámenes activos (acción principal) --}}
        <div class="rounded-xl border border-neutral-200 p-6 dark:border-neutral-700">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-semibold">Exámenes activos</h2>
                <span class="text-xs text-neutral-500">
                    Solo aparecen exámenes publicados (is_active=1).
                </span>
            </div>

            @php
                $coursesWithActiveExam = collect($myCourses ?? [])->filter(fn($c) => $c->activeExam);
            @endphp

            @if($coursesWithActiveExam->count())
                <div class="grid gap-4 md:grid-cols-3">
                    @foreach($coursesWithActiveExam as $course)
                        <div class="rounded-xl border border-neutral-200 p-4 dark:border-neutral-700">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <p class="text-xs text-neutral-500">{{ $course->course_id }}</p>
                                    <h3 class="font-semibold">{{ $course->name }}</h3>
                                    <p class="mt-1 text-sm text-neutral-500">
                                        Profesor: {{ $course->teacher->name ?? '—' }}
                                    </p>
                                </div>

                                <span class="rounded-full bg-emerald-600/10 px-2 py-1 text-xs font-semibold text-emerald-600">
                                    Activo
                                </span>
                            </div>

                            <div class="mt-3 rounded-lg bg-neutral-50 p-3 text-sm dark:bg-neutral-800/40">
                                <p class="font-medium">📝 {{ $course->activeExam->titulo }}</p>
                                <p class="mt-1 text-xs text-neutral-500">
                                    Máx: {{ $course->activeExam->score_max }} pts ·
                                    Preguntas: {{ $course->activeExam->questions_count }}
                                </p>
                            </div>

                            <a href="{{ route('courses.examen.take', $course) }}"
                               class="mt-4 inline-flex w-full items-center justify-center rounded-lg bg-emerald-600 px-3 py-2 text-sm font-medium text-white transition hover:bg-emerald-700">
                                Rendir examen
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-neutral-500">No tienes exámenes activos por ahora.</p>
            @endif
        </div>

        {{-- Mis cursos recientes --}}
        <div class="rounded-xl border border-neutral-200 p-6 dark:border-neutral-700">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-semibold">Mis cursos recientes</h2>
                <a href="{{ route('courses.index') }}" class="text-sm text-indigo-600 hover:text-indigo-500">
                    Ver todos →
                </a>
            </div>

            @if(!empty($myCourses) && $myCourses->count())
                <div class="grid gap-4 md:grid-cols-3">
                    @foreach($myCourses as $course)
                        <a href="{{ route('courses.show', $course) }}"
                           class="rounded-xl border border-neutral-200 p-4 transition hover:bg-neutral-50 dark:border-neutral-700 dark:hover:bg-neutral-800/40">
                            <div class="flex items-center justify-between gap-2">
                                <h3 class="font-semibold">{{ $course->name }}</h3>
                                <span class="text-xs text-neutral-500">{{ $course->course_id }}</span>
                            </div>

                            <p class="mt-2 line-clamp-2 text-sm text-neutral-500">
                                {{ $course->description ?? 'Sin descripción' }}
                            </p>

                            <div class="mt-3 flex items-center justify-between text-xs text-neutral-500">
                                <span>Profesor: {{ $course->teacher->name ?? '—' }}</span>
                                @if($course->activeExam)
                                    <span class="font-semibold text-emerald-600">Examen activo</span>
                                @else
                                    <span>Sin examen</span>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-neutral-500">Aún no estás inscrito en cursos.</p>
            @endif
        </div>

        {{-- Actividad reciente (últimos resultados) --}}
        <div class="rounded-xl border border-neutral-200 p-6 dark:border-neutral-700">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-semibold">Actividad reciente</h2>
                <span class="text-xs text-neutral-500">Últimos intentos</span>
            </div>

            @if(!empty($recentResults) && $recentResults->count())
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="text-xs text-neutral-500">
                            <tr class="border-b border-neutral-200 dark:border-neutral-700">
                                <th class="py-2 pr-4">Curso</th>
                                <th class="py-2 pr-4">Examen</th>
                                <th class="py-2 pr-4">Nota</th>
                                <th class="py-2 pr-4">Estado</th>
                                <th class="py-2 pr-4">Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentResults as $r)
                                <tr class="border-b border-neutral-200 dark:border-neutral-700">
                                    <td class="py-2 pr-4">
                                        {{ $r->exam?->course?->name ?? '—' }}
                                    </td>
                                    <td class="py-2 pr-4">
                                        {{ $r->exam?->titulo ?? '—' }}
                                    </td>
                                    <td class="py-2 pr-4 font-semibold">
                                        {{ $r->percentage }}%
                                    </td>
                                    <td class="py-2 pr-4">
                                        @php
                                            $badge = match($r->status) {
                                                'flagged' => 'bg-red-600/10 text-red-600',
                                                'completed' => 'bg-emerald-600/10 text-emerald-600',
                                                default => 'bg-slate-600/10 text-slate-600',
                                            };
                                        @endphp
                                        <span class="rounded-full px-2 py-1 text-xs font-semibold {{ $badge }}">
                                            {{ $r->status }}
                                        </span>
                                    </td>
                                    <td class="py-2 pr-4 text-xs text-neutral-500">
                                        {{ $r->created_at?->format('d/m/Y H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-sm text-neutral-500">
                    Todavía no has rendido exámenes.
                </p>
            @endif
        </div>

    </div>
</x-layouts.app>
