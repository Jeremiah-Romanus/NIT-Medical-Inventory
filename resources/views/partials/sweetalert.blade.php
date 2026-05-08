<style>
    .swal2-popup.nit-swal {
        border-radius: 22px;
        background: #ffffff;
        color: #0f172a;
        border: 1px solid rgba(143, 211, 255, 0.35);
        box-shadow: 0 22px 54px rgba(15, 23, 42, 0.12);
    }

    .swal2-title.nit-swal-title,
    .swal2-html-container.nit-swal-text {
        color: #0f172a;
    }

    .swal2-confirm.nit-swal-confirm {
        border-radius: 12px !important;
        padding: 0.8rem 1.4rem !important;
        font-weight: 700 !important;
        background: #4aaef0 !important;
        color: #ffffff !important;
        box-shadow: none !important;
        border: 0 !important;
    }

    .swal2-icon.swal2-success {
        border-color: rgba(74, 174, 240, 0.4) !important;
        color: #8fd3ff !important;
    }

    .swal2-icon.swal2-error {
        border-color: rgba(248, 113, 113, 0.32) !important;
        color: #f87171 !important;
    }

    .swal2-icon.swal2-warning {
        border-color: rgba(251, 191, 36, 0.32) !important;
        color: #fbbf24 !important;
    }

</style>

@php
    $swalMessages = collect([
        session('success') ? [
            'icon' => 'success',
            'title' => 'Success',
            'text' => session('success'),
        ] : null,
        session('error') ? [
            'icon' => 'error',
            'title' => 'Action failed',
            'text' => session('error'),
        ] : null,
        session('warning') ? [
            'icon' => 'warning',
            'title' => 'Attention',
            'text' => session('warning'),
        ] : null,
        session('info') ? [
            'icon' => 'info',
            'title' => 'Information',
            'text' => session('info'),
        ] : null,
    ])->filter()->values();
@endphp

@if ($swalMessages->isNotEmpty())
    <script>
        document.addEventListener('DOMContentLoaded', async function () {
            const flashMessages = @json($swalMessages);

            for (const message of flashMessages) {
                await Swal.fire({
                    icon: message.icon,
                    title: message.title,
                    text: message.text,
                    timer: 3000,
                    showConfirmButton: false,
                    allowOutsideClick: true,
                    allowEscapeKey: true,
                    background: '#ffffff',
                    color: '#0f172a',
                    customClass: {
                        popup: 'nit-swal',
                        title: 'nit-swal-title',
                        htmlContainer: 'nit-swal-text',
                        confirmButton: 'nit-swal-confirm'
                    }
                });
            }
        });
    </script>
@endif
