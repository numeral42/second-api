function isDarkMode() {
    const html = document.documentElement;

    return html.classList.contains('dark')
        || getComputedStyle(html).colorScheme === 'dark';
}

function getSwalTheme() {
    if (isDarkMode()) {
        return {
            background: '#3f3f46',
            color: '#f9fafb',
            confirmButtonColor: '#18181b',
            cancelButtonColor: '#18181b',
        };
    }

    return {
        background: '#ffffff',
        color: '#111827',
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#9ca3af',
    };
}

function fireSwal(options = {}) {
    return Swal.fire({
        ...getSwalTheme(),
        ...options,
    });
}

function confirmDelete(callback, options = {}) {
    const {
        title = '¿Eliminar registro?',
        text = 'Esta acción no se puede deshacer.',
        confirmButtonText = 'Sí, eliminar',
        cancelButtonText = 'Cancelar',
        errorTitle = 'No se pudo eliminar',
        errorText = 'Ocurrió un error al eliminar el registro.',
    } = options;

    return fireSwal({
        title,
        text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        confirmButtonText,
        cancelButtonText,
        reverseButtons: true,
    }).then(async (result) => {
        if (!result.isConfirmed) {
            return false;
        }

        if (typeof callback !== 'function') {
            console.error('confirmDelete: el callback no es una función');
            return false;
        }

        try {
            await callback();

            return true;
        } catch (error) {
            console.error('Error al eliminar:', error);

            await fireSwal({
                icon: 'error',
                title: errorTitle,
                text: errorText,
            });

            return false;
        }
    });
}

function swalMessage(icon, title, text = '', options = {}) {
    return fireSwal({
        icon,
        title,
        text,
        ...options,
    });
}

function swalSuccess(message, title = 'Correcto', options = {}) {
    return fireSwal({
        icon: 'success',
        title,
        text: message,
        timer: 1800,
        timerProgressBar: true,
        showConfirmButton: false,
        ...options,
    });
}

function showFlashSwal() {
    const data = window.flashSwal;

    if (!data) {
        return;
    }

    fireSwal({
        icon: data.icon ?? 'info',
        title: data.title ?? '',
        text: data.text ?? '',
        timer: data.timer ?? undefined,
        timerProgressBar: Boolean(data.timer),
        showConfirmButton: data.showConfirmButton ?? !data.timer,
    });

    window.flashSwal = null;
}

document.addEventListener('livewire:init', () => {
    Livewire.on('swal-message', (event) => {
        const data = Array.isArray(event) ? event[0] : event;

        swalMessage(
            data.icon ?? 'info',
            data.title ?? '',
            data.text ?? '',
            data.options ?? {}
        );
    });
});

document.addEventListener('DOMContentLoaded', showFlashSwal);
document.addEventListener('livewire:navigated', showFlashSwal);

window.fireSwal = fireSwal;
window.confirmDelete = confirmDelete;
window.swalMessage = swalMessage;
window.swalSuccess = swalSuccess;