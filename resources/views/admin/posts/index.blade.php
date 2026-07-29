<x-layouts::app :title="__('Posts')">

    <div class="flex items-center justify-between mb-4">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}" separator="slash">Dashboard
            </flux:breadcrumbs.item>
            {{-- <flux:breadcrumbs.item href="{{ route('admin.categories.index') }}" separator="slash">Categorías
            </flux:breadcrumbs.item> --}}
            <flux:breadcrumbs.item separator="slash">Posts</flux:breadcrumbs.item>
        </flux:breadcrumbs>

        <flux:button href="{{ route('admin.posts.create') }}" icon:trailing="plus" color="primary" size="sm">
            Nuevo
        </flux:button>

    </div>

    @livewire('Admin\PostTable')

{{--     @push('js')
        <script>

        </script>
    @endpush
 --}}

</x-layouts::app>
