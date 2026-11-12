
$(document).ready(function () {
    // ==========================================
    // 1. SIMPAN SETTING GENERAL
    // ==========================================
    $('#ProsesSettingGeneral').on('submit', function (e) {
        e.preventDefault();

        const form = this;
        const button = $('#ButtonSettingGeneral');
        const notification = $('#NotifikasiSimpanSettingGeneral');
        const originalButton = button.html();

        button.prop('disabled', true).html(
            '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Menyimpan...'
        );

        notification.empty();

        $.ajax({
            url: '_Page/SettingGeneral/ProsesSettingGeneral.php',
            type: 'POST',
            data: new FormData(form),
            dataType: 'JSON',
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.status === 'success') {
                    window.location.reload();
                    return;
                }

                notification.html(
                    $('<div>', {
                        class: 'alert alert-danger mb-0',
                        text: response.message || 'Gagal menyimpan pengaturan.'
                    })
                );

                button.prop('disabled', false).html(originalButton);
            },
            error: function (xhr) {
                const message = xhr.responseJSON?.message || 'Terjadi kesalahan saat menyimpan pengaturan.';

                notification.html(
                    $('<div>', {
                        class: 'alert alert-danger mb-0',
                        text: message
                    })
                );

                button.prop('disabled', false).html(originalButton);
            }
        });
    });

    // ==========================================
    // 2. SIMPAN IMAGE / LOGO
    // ==========================================
    $('#ProsesSettingImage').on('submit', function (e) {
        e.preventDefault();

        const form = this;
        const button = $('#ButtonSettingImage');
        const notification = $('#NotifikasiSettingImage');
        const originalButton = button.html();

        button.prop('disabled', true).html(
            '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Mengupload...'
        );

        notification.empty();

        $.ajax({
            url: '_Page/SettingGeneral/ProsesSettingImage.php',
            type: 'POST',
            data: new FormData(form),
            dataType: 'JSON',
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.status === 'success') {
                    window.location.reload();
                    return;
                }

                notification.html(
                    $('<div>', {
                        class: 'alert alert-danger mb-0',
                        text: response.message || 'Gagal menyimpan logo.'
                    })
                );

                button.prop('disabled', false).html(originalButton);
            },
            error: function (xhr) {
                const message = xhr.responseJSON?.message || 'Terjadi kesalahan saat mengupload logo.';

                notification.html(
                    $('<div>', {
                        class: 'alert alert-danger mb-0',
                        text: message
                    })
                );

                button.prop('disabled', false).html(originalButton);
            }
        });
    });
});
