const synchronizeInput = function () {
    const temporaryContainer = document.createElement('div');

    temporaryContainer.innerHTML = quill.root.innerHTML;

    /*
     * Eliminar callouts vacíos únicamente al final del contenido.
     *
     * Se conservan los callouts vacíos intermedios porque representan
     * saltos de línea intencionales dentro de la caja.
     */
    while (temporaryContainer.lastElementChild?.classList.contains('ql-callout')) {
        const lastCallout = temporaryContainer.lastElementChild;

        const isEmpty =
            lastCallout.textContent.trim() === '' &&
            !lastCallout.querySelector(
                'img, video, iframe, .ql-formula'
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
            'img, video, iframe, .ql-formula'
        ) !== null;

    hiddenInput.value =
        hasText || hasEmbeddedContent
            ? temporaryContainer.innerHTML
            : '';
};