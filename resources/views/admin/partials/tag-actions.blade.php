<div class="flex items-center justify-center gap-2">

    {{-- Ver --}}
    <flux:button
        href="{{ route('admin.tags.show', $row->id) }}"
        variant="primary"
        size="sm"
        class="bg-green-500 hover:bg-green-600"
        title="Ver etiqueta"
    >
        <flux:icon.eye class="size-4" />
    </flux:button>

    {{-- Editar --}}
    <flux:button
        href="{{ route('admin.tags.edit', $row->id) }}"
        variant="primary"
        size="sm"
        class="bg-blue-500 hover:bg-blue-600"
        title="Editar etiqueta"
    >
        <flux:icon.pencil-square class="size-4" />
    </flux:button>

    {{-- Eliminar --}}
<flux:button
    type="button"
    variant="danger"
    size="sm"
    x-data
    @click="
        confirmDelete(() => {
            $wire.call('deleteTag', {{ $row->id }});
        });
    "
    title="Eliminar etiqueta"
>
    <flux:icon.trash class="size-4" />
</flux:button>

</div>