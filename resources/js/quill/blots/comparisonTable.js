

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

        data.headers.forEach(function (header) {
            const th = document.createElement('th');

            th.setAttribute('scope', 'col');
            th.textContent = header;

            headerRow.appendChild(th);
        });

        thead.appendChild(headerRow);

        const tbody = document.createElement('tbody');

        data.rows.forEach(function (row) {
            const tr = document.createElement('tr');

            data.headers.forEach(function (_, columnIndex) {
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
                value.headers.map(function (header) {
                    return String(header ?? '').trim();
                }) : [],

            rows: Array.isArray(value?.rows) ?
                value.rows.map(function (row) {
                    return Array.isArray(row) ?
                        row.map(function (cell) {
                            return String(cell ?? '').trim();
                        }) : [];
                }) : [],
        };
    }
}

Quill.register(ComparisonTableBlot, true);



