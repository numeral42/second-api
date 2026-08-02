<?php

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public Post $post;

    public string $body = '';

    public array $replyBodies = [];

    public ?int $replyingTo = null;

    public function mount(Post $post): void
    {
        $this->post = $post;
    }

    #[Computed]
    public function comments(): Collection
    {
        return Comment::query()
            ->where('post_id', $this->post->id)
            ->whereNull('parent_id')
            ->where('is_approved', true)
            ->with([
                'user',
                'replies' => fn ($query) => $query
                    ->where('is_approved', true)
                    ->oldest(),
                'replies.user',
                'replies.likes',
            ])
            ->latest()
            ->get();
    }

    public function storeComment(): void
    {
        $this->ensureAuthenticated();

        $validated = $this->validate(
            [
                'body' => [
                    'required',
                    'string',
                    'min:2',
                    'max:2000',
                ],
            ],
            [
                'body.required' => 'Escribe un comentario.',
                'body.min' => 'El comentario debe tener al menos 2 caracteres.',
                'body.max' => 'El comentario no puede superar los 2000 caracteres.',
            ]
        );

        Comment::query()->create([
            'post_id' => $this->post->id,
            'user_id' => Auth::id(),
            'parent_id' => null,
            'body' => trim($validated['body']),
            'is_approved' => true,
        ]);

        $this->reset('body');

        $this->refreshComments();

        $this->dispatch(
            'comment-created',
            message: 'Comentario publicado correctamente.'
        );
    }

    public function openReplyForm(int $commentId): void
    {
        $this->ensureAuthenticated();

        $this->findParentComment($commentId);

        $this->replyingTo = $this->replyingTo === $commentId
            ? null
            : $commentId;

        $this->resetValidation();
    }

    public function cancelReply(): void
    {
        $this->replyingTo = null;

        $this->resetValidation();
    }

    public function storeReply(int $commentId): void
    {
        $this->ensureAuthenticated();

        $parent = $this->findParentComment($commentId);

        $validated = $this->validate(
            [
                "replyBodies.{$commentId}" => [
                    'required',
                    'string',
                    'min:2',
                    'max:2000',
                ],
            ],
            [
                "replyBodies.{$commentId}.required" => 'Escribe una respuesta.',
                "replyBodies.{$commentId}.min" => 'La respuesta debe tener al menos 2 caracteres.',
                "replyBodies.{$commentId}.max" => 'La respuesta no puede superar los 2000 caracteres.',
            ]
        );

        Comment::query()->create([
            'post_id' => $this->post->id,
            'user_id' => Auth::id(),
            'parent_id' => $parent->id,
            'body' => trim($validated['replyBodies'][$commentId]),
            'is_approved' => true,
        ]);

        unset($this->replyBodies[$commentId]);

        $this->replyingTo = null;

        $this->refreshComments();

        $this->dispatch(
            'reply-created',
            message: 'Respuesta publicada correctamente.'
        );
    }

    public function toggleLike(int $replyId): void
    {
        $this->ensureAuthenticated();

        $reply = Comment::query()
            ->whereKey($replyId)
            ->where('post_id', $this->post->id)
            ->whereNotNull('parent_id')
            ->where('is_approved', true)
            ->firstOrFail();

        $like = $reply->likes()
            ->where('user_id', Auth::id())
            ->first();

        if ($like) {
            $like->delete();
        } else {
            $reply->likes()->create([
                'user_id' => Auth::id(),
            ]);
        }

        $this->refreshComments();
    }

    private function findParentComment(int $commentId): Comment
    {
        return Comment::query()
            ->whereKey($commentId)
            ->where('post_id', $this->post->id)
            ->whereNull('parent_id')
            ->where('is_approved', true)
            ->firstOrFail();
    }

    private function ensureAuthenticated(): void
    {
        abort_unless(Auth::check(), 403);
    }

    private function refreshComments(): void
    {
        unset($this->comments);
    }
};
?>

<div>
    <section class="mt-12 border-t border-zinc-200 pt-8 dark:border-zinc-700">

        {{-- Encabezado --}}
        <div class="mb-6 flex items-center justify-between gap-4">
            <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">
                Comentarios
            </h2>

            <span class="text-sm text-zinc-500 dark:text-zinc-400">
                {{ $this->comments->count() }}

                {{ $this->comments->count() === 1
                    ? 'comentario'
                    : 'comentarios' }}
            </span>
        </div>

        {{-- Crear comentario --}}
        @auth
            <form
                wire:submit="storeComment"
                class="mb-8"
            >
                <label
                    for="comment-body"
                    class="mb-2 block font-medium text-zinc-800 dark:text-zinc-200"
                >
                    Escribe un comentario
                </label>

                <textarea
                    wire:model="body"
                    id="comment-body"
                    rows="4"
                    maxlength="2000"
                    placeholder="Comparte tu opinión..."
                    class="w-full rounded-xl border border-zinc-300 bg-white p-4
                           text-zinc-900 outline-none transition
                           focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20
                           dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
                ></textarea>

                @error('body')
                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

                <div class="mt-3 flex justify-end">
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:target="storeComment"
                        class="inline-flex items-center gap-2 rounded-lg
                               bg-blue-600 px-5 py-2.5 font-medium text-white
                               transition hover:bg-blue-700
                               disabled:cursor-not-allowed disabled:opacity-60"
                    >
                        <span
                            wire:loading.remove
                            wire:target="storeComment"
                        >
                            Publicar comentario
                        </span>

                        <span
                            wire:loading
                            wire:target="storeComment"
                        >
                            Publicando...
                        </span>
                    </button>
                </div>
            </form>
        @else
            <div
                class="mb-8 rounded-xl border border-zinc-200
                       bg-zinc-50 p-5
                       dark:border-zinc-700 dark:bg-zinc-900"
            >
                <p class="text-zinc-700 dark:text-zinc-300">
                    Puedes leer todos los comentarios.

                    Para participar debes

                    <a
                        href="{{ route('login') }}"
                        class="font-semibold text-blue-600 hover:underline"
                    >
                        iniciar sesión
                    </a>.
                </p>
            </div>
        @endauth

        {{-- Listado de comentarios --}}
        <div class="space-y-6">
            @forelse ($this->comments as $comment)
                <article
                    wire:key="comment-{{ $comment->id }}"
                    id="comment-{{ $comment->id }}"
                    class="rounded-xl border border-zinc-200
                           bg-white p-5
                           dark:border-zinc-700 dark:bg-zinc-900"
                >
                    <header class="mb-3">
                        <p class="font-semibold text-zinc-900 dark:text-white">
                            {{ $comment->user->name }}
                        </p>

                        <time
                            datetime="{{ $comment->created_at->toIso8601String() }}"
                            class="text-sm text-zinc-500 dark:text-zinc-400"
                        >
                            {{ $comment->created_at->diffForHumans() }}
                        </time>
                    </header>

                    <div
                        class="whitespace-pre-line break-words
                               text-zinc-700 dark:text-zinc-300"
                    >{{ $comment->body }}</div>

                    {{-- Botón y formulario de respuesta --}}
                    @auth
                        <div class="mt-4">
                            <button
                                type="button"
                                wire:click="openReplyForm({{ $comment->id }})"
                                class="text-sm font-semibold
                                       text-blue-600 hover:underline"
                            >
                                {{ $replyingTo === $comment->id
                                    ? 'Cerrar respuesta'
                                    : 'Responder' }}
                            </button>
                        </div>

                        @if ($replyingTo === $comment->id)
                            <form
                                wire:submit="storeReply({{ $comment->id }})"
                                class="mt-4 rounded-xl bg-zinc-50 p-4
                                       dark:bg-zinc-800"
                            >
                                <textarea
                                    wire:model="replyBodies.{{ $comment->id }}"
                                    rows="3"
                                    maxlength="2000"
                                    placeholder="Escribe una respuesta..."
                                    class="w-full rounded-xl border
                                           border-zinc-300 bg-white p-3
                                           text-zinc-900 outline-none
                                           focus:border-blue-500
                                           focus:ring-2 focus:ring-blue-500/20
                                           dark:border-zinc-600
                                           dark:bg-zinc-900 dark:text-white"
                                ></textarea>

                                @error("replyBodies.{$comment->id}")
                                    <p class="mt-2 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                                <div class="mt-3 flex justify-end gap-2">
                                    <button
                                        type="button"
                                        wire:click="cancelReply"
                                        class="rounded-lg border
                                               border-zinc-300 px-4 py-2
                                               text-sm font-medium text-zinc-700
                                               transition hover:bg-zinc-100
                                               dark:border-zinc-600
                                               dark:text-zinc-300
                                               dark:hover:bg-zinc-700"
                                    >
                                        Cancelar
                                    </button>

                                    <button
                                        type="submit"
                                        wire:loading.attr="disabled"
                                        wire:target="storeReply({{ $comment->id }})"
                                        class="rounded-lg bg-zinc-900
                                               px-4 py-2 text-sm font-medium
                                               text-white disabled:opacity-60
                                               dark:bg-white dark:text-zinc-900"
                                    >
                                        <span
                                            wire:loading.remove
                                            wire:target="storeReply({{ $comment->id }})"
                                        >
                                            Publicar respuesta
                                        </span>

                                        <span
                                            wire:loading
                                            wire:target="storeReply({{ $comment->id }})"
                                        >
                                            Publicando...
                                        </span>
                                    </button>
                                </div>
                            </form>
                        @endif
                    @endauth

                    {{-- Respuestas --}}
                    @if ($comment->replies->isNotEmpty())
                        <div
                            class="mt-6 space-y-5 border-l-2
                                   border-zinc-200 pl-5
                                   dark:border-zinc-700"
                        >
                            @foreach ($comment->replies as $reply)
                                @php
                                    $liked = auth()->check()
                                        && $reply->likes->contains(
                                            'user_id',
                                            auth()->id()
                                        );
                                @endphp

                                <article
                                    wire:key="reply-{{ $reply->id }}"
                                    id="reply-{{ $reply->id }}"
                                >
                                    <header class="mb-2">
                                        <p
                                            class="font-semibold
                                                   text-zinc-900 dark:text-white"
                                        >
                                            {{ $reply->user->name }}
                                        </p>

                                        <time
                                            datetime="{{ $reply->created_at->toIso8601String() }}"
                                            class="text-sm text-zinc-500
                                                   dark:text-zinc-400"
                                        >
                                            {{ $reply->created_at->diffForHumans() }}
                                        </time>
                                    </header>

                                    <div
                                        class="whitespace-pre-line break-words
                                               text-zinc-700 dark:text-zinc-300"
                                    >{{ $reply->body }}</div>

                                    {{-- Like --}}
                                    <div class="mt-3">
                                        @auth
                                            <button
                                                type="button"
                                                wire:click="toggleLike({{ $reply->id }})"
                                                wire:loading.attr="disabled"
                                                wire:target="toggleLike({{ $reply->id }})"
                                                class="inline-flex items-center
                                                       gap-2 text-sm font-medium
                                                       transition disabled:opacity-60
                                                       {{ $liked
                                                           ? 'text-blue-600'
                                                           : 'text-zinc-500 hover:text-blue-600 dark:text-zinc-400'
                                                       }}"
                                            >
                                                <span class="text-lg">
                                                    {{ $liked ? '♥' : '♡' }}
                                                </span>

                                                <span>
                                                    Me gusta
                                                </span>

                                                <span>
                                                    {{ $reply->likes->count() }}
                                                </span>
                                            </button>
                                        @else
                                            <span
                                                class="inline-flex items-center
                                                       gap-2 text-sm
                                                       text-zinc-500
                                                       dark:text-zinc-400"
                                            >
                                                <span class="text-lg">
                                                    ♡
                                                </span>

                                                <span>
                                                    {{ $reply->likes->count() }}
                                                </span>
                                            </span>
                                        @endauth
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @endif
                </article>
            @empty
                <div
                    class="rounded-xl border border-dashed
                           border-zinc-300 p-8 text-center
                           dark:border-zinc-700"
                >
                    <p class="text-zinc-500 dark:text-zinc-400">
                        Todavía no hay comentarios.
                    </p>
                </div>
            @endforelse
        </div>
    </section>
</div>