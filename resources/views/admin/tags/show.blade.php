<x-layouts::app :title="__('Tags')">

    <flux:breadcrumbs class="mb-4">
        <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}" separator="slash">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('admin.tags.index') }}" separator="slash">Etiquetas
        </flux:breadcrumbs.item>
        <flux:breadcrumbs.item separator="slash">{{ $tag->name }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>


    <div
        class="dark:bg-opacity-10 px-6 py-8 rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900">

        <flux:field class="mb-4">
            <flux:label>Nombre:</flux:label>

            <flux:input type="text" name="name" id="name" value="{{ $tag->name }}" readonly />

        </flux:field>

        <flux:field class="mb-4">
            <flux:label>Slug:</flux:label>

            <flux:input type="text" name="slug" id="slug" value="{{ $tag->slug }}" readonly />

        </flux:field>

        <flux:field class="mb-4">
            <flux:label>Color:</flux:label>

            <x-admin.color-indicator :color="$tag->color" />
        </flux:field>

    </div>

</x-layouts::app>
