// Confirm delete
document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', function(e) {
        if (!confirm('Yakin ingin menghapus data ini?')) {
            e.preventDefault();
        }
    });
});

// Auto dismiss alerts
setTimeout(() => {
    document.querySelectorAll('.alert-dismissible').forEach(el => {
        const bsAlert = bootstrap.Alert.getOrCreateInstance(el);
        if (bsAlert) bsAlert.close();
    });
}, 4000);
