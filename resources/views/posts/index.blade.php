<x-layouts::public :title="__('Inicio')">

    <div class="bg-zinc-50 dark:bg-zinc-950">

        {{-- Hero --}}
        <section class="border-b border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-900">
            <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">

                <div class="grid items-center gap-10 lg:grid-cols-2">

                    <div>
                        <span
                            class="mb-5 inline-flex rounded-full bg-amber-100 px-4 py-2
                                   text-sm font-semibold text-amber-700
                                   dark:bg-amber-900/30 dark:text-amber-300"
                        >
                            Blog profesional
                        </span>

                        <h1
                            class="text-4xl font-bold tracking-tight text-zinc-900
                                   dark:text-white sm:text-5xl lg:text-6xl"
                        >
                            Información, consejos y novedades
                        </h1>

                        <p
                            class="mt-6 max-w-xl text-lg leading-8
                                   text-zinc-600 dark:text-zinc-300"
                        >
                            Descubre artículos, guías, recomendaciones y recursos
                            preparados para ayudarte a tomar mejores decisiones.
                        </p>

                        <div class="mt-8 flex flex-wrap gap-4">
                            <a
                                href="#ultimos-posts"
                                class="rounded-xl bg-amber-500 px-6 py-3
                                       font-semibold text-white transition
                                       hover:bg-amber-600"
                            >
                                Ver artículos
                            </a>

                            @if (Route::has('home'))
                                <a
                                    href="{{ route('home') }}"
                                    class="rounded-xl border border-zinc-300 px-6 py-3
                                           font-semibold text-zinc-700 transition
                                           hover:border-amber-500 hover:text-amber-600
                                           dark:border-zinc-700 dark:text-zinc-200"
                                >
                                    Explorar el blog
                                </a>
                            @endif
                        </div>
                    </div>

                    @if ($featuredPost ?? null)
                        <a
                            href="{{ route('posts.show', $featuredPost->slug) }}"
                            class="group relative min-h-[380px] overflow-hidden rounded-3xl bg-zinc-900"
                        >
                            @if ($featuredPost->image_path)
                                <img
                                    src="{{ Storage::url($featuredPost->image_path) }}"
                                    alt="{{ $featuredPost->title }}"
                                    class="absolute inset-0 h-full w-full object-cover
                                           transition duration-500 group-hover:scale-105"
                                >
                            @endif

                            <div
                                class="absolute inset-0 bg-gradient-to-t
                                       from-black/90 via-black/30 to-transparent"
                            ></div>

                            <div class="absolute inset-x-0 bottom-0 p-6 sm:p-8">

                                @if ($featuredPost->category)
                                    <span
                                        class="mb-4 inline-flex rounded-full
                                               bg-amber-500 px-3 py-1
                                               text-xs font-bold uppercase
                                               tracking-wide text-white"
                                    >
                                        {{ $featuredPost->category->name }}
                                    </span>
                                @endif

                                <h2 class="text-2xl font-bold text-white sm:text-3xl">
                                    {{ $featuredPost->title }}
                                </h2>

                                <p class="mt-3 line-clamp-2 text-sm leading-6 text-zinc-200">
                                    {{ Str::limit(strip_tags($featuredPost->summary), 160) }}
                                </p>

                                <div class="mt-5 flex items-center gap-3 text-sm text-zinc-300">
                                    <span>
                                        {{ $featuredPost->user?->name ?? 'Autor' }}
                                    </span>

                                    <span>•</span>

                                    <span>
                                        {{ optional(
                                            $featuredPost->published_at
                                            ?? $featuredPost->created_at
                                        )->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endif

                </div>

            </div>
        </section>

        {{-- Categorías --}}
        @if (isset($categories) && $categories->isNotEmpty())
            <section class="py-10">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

                    <div class="mb-6 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-wide text-amber-600">
                                Explorar
                            </p>

                            <h2 class="mt-1 text-2xl font-bold text-zinc-900 dark:text-white">
                                Categorías
                            </h2>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        @foreach ($categories as $category)
                            <a
                                {{-- href="{{ route('posts.by-category', $category->slug) }}" --}}
                                class="group inline-flex items-center gap-2 rounded-full
                                       border border-zinc-200 bg-white px-4 py-2
                                       text-sm font-medium text-zinc-700 shadow-sm
                                       transition hover:border-amber-500
                                       hover:bg-amber-500 hover:text-white
                                       dark:border-zinc-700 dark:bg-zinc-900
                                       dark:text-zinc-300"
                            >
                                <span>
                                    {{ $category->name }}
                                </span>

                                <span
                                    class="rounded-full bg-zinc-100 px-2 py-0.5
                                           text-xs text-zinc-500
                                           group-hover:bg-white/20
                                           group-hover:text-white
                                           dark:bg-zinc-800 dark:text-zinc-400"
                                >
                                    {{ $category->posts_count }}
                                </span>
                            </a>
                        @endforeach
                    </div>

                </div>
            </section>
        @endif

        {{-- Últimos posts --}}
        <section id="ultimos-posts" class="py-10">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

                <div
                    class="mb-8 flex flex-col gap-4
                           sm:flex-row sm:items-end sm:justify-between"
                >
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wide text-amber-600">
                            Novedades
                        </p>

                        <h2 class="mt-1 text-3xl font-bold text-zinc-900 dark:text-white">
                            Últimos artículos
                        </h2>
                    </div>

                    @if (Route::has('home'))
                        <a
                            href="{{ route('home') }}"
                            class="text-sm font-semibold text-amber-600
                                   transition hover:text-amber-700"
                        >
                            Ver todos los artículos →
                        </a>
                    @endif
                </div>

                @if (isset($posts) && $posts->isNotEmpty())

                    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">

                        @foreach ($posts as $post)
                            <article
                                class="group flex flex-col overflow-hidden
                                       rounded-2xl border border-zinc-200
                                       bg-white shadow-sm transition
                                       hover:-translate-y-1 hover:shadow-lg
                                       dark:border-zinc-800 dark:bg-zinc-900"
                            >

                                <a
                                    href="{{ route('posts.show', $post->slug) }}"
                                    class="relative block aspect-[16/10]
                                           overflow-hidden bg-zinc-100
                                           dark:bg-zinc-800"
                                >
                                    @if ($post->image_path)
                                        <img
                                            src="{{ Storage::url($post->image_path) }}"
                                            alt="{{ $post->title }}"
                                            class="h-full w-full object-cover
                                                   transition duration-500
                                                   group-hover:scale-105"
                                        >
                                    @endif

                                    @if ($post->category)
                                        <span
                                            class="absolute left-4 top-4 rounded-full
                                                   bg-amber-500 px-3 py-1
                                                   text-xs font-bold text-white"
                                        >
                                            {{ $post->category->name }}
                                        </span>
                                    @endif
                                </a>

                                <div class="flex flex-1 flex-col p-5">

                                    <div
                                        class="mb-3 flex flex-wrap items-center gap-2
                                               text-xs text-zinc-500
                                               dark:text-zinc-400"
                                    >
                                        <span>
                                            {{ optional(
                                                $post->published_at
                                                ?? $post->created_at
                                            )->format('d/m/Y') }}
                                        </span>

                                        <span>•</span>

                                        <span>
                                            {{ $post->user?->name ?? 'Autor' }}
                                        </span>
                                    </div>

                                    <h3
                                        class="text-xl font-bold leading-snug
                                               text-zinc-900 transition
                                               group-hover:text-amber-600
                                               dark:text-white"
                                    >
                                        <a href="{{ route('posts.show', $post->slug) }}">
                                            {{ $post->title }}
                                        </a>
                                    </h3>

                                    @if ($post->summary)
                                        <p
                                            class="mt-3 line-clamp-3 text-sm leading-6
                                                   text-zinc-600 dark:text-zinc-300"
                                        >
                                            {{ Str::limit(
                                                strip_tags($post->summary),
                                                150
                                            ) }}
                                        </p>
                                    @endif

                                    @if ($post->tags->isNotEmpty())
                                        <div class="mt-4 flex flex-wrap gap-2">
                                            @foreach ($post->tags->take(3) as $tag)
                                                <a
                                                    {{-- href="{{ route('posts.by-tag', $tag->slug) }}" --}}
                                                    class="rounded-full bg-zinc-100
                                                           px-2.5 py-1 text-xs
                                                           font-medium text-zinc-600
                                                           transition
                                                           hover:bg-amber-500
                                                           hover:text-white
                                                           dark:bg-zinc-800
                                                           dark:text-zinc-300"
                                                >
                                                    #{{ $tag->name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="mt-auto pt-5">
                                        <a
                                            href="{{ route('posts.show', $post->slug) }}"
                                            class="inline-flex items-center gap-2
                                                   text-sm font-semibold
                                                   text-amber-600 transition
                                                   hover:gap-3"
                                        >
                                            Leer artículo
                                            <span>→</span>
                                        </a>
                                    </div>

                                </div>

                            </article>
                        @endforeach

                    </div>

                @else
                    <div
                        class="rounded-2xl border border-dashed border-zinc-300
                               bg-white p-12 text-center
                               dark:border-zinc-700 dark:bg-zinc-900"
                    >
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">
                            Todavía no hay artículos publicados
                        </h3>

                        <p class="mt-2 text-zinc-500 dark:text-zinc-400">
                            Los nuevos contenidos aparecerán aquí.
                        </p>
                    </div>
                @endif

            </div>
        </section>

        {{-- Tags populares --}}
        @if (isset($tags) && $tags->isNotEmpty())
            <section class="py-10">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

                    <div
                        class="rounded-3xl bg-zinc-900 p-8
                               text-white dark:bg-zinc-900 sm:p-10"
                    >
                        <div class="grid gap-8 lg:grid-cols-[280px_1fr]">

                            <div>
                                <p
                                    class="text-sm font-semibold uppercase
                                           tracking-wide text-amber-400"
                                >
                                    Temas
                                </p>

                                <h2 class="mt-2 text-3xl font-bold">
                                    Tags populares
                                </h2>

                                <p class="mt-3 text-sm leading-6 text-zinc-300">
                                    Encuentra contenido relacionado con los
                                    temas que más te interesan.
                                </p>
                            </div>

                            <div class="flex flex-wrap content-start gap-3">
                                @foreach ($tags as $tag)
                                    <a
                                        {{-- href="{{ route('posts.by-tag', $tag->slug) }}" --}}
                                        class="inline-flex items-center gap-2 rounded-full
                                               border border-zinc-700 bg-zinc-800
                                               px-4 py-2 text-sm font-medium
                                               text-zinc-200 transition
                                               hover:border-amber-500
                                               hover:bg-amber-500
                                               hover:text-white"
                                    >
                                        <span>#{{ $tag->name }}</span>

                                        <span class="text-xs text-zinc-400">
                                            {{ $tag->posts_count }}
                                        </span>
                                    </a>
                                @endforeach
                            </div>

                        </div>
                    </div>

                </div>
            </section>
        @endif

        {{-- Llamado final --}}
        <section class="py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

                <div
                    class="rounded-3xl border border-zinc-200
                           bg-white px-6 py-12 text-center shadow-sm
                           dark:border-zinc-800 dark:bg-zinc-900"
                >
                    <h2 class="text-3xl font-bold text-zinc-900 dark:text-white">
                        Sigue descubriendo nuevos contenidos
                    </h2>

                    <p
                        class="mx-auto mt-4 max-w-2xl leading-7
                               text-zinc-600 dark:text-zinc-300"
                    >
                        Explora nuestras publicaciones y encuentra guías,
                        consejos, novedades y recursos útiles.
                    </p>

                    @if (Route::has('home'))
                        <a
                            href="{{ route('home') }}"
                            class="mt-7 inline-flex rounded-xl bg-amber-500
                                   px-6 py-3 font-semibold text-white
                                   transition hover:bg-amber-600"
                        >
                            Ir al blog
                        </a>
                    @endif
                </div>

            </div>
        </section>

    </div>

</x-layouts::public>