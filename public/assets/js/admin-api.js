(function () {
    'use strict';
    let currentRequest;

    async function request(url, options) {
        const response = await fetch(url, {credentials: 'same-origin', ...options});
        const data = await response.json();
        if (!response.ok) throw new Error(data.message || 'Permintaan gagal diproses.');
        return data;
    }

    function showError(error) {
        let box = document.getElementById('adminApiMessage');
        if (!box) {
            box = document.createElement('div');
            box.id = 'adminApiMessage';
            box.className = 'alert alert-danger';
            box.setAttribute('role', 'alert');

        }
        const target = document.querySelector('.modal.show .modal-category-body, .modal.show .modal-body') || document.querySelector('main') || document.body;
        target.prepend(box);
        box.textContent = error.message || 'Koneksi gagal. Muat ulang halaman untuk mencoba lagi.';
        box.scrollIntoView({block: 'nearest'});
    }

    function badges(data) {
        const pending = data.assets.filter(a => a.status === 'Menunggu').length;
        const sellers = data.sellers.filter(s => s.status === 'Menunggu').length;
        ['sidebarModerationBadge'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.textContent = pending;
        });
        ['sidebarSellerBadge', 'sidebarSellerReqBadge'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.textContent = sellers;
        });
    }

    window.AdminApi = {
        async data() {
            // Reuse a single snapshot for loaders started together by the same page.
            if (!currentRequest) {
                const box = document.createElement('div');
                box.id = 'adminApiLoading';
                box.className = 'alert alert-info';
                box.setAttribute('role', 'status');
                box.textContent = 'Memuat data admin…';
                document.querySelector('main')?.prepend(box);
                currentRequest = request('/api/admin/data');
            }
            try {
                const data = await currentRequest;
                document.getElementById('adminApiLoading')?.remove();
                document.getElementById('adminApiMessage')?.remove();
                badges(data);
                return data;
            } catch (error) {
                document.getElementById('adminApiLoading')?.remove();
                currentRequest = null;
                throw error;
            }
        },
        async save(action, data) {
            const result = await request('/api/admin/save', {
                method: 'POST',
                headers: {'Content-Type': 'application/json', 'X-CSRF-Token': document.querySelector('meta[name="admin-csrf"]').content},
                body: JSON.stringify({action, ...data})
            });
            currentRequest = null;
            return result;
        },
        status(value) { return value; },
        showError
    };
})();
