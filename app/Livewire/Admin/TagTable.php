<?php

namespace App\Livewire\Admin;

use App\Http\Controllers\Admin\TagController;
use Illuminate\Support\Facades\Blade;
use App\Models\Tag;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use Illuminate\Contracts\View\View;

final class TagTable extends PowerGridComponent
{
    public string $tableName = 'tagTable';
    public string $primaryKey = 'id';

    public function setUp(): array
    {
        $this->showCheckBox();

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
        return Tag::query();
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
            ->add('color')
            ->add('color_indicator', function ($row) {
                return Blade::render(
                    '<x-admin.color-indicator :color="$color" />',
                    ['color' => $row->color]
                );
            })
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id'),
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Slug', 'slug')
                ->sortable()
                ->searchable(),

            Column::make('Color', 'color_indicator'),

            Column::action('Acciones')
        ];
    }

    public function filters(): array
    {
        return [];
    }

    public function actionsFromView($row): View
    {
        return view('admin.partials.tag-actions', [
            'row' => $row,
        ]);
    }

    public function deleteTag(int $id): void
    {

        $tag = Tag::findOrFail($id);

        app(TagController::class)->destroy($tag);

        $this->dispatch('pg:eventRefresh-tagTable');

        $this->dispatch(
            'swal-message',
            icon: 'success',
            title: 'Etiqueta eliminada',
            text: 'La etiqueta fue eliminada correctamente.'
        );
    }
}
