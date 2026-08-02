<x-layouts::app :title="__('Posts')">

    <flux:breadcrumbs class="mb-4">
        <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}" separator="slash">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('admin.posts.index') }}" separator="slash">Posts
        </flux:breadcrumbs.item>
        <flux:breadcrumbs.item separator="slash">{{ $post->title }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>


    <div class="bg-white text-black p-12">
        <div class="text-sm bg-amber-500 text-white px-2 py-1 rounded-xl inline items-end">

            {!! $post->category->name !!}

        </div>

        <h1 class="text-7xl text-center font-bold font-malvinas mt-10">{!! $post->title !!}</h1>
    
        <div class="ql-editor">
            {!! $post->summary !!}
        </div>

        <div class="flex justify-between mb-2">

            <span class="flex"><flux:icon.pencil-square />Autor: {!! $post->user->name !!}</span>
            <span class="flex"><flux:icon.calendar-days />Publicado: {!! $post->published_at !!}</span>
        </div>

        <div class="relative">
            <img class="w-full aspect-video object-cover object-center"
                src="{{ $post->image_path ? Storage::url($post->image_path) : asset('assets/no-image.png') }}"
                alt="{{ $post->title }}" srcset="">
            <div class="absolute -bottom-5 -right-5 gap-1 flex flex-wrap justify-end p-2 max-w-1/2 text-right">
                @foreach ($post->tags as $tag)
                    <span class="whitespace-nowrap px-2 py-1 rounded-lg text-white text-sm font-bold"
                        style="background-color: {{ $tag->color }};">
                        {{ $tag->name }}
                    </span>
                @endforeach
            </div>
        </div>

        <div class="ql-editor post-content">
            <div class="ql-comparison-wrapper">
                {!! $post->body !!}
            </div>
        </div>

        <div id="questions"
            class="relative mt-10 rounded-xl border border-zinc-200 bg-zinc-50
           px-5 py-0 dark:border-zinc-700 dark:bg-zinc-900 pt-3">
            <h3
                class="absolute top-4 left-4 bg-zinc-50 px-3
               text-base font-bold text-zinc-900
               dark:bg-zinc-900 dark:text-white">
                Preguntas frecuentas
            </h3>
            <div class="ql-editor text-sm text-zinc-700 dark:text-zinc-300">
                {!! $post->questions !!}
            </div>
        </div>
            <div id="sources"
                class="relative mt-10 rounded-xl border border-zinc-200 bg-zinc-50
           px-5 py-0 dark:border-zinc-700 dark:bg-zinc-900">
                <h3
                    class="absolute top-4 left-4 bg-zinc-50 px-3
               text-base font-bold text-zinc-900
               dark:bg-zinc-900 dark:text-white">
                    Fuentes técnicas consultadas
                </h3>

                <div class="ql-editor text-sm text-zinc-700 dark:text-zinc-300">
                    {!! $post->sources !!}
                </div>

                <p class="mr-4 text-xs italic text-zinc-500 dark:text-zinc-400 absolute bottom-4 left-4">
                    * Información consultada con asistencia de inteligencia artificial
                    para la generación del post.
                </p>
            </div>

        </div>

</x-layouts::app>
