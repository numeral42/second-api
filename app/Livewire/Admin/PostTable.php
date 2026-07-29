<?php

namespace App\Livewire\Admin;

use App\Http\Controllers\Admin\PostController;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use Illuminate\Support\Facades\Blade;
use Illuminate\Contracts\View\View;

final class PostTable extends PowerGridComponent
{
    public string $tableName = 'postTable';
    public string $primaryKey = 'id';

    /*
    |--------------------------------------------------------------------------
    | Mostrar los filtros externos abiertos
    |--------------------------------------------------------------------------
    */

    public bool $showFilters = true;

    public function boot(): void
    {
        config([
            'livewire-powergrid.filter' => 'outside',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Configuración de la tabla
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | Consulta
    |--------------------------------------------------------------------------
    */

    public function datasource(): Builder
    {
        return Post::query()
            ->leftJoin(
                'categories',
                'posts.category_id',
                '=',
                'categories.id'
            )
            ->leftJoin(
                'users',
                'posts.user_id',
                '=',
                'users.id'
            )
            ->select([
                'posts.*',
                'categories.name as category_name',
                'users.name as user_name',
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Campos
    |--------------------------------------------------------------------------
    */

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('order')
            ->add('title')
            ->add('category_id')
            ->add('category_name')
            ->add('user_id')
            ->add('user_name')
            ->add('is_published')
            ->add(
                'publication_status',
                fn(Post $post): string => Blade::render(
                    '<x-admin.post-status-switch
                    :post-id="$postId"
                    :published="$published"
                />',
                    [
                        'postId' => $post->id,
                        'published' => (bool) $post->is_published,
                    ]
                )
            )
            ->add('published_at')
            ->add(
                'published_at_formatted',
                fn(Post $post): string => $post->published_at
                    ? Carbon::parse($post->published_at)->format('d/m/Y H:i')
                    : 'Sin publicar'
            )
            ->add('created_at')
            ->add(
                'created_at_formatted',
                fn(Post $post): string => $post->created_at
                    ? Carbon::parse($post->created_at)->format('d/m/Y H:i')
                    : ''
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Columnas
    |--------------------------------------------------------------------------
    */

    public function columns(): array
    {
        return [
            Column::make('Orden', 'order', 'posts.order')
                ->sortable(),

            Column::make('Título', 'title', 'posts.title')
                ->sortable()
                ->searchable(),

            Column::make(
                'Categoría',
                'category_name',
                'categories.name'
            )
                ->sortable()
                ->searchable(),

            Column::make(
                'Autor',
                'user_name',
                'users.name'
            )
                ->sortable()
                ->searchable(),

            Column::make(
                'Estado',
                'publication_status',
                'posts.is_published'
            )
                ->sortable()
                ->template(),

            Column::make(
                'Publicado',
                'published_at_formatted',
                'posts.published_at'
            )
                ->sortable(),

            Column::make(
                'Creado',
                'created_at_formatted',
                'posts.created_at'
            )
                ->sortable(),

            Column::action('Acciones'),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Filtros
    |--------------------------------------------------------------------------
    */

    public function filters(): array
    {
        return [
            Filter::select(
                'category_name',
                'posts.category_id'
            )
                ->dataSource(
                    Category::query()
                        ->select(['id', 'name'])
                        ->orderBy('name', 'asc')
                        ->get()
                )
                ->optionLabel('name')
                ->optionValue('id'),

            Filter::boolean(
                'publication_status',
                'posts.is_published'
            )
                ->label('Publicado', 'Borrador'),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Acciones
    |--------------------------------------------------------------------------
    */

    public function actionsFromView($row): View
    {
        return view('admin.partials.post-actions', [
            'row' => $row,
        ]);
    }
 
    public function togglePostStatus(int $postId): void
    {
        $post = Post::query()->findOrFail($postId);

        $isPublished = ! $post->is_published;

        /* if(!$post->published_at ) {
            $data['published_at'] = now();
        } */

        $post->update([
            'is_published' => $isPublished,
            'published_at' => $isPublished
                ? ($post->published_at ?? now())
                : $post->published_at,
        ]);
    }

    public function deletePost(int $id): void
    {

        $post = Post::findOrFail($id);

        app(PostController::class)->destroy($post);

        $this->dispatch('pg:eventRefresh-postTable');

        $this->dispatch(
            'swal-message',
            icon: 'success',
            title: 'Publicación eliminada',
            text: 'La publicación fue eliminada correctamente.'
        );
    }
}
