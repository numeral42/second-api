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
            Column::action('Acciones'),
        ];
    }

    public function actionsFromView($row): View
    {
        return view('admin.partials.category-actions', [
            'row' => $row,
        ]);
    }

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
