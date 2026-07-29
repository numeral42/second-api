<x-layouts::app :title="__('Posts')">

    <flux:breadcrumbs class="mb-4">
        <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}" separator="slash">
            Dashboard
        </flux:breadcrumbs.item>

        <flux:breadcrumbs.item href="{{ route('admin.posts.index') }}" separator="slash">
            Posts
        </flux:breadcrumbs.item>

        <flux:breadcrumbs.item separator="slash">
            {{ $post->title }}
        </flux:breadcrumbs.item>
    </flux:breadcrumbs>
    <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class=" relative mb-4 border-3 border-dashed border-zinc-200 dark:border-zinc-700 p-1">
            <img id="imgPreview" class="w-full aspect-video object-cover object-center"
                src="{{ $post->image_path ? Storage::url($post->image_path) : asset('assets/no-image.png') }}"
                alt="{{ $post->title }}" srcset="">
            <div class="absolute top-8 right-8">


                <label
                    class="cursor-pointer inline-flex items-center gap-2 rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-500 focus:ring-offset-2 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-zinc-200 dark:focus:ring-zinc-400">
                    Cambiar imagen
                    <flux:input type="file" name="image" id="image" class="hidden" accept="image/*"
                        onchange="previewImage(event, '#imgPreview')" />
                    <flux:error name="image" class="hidden" />
                </label>

            </div>
            <flux:error name="image" />
        </div>
        <div
            class="rounded-lg border border-zinc-200 bg-zinc-50 px-6 py-8
               dark:border-zinc-700 dark:bg-zinc-900">

            <flux:field class="mb-4">
                <flux:label badge="Required">
                    Título:
                </flux:label>

                <flux:input type="text" name="title" id="title" value="{{ old('title', $post->title) }}"
                    required oninput="string_to_slug(this.value, '#slug')" />

                <flux:error name="title" />
            </flux:field>

            <flux:field class="mb-4">
                <flux:label badge="Required">
                    Slug:
                </flux:label>

                <flux:input type="text" name="slug" id="slug" value="{{ old('slug', $post->slug) }}"
                    required />

                <flux:error name="slug" />
            </flux:field>

            <flux:field class="mb-4">
                <flux:label badge="Required">
                    Categoría:
                </flux:label>

                <flux:select name="category_id" id="category_id" required>
                    <option value="">
                        Seleccione una categoría
                    </option>

                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $post->category_id) == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </flux:select>

                <flux:error name="category_id" />
            </flux:field>

            <flux:field class="mb-4 rounded-lg border border-zinc-200 p-4
                       dark:border-zinc-700">
                <flux:radio.group label="Estado:" name="is_published" :value="old('is_published', $post->is_published)">
                    <flux:radio label="Publicado" value="1"
                        :checked="old('is_published', $post->is_published) == 1" disabled/>

                    <flux:radio label="No publicado" value="0"
                        :checked="old('is_published', $post->is_published) == 0" disabled/>
                </flux:radio.group>

                <flux:error name="is_published" />
            </flux:field>

            <flux:field class="mb-4 bg-white text-zinc-900 p-4 rounded-lg">

                <flux:label class="bg-white text-zinc-900">Etiquetas:</flux:label>

                <select id="select-tags" name="tags[]" multiple placeholder="Seleccione las etiquetas..."
                    autocomplete="off">
                    {{-- <option value="">Seleccione las etiquetas...</option> --}}
                    @foreach ($tags as $tag)
                        <option data-color="{{ $tag->color }}" value="{{ $tag->id }}"
                            @selected(in_array($tag->id, old('tags', $post->tags->pluck('id')->toArray())))>
                            {{ $tag->name }}
                        </option>
                    @endforeach
                </select>

            </flux:field>

            <flux:field class="mb-4 bg-white text-zinc-900 p-4 rounded-lg">
                <flux:label class="bg-white text-zinc-900">Resumen:</flux:label>

                <div id="summary-editor" data-quill-input="summary" data-placeholder="Escribe el resumen del post..."
                    class="quill-editor bg-white text-zinc-900 {{-- dark:bg-zinc-900 dark:text-zinc-100 --}}"></div>

                <input type="hidden" name="summary" id="summary" value="{{ old('summary', $post->summary) }}">

                <flux:error name="summary" />
            </flux:field>

            <flux:field class="mb-4">
                <flux:label>
                    Contenido:
                </flux:label>

                <div id="body-toolbar">
                    <span class="ql-formats">
                        <select class="ql-font"></select>
                        <select class="ql-size"></select>
                    </span>
                    <span class="ql-formats">
                        <button class="ql-bold"></button>
                        <button class="ql-italic"></button>
                        <button class="ql-underline"></button>
                        <button class="ql-strike"></button>
                    </span>
                    <span class="ql-formats">
                        <select class="ql-color"></select>
                        <select class="ql-background"></select>
                    </span>
                    <span class="ql-formats">
                        <button class="ql-script" value="sub"></button>
                        <button class="ql-script" value="super"></button>
                    </span>
                    <span class="ql-formats">
                        <button class="ql-header" value="1"></button>
                        <button class="ql-header" value="2"></button>
                        <button class="ql-blockquote"></button>
                        <button class="ql-code-block"></button>
                    </span>
                    <span class="ql-formats">
                        <button class="ql-list" value="ordered"></button>
                        <button class="ql-list" value="bullet"></button>
                        <button class="ql-indent" value="-1"></button>
                        <button class="ql-indent" value="+1"></button>
                    </span>
                    <span class="ql-formats">
                        <button class="ql-direction" value="rtl"></button>
                        <select class="ql-align"></select>
                    </span>
                    <span class="ql-formats">
                        <button class="ql-link"></button>
                        <button class="ql-image"></button>
                        <button class="ql-video"></button>
                        <button class="ql-formula"></button>
                    </span>
                    <span class="ql-formats">
                        <select class="ql-callout">
                            <option selected></option>
                            <option value="professional-tip"></option>
                            <option value="warning"></option>
                            <option value="note"></option>
                            <option value="recommendation"></option>
                        </select>
                    </span>

                    <span class="ql-formats">
                        <button type="button" class="ql-comparison-table">
                            📊
                        </button>
                    </span>

                    <span class="ql-formats">
                        <button class="ql-clean"></button>
                    </span>
                </div>


                <div id="body-editor" data-quill-input="body"
                    data-placeholder="Escribe el contenido completo del post..."
                    class="quill-editor bg-white text-zinc-900 dark:bg-zinc-900 dark:text-zinc-100">
                </div>

                <input type="hidden" name="body" id="body" value="{{ old('body', $post->body) }}">

                <flux:error name="body" />
            </flux:field>

            <flux:field class="mb-4">
                <flux:label>Preguntas frecuentes:</flux:label>

                <div id="questions-editor" data-quill-input="questions"
                    data-placeholder="Escribe las preguntas frecuentes..."
                    class="quill-editor bg-white text-zinc-900 dark:bg-zinc-900 dark:text-zinc-100"></div>

                <input type="hidden" name="questions" id="questions"
                    value="{{ old('questions', $post->questions) }}">

                <flux:error name="questions" />
            </flux:field>

            <flux:field class="mb-4">
                <flux:label>Fuentes:</flux:label>

                <div id="sources-editor" data-quill-input="sources"
                    data-placeholder="Agrega las fuentes del artículo..."
                    class="quill-editor bg-white text-zinc-900 dark:bg-zinc-900 dark:text-zinc-100"></div>

                <input type="hidden" name="sources" id="sources" value="{{ old('sources', $post->sources) }}">

                <flux:error name="sources" />
            </flux:field>

            <div class="mt-4 flex justify-end">
                <flux:button type="submit" variant="primary" size="sm" class="cursor-pointer">
                    Actualizar Post
                </flux:button>
            </div>

        </div>
    </form>
    @push('js')
        <script>
            new TomSelect('#select-tags', {
                plugins: ['remove_button'],

                render: {
                    option: function(data, escape) {
                        return `
                <div class="flex items-center gap-2">
                    <span
                        style="
                            width:15px;
                            height:15px;
                            border-radius:5px;
                            background:${data.$option.dataset.color};
                        "
                    ></span>

                    ${escape(data.text)}
                </div>
            `;
                    },

                    item: function(data, escape) {
                        return `
                <div class="flex items-center gap-2">
                    <span
                        style="
                            width:12px;
                            height:12px;
                            border-radius:2px;
                            background:${data.$option.dataset.color};
                        "
                    ></span>

                    ${escape(data.text)}
                </div>
            `;
                    }
                }
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const editors = document.querySelectorAll('[data-quill-input]');
                const form = document.querySelector(
                    'form[action="{{ route('admin.posts.update', $post) }}"]'
                );

                if (typeof Quill === 'undefined') {
                    console.error('Quill no está cargado.');
                    return;
                }

                /*
                |--------------------------------------------------------------------------
                | Callouts
                |--------------------------------------------------------------------------
                */

                const Block = Quill.import('blots/block');

                class CalloutBlot extends Block {
                    static blotName = 'callout';
                    static tagName = 'div';
                    static className = 'ql-callout';

                    static allowedTypes = [
                        'professional-tip',
                        'warning',
                        'note',
                        'recommendation',
                    ];

                    static create(value) {
                        const node = super.create();
                        const type = this.allowedTypes.includes(value) ?
                            value :
                            'note';

                        node.setAttribute('data-callout', type);

                        return node;
                    }

                    static formats(node) {
                        return node.getAttribute('data-callout') ?? 'note';
                    }

                    format(name, value) {
                        if (name !== CalloutBlot.blotName) {
                            super.format(name, value);
                            return;
                        }

                        if (value && CalloutBlot.allowedTypes.includes(value)) {
                            this.domNode.setAttribute('data-callout', value);
                            return;
                        }

                        this.replaceWith('block');
                    }
                }

                Quill.register(CalloutBlot, true);

                /*
                |--------------------------------------------------------------------------
                | Tabla comparativa
                |--------------------------------------------------------------------------
                */

                const BlockEmbed = Quill.import('blots/block/embed');

                class ComparisonTableBlot extends BlockEmbed {
                    static blotName = 'comparison-table';
                    static tagName = 'table';
                    static className = 'ql-comparison';

                    static create(value) {
                        const node = super.create();
                        const data = this.normalizeValue(value);

                        node.setAttribute('contenteditable', 'false');
                        node.dataset.comparisonTable = JSON.stringify(data);

                        const thead = document.createElement('thead');
                        const headerRow = document.createElement('tr');

                        data.headers.forEach(function(header) {
                            const th = document.createElement('th');

                            th.setAttribute('scope', 'col');
                            th.textContent = header;

                            headerRow.appendChild(th);
                        });

                        thead.appendChild(headerRow);

                        const tbody = document.createElement('tbody');

                        data.rows.forEach(function(row) {
                            const tr = document.createElement('tr');

                            data.headers.forEach(function(_, columnIndex) {
                                const cell = columnIndex === 0 ?
                                    document.createElement('th') :
                                    document.createElement('td');

                                if (columnIndex === 0) {
                                    cell.setAttribute('scope', 'row');
                                }

                                cell.textContent = row[columnIndex] ?? '';

                                tr.appendChild(cell);
                            });

                            tbody.appendChild(tr);
                        });

                        node.appendChild(thead);
                        node.appendChild(tbody);

                        return node;
                    }

                    static value(node) {
                        try {
                            return JSON.parse(
                                node.dataset.comparisonTable ?? '{}'
                            );
                        } catch {
                            return {
                                headers: [],
                                rows: [],
                            };
                        }
                    }

                    static normalizeValue(value) {
                        return {
                            headers: Array.isArray(value?.headers) ?
                                value.headers.map(function(header) {
                                    return String(header ?? '').trim();
                                }) : [],

                            rows: Array.isArray(value?.rows) ?
                                value.rows.map(function(row) {
                                    return Array.isArray(row) ?
                                        row.map(function(cell) {
                                            return String(cell ?? '').trim();
                                        }) : [];
                                }) : [],
                        };
                    }
                }

                Quill.register(ComparisonTableBlot, true);

                /*
                |--------------------------------------------------------------------------
                | Toolbar estándar
                |--------------------------------------------------------------------------
                */

                const standardToolbar = [
                    [{
                        header: [1, 2, 3, false]
                    }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{
                        list: 'ordered'
                    }, {
                        list: 'bullet'
                    }],
                    [{
                        indent: '-1'
                    }, {
                        indent: '+1'
                    }],
                    ['blockquote', 'code-block'],
                    ['link'],
                    ['clean'],
                ];

                /*
                |--------------------------------------------------------------------------
                | Crear tabla
                |--------------------------------------------------------------------------
                */

                function insertComparisonTable(quill) {

                    const columnsInput = window.prompt(
                        'Escribe los encabezados separados por comas:',
                        'Característica,Producto A,Producto B'
                    );

                    if (columnsInput === null) {
                        return;
                    }

                    const headers = columnsInput
                        .split(',')
                        .map((header) => header.trim())
                        .filter(Boolean);

                    if (headers.length < 2) {
                        window.alert(
                            'La tabla necesita al menos dos columnas.'
                        );
                        return;
                    }

                    const rowCountInput = window.prompt(
                        '¿Cuántas filas deseas agregar?',
                        '4'
                    );

                    if (rowCountInput === null) {
                        return;
                    }

                    const rowCount = Number.parseInt(rowCountInput, 10);

                    if (
                        !Number.isInteger(rowCount) ||
                        rowCount < 1 ||
                        rowCount > 20
                    ) {
                        window.alert(
                            'Indica una cantidad de filas entre 1 y 20.'
                        );
                        return;
                    }

                    const rows = [];

                    for (
                        let rowIndex = 0; rowIndex < rowCount; rowIndex++
                    ) {
                        const row = [];

                        for (
                            let columnIndex = 0; columnIndex < headers.length; columnIndex++
                        ) {
                            const value = window.prompt(
                                `Fila ${rowIndex + 1}: ${headers[columnIndex]}`,
                                ''
                            );

                            if (value === null) {
                                return;
                            }

                            row.push(value.trim());
                        }

                        rows.push(row);
                    }

                    const range = quill.getSelection(true);
                    const insertionIndex = range ?
                        range.index :
                        Math.max(0, quill.getLength() - 1);

                    quill.insertText(insertionIndex, '\n', 'user');

                    quill.insertEmbed(
                        insertionIndex + 1,
                        'comparison-table', {
                            headers,
                            rows,
                        },
                        'user'
                    );

                    quill.insertText(insertionIndex + 2, '\n', 'user');
                    quill.setSelection(insertionIndex + 3, 0, 'silent');
                }

                /*
                |--------------------------------------------------------------------------
                | Editar tabla
                |--------------------------------------------------------------------------
                */

                function editComparisonTable(quill, tableElement) {
                    let currentData;

                    try {
                        currentData = JSON.parse(
                            tableElement.dataset.comparisonTable ?? '{}'
                        );
                    } catch (error) {
                        console.error(error);
                        window.alert('No se pudo leer la tabla.');
                        return;
                    }

                    const headersInput = window.prompt(
                        'Encabezados separados por comas:',
                        (currentData.headers ?? []).join(', ')
                    );

                    if (headersInput === null) {
                        return;
                    }

                    const headers = headersInput
                        .split(',')
                        .map((header) => header.trim())
                        .filter(Boolean);

                    if (headers.length < 2) {
                        window.alert(
                            'La tabla necesita al menos dos columnas.'
                        );
                        return;
                    }

                    const rowCountInput = window.prompt(
                        'Cantidad de filas:',
                        String(currentData.rows?.length ?? 1)
                    );

                    if (rowCountInput === null) {
                        return;
                    }

                    const rowCount = Number.parseInt(rowCountInput, 10);

                    if (
                        !Number.isInteger(rowCount) ||
                        rowCount < 1 ||
                        rowCount > 20
                    ) {
                        window.alert(
                            'Indica una cantidad de filas entre 1 y 20.'
                        );
                        return;
                    }

                    const rows = [];

                    for (
                        let rowIndex = 0; rowIndex < rowCount; rowIndex++
                    ) {
                        const row = [];

                        for (
                            let columnIndex = 0; columnIndex < headers.length; columnIndex++
                        ) {
                            const previousValue =
                                currentData.rows?.[rowIndex]?.[columnIndex] ?? '';

                            const value = window.prompt(
                                `Fila ${rowIndex + 1}: ${headers[columnIndex]}`,
                                previousValue
                            );

                            if (value === null) {
                                return;
                            }

                            row.push(value.trim());
                        }

                        rows.push(row);
                    }

                    const blot = Quill.find(tableElement);

                    if (!blot) {
                        window.alert(
                            'No se pudo localizar la tabla dentro del editor.'
                        );
                        return;
                    }

                    const blotIndex = quill.getIndex(blot);

                    quill.deleteText(blotIndex, 1, 'user');

                    quill.insertEmbed(
                        blotIndex,
                        'comparison-table', {
                            headers,
                            rows,
                        },
                        'user'
                    );

                    quill.setSelection(blotIndex + 1, 0, 'silent');
                }

                /*
                |--------------------------------------------------------------------------
                | Inicializar editores
                |--------------------------------------------------------------------------
                */

                editors.forEach(function(editorElement) {
                    const inputId = editorElement.dataset.quillInput;
                    const hiddenInput = document.getElementById(inputId);

                    if (
                        !hiddenInput ||
                        editorElement.dataset.initialized === 'true'
                    ) {
                        return;
                    }

                    editorElement.dataset.initialized = 'true';

                    const isBodyEditor = inputId === 'body';

                    const toolbarOptions = isBodyEditor ? {
                            container: '#body-toolbar',
                            handlers: {
                                callout(value) {
                                    const range =
                                        this.quill.getSelection(true);

                                    if (!range) {
                                        return;
                                    }

                                    this.quill.formatLine(
                                        range.index,
                                        Math.max(range.length, 1),
                                        'callout',
                                        value || false,
                                        'user'
                                    );
                                },

                                'comparison-table'() {
                                    insertComparisonTable(this.quill);
                                },
                            },
                        } :
                        standardToolbar;

                    const quill = new Quill(editorElement, {
                        theme: 'snow',
                        placeholder: editorElement.dataset.placeholder ?? '',
                        modules: {
                            toolbar: toolbarOptions,
                        },
                    });

                    /*
                     * Editar la tabla con doble clic.
                     * Debe registrarse dentro de esta instancia de Quill.
                     */
                    if (isBodyEditor) {
                        quill.root.addEventListener(
                            'dblclick',
                            function(event) {
                                const target = event.target;

                                if (!(target instanceof Element)) {
                                    return;
                                }

                                const tableElement = target.closest(
                                    'table.ql-comparison'
                                );

                                if (!tableElement) {
                                    return;
                                }

                                event.preventDefault();
                                event.stopPropagation();

                                editComparisonTable(
                                    quill,
                                    tableElement
                                );
                            }
                        );
                    }

                    /*
                     * Cargar contenido existente después de registrar los blots.
                     */
                    if (hiddenInput.value.trim() !== '') {
                        quill.clipboard.dangerouslyPasteHTML(
                            hiddenInput.value
                        );
                    }

                    const synchronizeInput = function() {
                        const temporaryContainer =
                            document.createElement('div');

                        temporaryContainer.innerHTML =
                            quill.root.innerHTML;

                        /*
                         * Eliminar solamente los callouts vacíos finales.
                         */
                        while (
                            temporaryContainer.lastElementChild
                            ?.classList.contains('ql-callout')
                        ) {
                            const lastCallout =
                                temporaryContainer.lastElementChild;

                            const isEmpty =
                                lastCallout.textContent.trim() === '' &&
                                !lastCallout.querySelector(
                                    [
                                        'img',
                                        'video',
                                        'iframe',
                                        '.ql-formula',
                                        '.ql-comparison',
                                    ].join(',')
                                );

                            if (!isEmpty) {
                                break;
                            }

                            lastCallout.remove();
                        }

                        const hasText =
                            temporaryContainer.textContent.trim().length > 0;

                        const hasEmbeddedContent =
                            temporaryContainer.querySelector(
                                [
                                    'img',
                                    'video',
                                    'iframe',
                                    '.ql-formula',
                                    '.ql-comparison',
                                ].join(',')
                            ) !== null;

                        hiddenInput.value =
                            hasText || hasEmbeddedContent ?
                            temporaryContainer.innerHTML :
                            '';
                    };

                    quill.on('text-change', synchronizeInput);

                    editorElement.quillInstance = quill;
                    editorElement.synchronizeInput = synchronizeInput;
                });
                /*
                |--------------------------------------------------------------------------
                | Sincronizar antes de enviar
                |--------------------------------------------------------------------------
                */

                form?.addEventListener('submit', function() {
                    editors.forEach(function(editorElement) {
                        if (
                            typeof editorElement.synchronizeInput ===
                            'function'
                        ) {
                            editorElement.synchronizeInput();
                        }
                    });
                });
            });
        </script>
    @endpush

</x-layouts::app>
