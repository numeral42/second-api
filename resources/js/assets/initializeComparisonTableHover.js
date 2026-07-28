function initializeComparisonTableHover(root) {

    root.addEventListener('mouseover', function (event) {

        const cell = event.target.closest('th, td');

        if (!cell) {
            return;
        }

        const table = cell.closest('.ql-comparison');

        if (!table) {
            return;
        }

        clearHover(table);

        const row = cell.parentElement;

        // Primera columna => resaltar fila
        if (cell.cellIndex === 0 && row.parentElement.tagName === 'TBODY') {

            row.classList.add('is-hover-row');

            return;
        }

        // Cabecera => resaltar columna
        if (row.parentElement.tagName === 'THEAD') {

            const index = cell.cellIndex;

            table.querySelectorAll('tr').forEach(function (tr) {

                if (tr.children[index]) {
                    tr.children[index].classList.add('is-hover-column');
                }

            });

        }

    });

    root.addEventListener('mouseleave', function () {

        root.querySelectorAll('.ql-comparison').forEach(clearHover);

    });

    function clearHover(table) {

        table.querySelectorAll('.is-hover-row')
            .forEach(e => e.classList.remove('is-hover-row'));

        table.querySelectorAll('.is-hover-column')
            .forEach(e => e.classList.remove('is-hover-column'));

    }

}