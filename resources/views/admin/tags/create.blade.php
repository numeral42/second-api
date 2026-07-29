<x-layouts::app :title="__('Tags')">

    <flux:breadcrumbs class="mb-4">
        <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}" separator="slash">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('admin.tags.index') }}" separator="slash">Etiquetas
        </flux:breadcrumbs.item>
        <flux:breadcrumbs.item separator="slash">Nueva Etiqueta</flux:breadcrumbs.item>
    </flux:breadcrumbs>



    <div
        class="dark:bg-opacity-10 px-6 py-8 rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900">
        <form action="{{ route('admin.tags.store') }}" method="POST">
            @csrf

            <flux:field class="mb-4">
                <flux:label badge="Required">Nombre:</flux:label>

                <flux:input type="text" name="name" id="name" value="{{ old('name') }}" required
                    oninput="string_to_slug(this.value, '#slug')" />

                <flux:error name="name" />
            </flux:field>

            <flux:field class="mb-4">
                <flux:label badge="Required">Slug:</flux:label>

                <flux:input type="text" name="slug" id="slug" value="{{ old('slug') }}" required />

                <flux:error name="slug" />
            </flux:field>

            <flux:field class="mb-4">
                <flux:label badge="Required">Color:</flux:label>

                <flux:input type="color" name="color" id="color" value="{{ old('color') }}" required />

                <flux:error name="color" />
            </flux:field>


            <div class="mt-4 flex justify-end">
                <flux:button type="submit" color="primary" size="sm" class="cursor-pointer">
                    Crear Etiqueta
                </flux:button>
            </div>


        </form>
    </div>


</x-layouts::app>
