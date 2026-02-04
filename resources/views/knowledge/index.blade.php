<x-layouts.app :title="__('Lecturas de refuerzo')">
    <div class="flex flex-col gap-6">

        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold">📘 Lecturas de refuerzo</h1>
                <p class="mt-1 text-sm text-neutral-500">
                    Lee el material antes del examen. Tu concentración puede ser monitoreada durante la lectura.
                </p>
            </div>

            <a href="{{ route('estudiante.dashboard') }}"
               class="rounded-lg border border-neutral-200 px-3 py-2 text-sm hover:bg-neutral-50 dark:border-neutral-700 dark:hover:bg-neutral-800/40">
                ← Volver
            </a>
        </div>

        @if(isset($modules) && $modules->count())
            <div class="grid gap-4 md:grid-cols-3">
                @foreach($modules as $module)
                    <div class="rounded-xl border border-neutral-200 p-4 dark:border-neutral-700">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs text-neutral-500">
                                    Curso: <span class="font-medium">{{ $module->course->name ?? '—' }}</span>
                                </p>
                                <h3 class="mt-1 font-semibold">
                                    {{ $module->title ?? 'Lectura de refuerzo' }}
                                </h3>
                            </div>

                            <span class="rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300">
                                Activo
                            </span>
                        </div>

                        <p class="mt-2 line-clamp-3 text-sm text-neutral-500">
                            {{ $module->topic ?? 'Sin tema' }}
                        </p>

                        <div class="mt-4 flex items-center justify-between">
                            <span class="text-xs text-neutral-400">
                                {{ optional($module->created_at)->format('d/m/Y') }}
                            </span>

                            <a href="{{ route('knowledge.read', $module) }}"
                               class="rounded-lg bg-slate-900 px-3 py-2 text-sm text-white hover:bg-slate-800">
                                Leer →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="rounded-xl border border-neutral-200 p-6 text-sm text-neutral-500 dark:border-neutral-700">
                No hay lecturas activas disponibles todavía.
            </div>
        @endif

    </div>
</x-layouts.app>
