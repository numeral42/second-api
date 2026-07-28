@props([
    'postId',
    'published' => false,
])

<div
    x-data="{
        active: @js((bool) $published),
        loading: false,

        async toggleStatus() {
            if (this.loading) {
                return;
            }

            const previous = this.active;

            this.active = !this.active;
            this.loading = true;

            try {
                await $wire.togglePostStatus({{ $postId }});
            } catch (error) {
                this.active = previous;
            } finally {
                this.loading = false;
            }
        }
    }"
    wire:key="post-status-{{ $postId }}"
>
    <button
        type="button"
        x-on:click="toggleStatus()"
        x-bind:disabled="loading"
        x-bind:aria-checked="active"
        role="switch"
        class="inline-flex items-center gap-2 disabled:cursor-wait disabled:opacity-60"
    >
        <span
            class="relative inline-flex h-6 w-11 shrink-0 rounded-full transition-colors duration-200"
            x-bind:class="active
                ? 'bg-green-500'
                : 'bg-zinc-300 dark:bg-zinc-600'"
        >
            <span
                class="absolute left-0.5 top-0.5 size-5 rounded-full bg-white shadow transition-transform duration-200"
                x-bind:class="active
                    ? 'translate-x-5'
                    : 'translate-x-0'"
            ></span>
        </span>

        <span
            class="text-sm font-medium"
            x-text="active ? 'Publicado' : 'Borrador'"
            x-bind:class="active
                ? 'text-green-600 dark:text-green-400'
                : 'text-zinc-600 dark:text-zinc-300'"
        ></span>
    </button>
</div>