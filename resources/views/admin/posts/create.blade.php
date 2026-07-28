<x-layouts::app :title="__('Posts')">

    <flux:breadcrumbs class="mb-4">
        <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}" separator="slash">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('admin.posts.index') }}" separator="slash">Posts
        </flux:breadcrumbs.item>
        <flux:breadcrumbs.item separator="slash">Nuevo Post</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div
        class="dark:bg-opacity-10 px-6 py-8 rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900">
        <form action="{{ route('admin.posts.store') }}" method="POST">
            @csrf

            <flux:field class="mb-4">
                <flux:label badge="Required">Título:</flux:label>

                <flux:input type="text" name="title" id="title" value="{{ old('title') }}" required
                    oninput="string_to_slug(this.value, '#slug')" />

                <flux:error name="title" />
            </flux:field>

            <flux:field class="mb-4">
                <flux:label badge="Required">Slug:</flux:label>

                <flux:input type="text" name="slug" id="slug" value="{{ old('slug') }}" required />

                <flux:error name="slug" />
            </flux:field>


            <flux:field class="mb-4">
                <flux:label badge="Required">Categoría:</flux:label>

                <flux:select name="category_id" id="category_id" required>
                    <option value="">Seleccione una categoría</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </flux:select>
                <flux:error name="category_id" />
            </flux:field>

            <div class="mt-4 flex justify-end">
                <flux:button type="submit" color="primary" size="sm" class="cursor-pointer">
                    Crear Post
                </flux:button>
            </div>
        </form>
    </div>

</x-layouts::app>
