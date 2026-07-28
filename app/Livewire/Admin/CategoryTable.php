<?php

namespace App\Livewire\Admin;

use App\Http\Controllers\Admin\CategoryController;
use App\Models\Category;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use Illuminate\Contracts\View\View;

use Livewire\Attributes\On;

final class CategoryTable extends PowerGridComponent
{
    public string $tableName = 'categoryTable';
    public string $primaryKey = 'id';

    public function setUp(): array
    {
        /*  $this->showCheckBox(); */

        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Category::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('slug')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
                ->sortable(),
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),

            /* Column::make('Slug', 'slug')
                ->sortable()
                ->searchable(), */

            /* Column::make('Created at', 'created_at_formatted', 'created_at')
                ->sortable(),

            Column::make('Created at', 'created_at')
                ->sortable()
                ->searchable(),
 */
            //Column::action('Action')
            Column::action('Acciones'),
        ];
    }

    /*     public function filters(): array
    {
        return [

            Filter::inputText('id'),

            Filter::inputText('name')
                ->placeholder('Nombre'),

        ];
    } */

    /* #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    } */

    /*     public function actions(Category $row): array
    {
        return [
            Button::add('view')
                ->slot('<flux:icon.bolt variant="solid" class="text-amber-500 dark:text-amber-300" />')
                ->class('bg-green-500 cursor-pointer text-white px-3 py-2 m-1 rounded text-sm')
                ->route('admin.categories.show', ['category' => $row->id]),
            Button::add('edit')
                ->slot('Editar')
                ->class('bg-indigo-500 cursor-pointer text-white px-3 py-2 m-1 rounded text-sm')
                ->route('admin.categories.edit', ['category' => $row->id])
                ->dispatch('edit', ['rowId' => $row->id]),
            Button::add('destroy')
                ->slot('Eliminar')
                ->class('bg-red-500 cursor-pointer text-white px-3 py-2 m-1 rounded text-sm')
                ->dispatch('deleteCategory', ['id' => $row->id]),
        ];
    } */

    public function actionsFromView($row): View
    {
        return view('admin.partials.category-actions', [
            'row' => $row,
        ]);
    }

    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */

    /*     #[On('deleteCategory')]
    public function deleteCategory($id)
    {
        app(CategoryController::class)->destroy(
            Category::findOrFail($id)
        );

        $this->dispatch('pg:eventRefresh-categoryTable');
    } */

public function deleteCategory(int $id): void
{
    $category = Category::findOrFail($id);

    app(CategoryController::class)->destroy($category);

    $this->dispatch('pg:eventRefresh-categoryTable');

    $this->dispatch(
        'swal-message',
        icon: 'success',
        title: 'Categoría eliminada',
        text: 'La categoría fue eliminada correctamente.'
    );
}
}
