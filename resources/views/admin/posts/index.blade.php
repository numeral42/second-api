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


    {{-- @foreach ($categories as $category)
    {{ $category }}
@endforeach --}}


    @livewire('Admin\PostTable')

    @push('js')
        @if (session()->has('swal'))
            <script>
                document.addEventListener('livewire:init', () => {
                    Livewire.on('swal', (event) => {
                        const data = Array.isArray(event) ? event[0] : event;

                        window.swalMessage(
                            @json(session('swal.icon')),
                            @json(session('swal.title')),
                            @json(session('swal.text'))
                        );
                    });
                });
            </script>
        @endif
        </script>
    @endpush


</x-layouts::app>
