<form
    action="{{ route('admin.categories.destroy', $row->id) }}"
    method="POST"
    onsubmit="return confirm('¿Eliminar esta categoría?')"
>
    @csrf
    @method('DELETE')

    <button
        type="submit"
        class="bg-red-500 text-white px-3 py-2 rounded text-sm"
    >
        Eliminar
    </button>
</form>