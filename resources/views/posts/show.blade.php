<x-layouts::public :title="$post->title">

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

        {{-- Breadcrumbs --}}
        <nav class="mb-6 flex flex-wrap items-center gap-2 text-sm text-zinc-500 dark:text-zinc-400">
            <a
                href="{{ route('home') }}"
                class="transition hover:text-amber-600"
            >
                Inicio
            </a>

            <span>/</span>

            <a
                href="{{ route('home') }}"
                class="transition hover:text-amber-600"
            >
                Blog
            </a>

            @if ($post->category)
                <span>/</span>

                <span>
                    {{ $post->category->name }}
                </span>
            @endif

            <span>/</span>

            <span class="font-medium text-zinc-900 dark:text-white">
                {{ $post->title }}
            </span>
        </nav>

        <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_320px]">

            {{-- Columna principal --}}
            <main class="space-y-6">

                {{-- Imagen principal y título --}}
                <article
                    class="relative min-h-[340px] overflow-hidden rounded-2xl bg-zinc-900"
                >
                    @if ($post->image_path)
                        <img
                            src="{{ Storage::url($post->image_path) }}"
                            alt="{{ $post->title }}"
                            class="absolute inset-0 h-full w-full object-cover"
                        >
                    @endif

                    <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent"></div>

                    <div class="absolute inset-x-0 bottom-0 p-6 sm:p-10">
                        @if ($post->category)
                            <span
                                class="mb-4 inline-flex rounded-full bg-amber-500 px-4 py-2
                                       text-xs font-bold uppercase tracking-wide text-white"
                            >
                                {{ $post->category->name }}
                            </span>
                        @endif

                        <h1 class="max-w-4xl text-3xl font-bold leading-tight text-white sm:text-5xl">
                            {{ $post->title }}
                        </h1>
                    </div>
                </article>

                {{-- Resumen --}}
                @if ($post->summary)
                    <section
                        class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm
                               dark:border-zinc-700 dark:bg-zinc-900"
                    >
                        <div
                            class="border-l-4 border-amber-500 pl-5
                                   text-lg leading-8 text-zinc-600 dark:text-zinc-300"
                        >
                            {!! $post->summary !!}
                        </div>
                    </section>
                @endif

                {{-- Tags --}}
                @if ($post->tags->isNotEmpty())
                    <section
                        class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm
                               dark:border-zinc-700 dark:bg-zinc-900"
                    >
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="font-semibold text-zinc-700 dark:text-zinc-300">
                                Etiquetas:
                            </span>

                            @foreach ($post->tags as $tag)
                                <a{{--  --}}
                                    {{-- href="{{ route('posts.by-tag', $tag->slug) }}" --}}
                                    class="inline-flex items-center rounded-full border border-amber-500
                                           bg-amber-50 px-3 py-1 text-sm font-medium text-amber-700
                                           transition hover:bg-amber-500 hover:text-white
                                           dark:border-amber-400 dark:bg-amber-900/20
                                           dark:text-amber-300 dark:hover:bg-amber-500
                                           dark:hover:text-white"
                                >
                                    #{{ $tag->name }}
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif

                {{-- Autor, fecha y estado --}}
                <section
                    class="flex flex-col gap-4 rounded-2xl border border-zinc-200
                           bg-white p-5 shadow-sm dark:border-zinc-700
                           dark:bg-zinc-900 sm:flex-row sm:items-center
                           sm:justify-between"
                >
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-11 w-11 items-center justify-center rounded-full
                                   bg-amber-100 text-sm font-bold text-amber-700
                                   dark:bg-amber-900/30 dark:text-amber-400"
                        >
                            {{ strtoupper(substr($post->user?->name ?? 'A', 0, 1)) }}
                        </div>

                        <div>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">
                                Escrito por
                            </p>

                            <p class="font-semibold text-zinc-900 dark:text-white">
                                {{ $post->user?->name ?? 'Autor no disponible' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-4 text-sm text-zinc-500 dark:text-zinc-400">
                        <span>
                            {{ optional($post->published_at ?? $post->created_at)->format('d/m/Y') }}
                        </span>

                        <span
                            @class([
                                'inline-flex rounded-full px-3 py-1 text-xs font-semibold',
                                'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' => $post->is_published,
                                'bg-zinc-100 text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300' => ! $post->is_published,
                            ])
                        >
                            {{ $post->is_published ? 'Publicado' : 'Borrador' }}
                        </span>
                    </div>
                </section>

                {{-- Contenido --}}
                @if ($post->body)
                    <section
                        class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm
                               dark:border-zinc-700 dark:bg-zinc-900 sm:p-8"
                    >
                        <h2 class="mb-6 text-2xl font-bold text-zinc-900 dark:text-white">
                            Contenido del post
                        </h2>

                        <div
                            class="ql-editor prose max-w-none
                                   prose-headings:text-zinc-900
                                   prose-p:text-zinc-700
                                   prose-a:text-amber-600
                                   prose-strong:text-zinc-900
                                   prose-li:text-zinc-700
                                   dark:prose-invert
                                   dark:prose-headings:text-white
                                   dark:prose-p:text-zinc-300"
                        >
                            {!! $post->body !!}
                        </div>
                    </section>
                @endif

                {{-- Preguntas frecuentes --}}
                @if ($post->questions)
                    <section
                        class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm
                               dark:border-zinc-700 dark:bg-zinc-900 sm:p-8"
                    >
                        <h2 class="mb-6 text-2xl font-bold text-zinc-900 dark:text-white">
                            Preguntas frecuentes
                        </h2>

                        <div
                            id="questions"
                            class="ql-editor space-y-3 text-zinc-700 dark:text-zinc-300"
                        >
                            {!! $post->questions !!}
                        </div>
                    </section>
                @endif

                {{-- Fuentes --}}
                @if ($post->sources)
                    <section
                        class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm
                               dark:border-zinc-700 dark:bg-zinc-900 sm:p-8"
                    >
                        <h2 class="mb-6 text-2xl font-bold text-zinc-900 dark:text-white">
                            Fuentes técnicas consultadas
                        </h2>

                        <div
                            id="sources"
                            class="ql-editor prose max-w-none
                                   prose-p:text-zinc-700
                                   prose-li:text-zinc-700
                                   prose-a:text-amber-600
                                   dark:prose-invert
                                   dark:prose-p:text-zinc-300
                                   dark:prose-li:text-zinc-300"
                        >
                            {!! $post->sources !!}
                        </div>
                    </section>
                @endif

                {{-- Navegación --}}
                @if (($previousPost ?? null) || ($nextPost ?? null))
                    <section
                        class="grid gap-4 rounded-2xl border border-zinc-200
                               bg-white p-5 shadow-sm dark:border-zinc-700
                               dark:bg-zinc-900 sm:grid-cols-2"
                    >
                        <div>
                            @if ($previousPost ?? null)
                                <a
                                    href="{{ route('posts.show', $previousPost->slug) }}"
                                    class="group block"
                                >
                                    <span class="text-sm text-zinc-500 dark:text-zinc-400">
                                        ← Anterior
                                    </span>

                                    <p
                                        class="mt-1 font-semibold text-zinc-900 transition
                                               group-hover:text-amber-600 dark:text-white"
                                    >
                                        {{ $previousPost->title }}
                                    </p>
                                </a>
                            @endif
                        </div>

                        <div class="text-left sm:text-right">
                            @if ($nextPost ?? null)
                                <a
                                    href="{{ route('posts.show', $nextPost->slug) }}"
                                    class="group block"
                                >
                                    <span class="text-sm text-zinc-500 dark:text-zinc-400">
                                        Siguiente →
                                    </span>

                                    <p
                                        class="mt-1 font-semibold text-zinc-900 transition
                                               group-hover:text-amber-600 dark:text-white"
                                    >
                                        {{ $nextPost->title }}
                                    </p>
                                </a>
                            @endif
                        </div>
                    </section>
                @endif

            </main>

            {{-- Sidebar --}}
            <aside class="space-y-6">

                {{-- Detalles --}}
                <section
                    class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm
                           dark:border-zinc-700 dark:bg-zinc-900"
                >
                    <h2 class="mb-5 text-xl font-bold text-zinc-900 dark:text-white">
                        Detalles del post
                    </h2>

                    <dl class="space-y-5 text-sm">

                        <div class="grid grid-cols-[90px_1fr] gap-3">
                            <dt class="text-zinc-500 dark:text-zinc-400">
                                Slug
                            </dt>

                            <dd class="break-all font-medium text-zinc-900 dark:text-white">
                                {{ $post->slug }}
                            </dd>
                        </div>

                        <div class="grid grid-cols-[90px_1fr] gap-3">
                            <dt class="text-zinc-500 dark:text-zinc-400">
                                Categoría
                            </dt>

                            <dd class="font-medium text-zinc-900 dark:text-white">
                                {{ $post->category?->name ?? 'Sin categoría' }}
                            </dd>
                        </div>

                        @if ($post->tags->isNotEmpty())
                            <div class="grid grid-cols-[90px_1fr] gap-3">
                                <dt class="text-zinc-500 dark:text-zinc-400">
                                    Tags
                                </dt>

                                <dd class="flex flex-wrap gap-2">
                                    @foreach ($post->tags as $tag)
                                        <a
                                            {{-- href="{{ route('posts.by-tag', $tag->slug) }}" --}}
                                            class="rounded-full bg-zinc-100 px-2.5 py-1
                                                   text-xs font-medium text-zinc-700
                                                   transition hover:bg-amber-500 hover:text-white
                                                   dark:bg-zinc-800 dark:text-zinc-300"
                                        >
                                            {{ $tag->name }}
                                        </a>
                                    @endforeach
                                </dd>
                            </div>
                        @endif

                        <div class="grid grid-cols-[90px_1fr] gap-3">
                            <dt class="text-zinc-500 dark:text-zinc-400">
                                Autor
                            </dt>

                            <dd class="font-medium text-zinc-900 dark:text-white">
                                {{ $post->user?->name ?? 'Sin autor' }}
                            </dd>
                        </div>

                        <div class="grid grid-cols-[90px_1fr] gap-3">
                            <dt class="text-zinc-500 dark:text-zinc-400">
                                Estado
                            </dt>

                            <dd>
                                <span
                                    @class([
                                        'inline-flex rounded-full px-3 py-1 text-xs font-semibold',
                                        'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' => $post->is_published,
                                        'bg-zinc-100 text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300' => ! $post->is_published,
                                    ])
                                >
                                    {{ $post->is_published ? 'Publicado' : 'Borrador' }}
                                </span>
                            </dd>
                        </div>

                        <div class="grid grid-cols-[90px_1fr] gap-3">
                            <dt class="text-zinc-500 dark:text-zinc-400">
                                Publicado
                            </dt>

                            <dd class="font-medium text-zinc-900 dark:text-white">
                                {{ $post->published_at?->format('d/m/Y H:i') ?? 'Sin fecha' }}
                            </dd>
                        </div>

                        <div class="grid grid-cols-[90px_1fr] gap-3">
                            <dt class="text-zinc-500 dark:text-zinc-400">
                                Orden
                            </dt>

                            <dd class="font-medium text-zinc-900 dark:text-white">
                                {{ $post->order ?? '—' }}
                            </dd>
                        </div>

                    </dl>
                </section>

                {{-- Descarga --}}
                @if ($post->download_path)
                    <section
                        class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm
                               dark:border-zinc-700 dark:bg-zinc-900"
                    >
                        <h2 class="mb-3 text-xl font-bold text-zinc-900 dark:text-white">
                            Descargar recurso
                        </h2>

                        <p class="mb-5 text-sm leading-6 text-zinc-600 dark:text-zinc-300">
                            Accede al recurso relacionado con este contenido.
                        </p>

                        <a
                            href="{{ Storage::url($post->download_path) }}"
                            download
                            class="flex w-full items-center justify-center gap-2
                                   rounded-xl border border-amber-500 px-4 py-3
                                   font-semibold text-amber-600 transition
                                   hover:bg-amber-500 hover:text-white"
                        >
                            Descargar archivo
                        </a>
                    </section>
                @endif

                {{-- Compartir --}}
                <section
                    class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm
                           dark:border-zinc-700 dark:bg-zinc-900"
                >
                    <h2 class="mb-4 text-xl font-bold text-zinc-900 dark:text-white">
                        Compartir
                    </h2>

                    @php
                        $shareUrl = urlencode(request()->fullUrl());
                        $shareTitle = urlencode($post->title);
                    @endphp

                    <div class="flex flex-wrap gap-3">
                        <a
                            href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="flex h-11 w-11 items-center justify-center
                                   rounded-full bg-blue-600 font-bold text-white"
                        >
                            f
                        </a>

                        <a
                            href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="flex h-11 w-11 items-center justify-center
                                   rounded-full bg-sky-500 font-bold text-white"
                        >
                            X
                        </a>

                        <a
                            href="https://wa.me/?text={{ $shareTitle }}%20{{ $shareUrl }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="flex h-11 w-11 items-center justify-center
                                   rounded-full bg-green-500 font-bold text-white"
                        >
                            W
                        </a>

                        <button
                            type="button"
                            onclick="navigator.clipboard.writeText('{{ request()->fullUrl() }}')"
                            class="flex h-11 w-11 items-center justify-center
                                   rounded-full bg-zinc-500 font-bold text-white"
                        >
                            🔗
                        </button>
                    </div>
                </section>

                {{-- Posts relacionados --}}
                @if (isset($relatedPosts) && $relatedPosts->isNotEmpty())
                    <section
                        class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm
                               dark:border-zinc-700 dark:bg-zinc-900"
                    >
                        <h2 class="mb-5 text-xl font-bold text-zinc-900 dark:text-white">
                            Artículos relacionados
                        </h2>

                        <div class="space-y-5">
                            @foreach ($relatedPosts as $relatedPost)
                                <a
                                    href="{{ route('posts.show', $relatedPost->slug) }}"
                                    class="group flex gap-4"
                                >
                                    <div
                                        class="h-20 w-20 shrink-0 overflow-hidden rounded-xl
                                               bg-zinc-100 dark:bg-zinc-800"
                                    >
                                        @if ($relatedPost->image_path)
                                            <img
                                                src="{{ Storage::url($relatedPost->image_path) }}"
                                                alt="{{ $relatedPost->title }}"
                                                class="h-full w-full object-cover transition
                                                       duration-300 group-hover:scale-105"
                                            >
                                        @endif
                                    </div>

                                    <div>
                                        <h3
                                            class="line-clamp-2 font-semibold text-zinc-900
                                                   transition group-hover:text-amber-600
                                                   dark:text-white"
                                        >
                                            {{ $relatedPost->title }}
                                        </h3>

                                        <p class="mt-2 text-xs text-zinc-500 dark:text-zinc-400">
                                            {{ optional($relatedPost->published_at ?? $relatedPost->created_at)->format('d/m/Y') }}
                                        </p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif

            </aside>

        </div>

    </div>

</x-layouts::public>