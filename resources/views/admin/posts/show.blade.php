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
            <img class="w-full aspect-video object-cover object-center" src="{{ $post->image_path ? Storage::url($post->image_path) : asset('assets/no-image.png') }}"
                alt="{{ $post->title }}" srcset="">
            <div class="absolute -bottom-5 -right-5 gap-1 flex flex-wrap justify-end p-2 max-w-1/2 text-right">
                @foreach ($post->tags as $tag)
                    <span class="whitespace-nowrap px-2 py-1 rounded-lg text-white text-sm font-bold" style="background-color: {{ $tag->color }};">
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

        <div class="ql-editor" id="questions">
            <h4 class="text-2xl font-bold">Preguntas frecuentes</h4>
            {!! $post->questions !!}
        </div>

<div id="sources" class="space-y-3 border-4 border-red-600 rounded-xl p-4 bg-red-100">
    <span class="1xl text-red-600 font-bold">
        Fuentes técnicas consultadas
    </span>

    <div class="ql-editor">
        {!! $post->sources !!}
    </div>
    <span class=" text-xs text-zinc-500">*información consultada por IA para generar los posteos.</span>
</div>
        
    </div>

</x-layouts::app>
