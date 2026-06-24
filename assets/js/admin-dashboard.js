(function () {
    const dashboard = document.querySelector('.wox-dashboard');
    if (!dashboard) return;

    function openReply(id) {
        const row = document.getElementById('wox-reply-' + id);
        if (!row) return;
        row.style.display = 'table-row';
        const textarea = row.querySelector('.wox-reply-text');
        if (textarea) textarea.focus();
        markRead(id);
    }

    function closeReply(id) {
        const row = document.getElementById('wox-reply-' + id);
        if (!row) return;
        row.style.display = 'none';
        const textarea = row.querySelector('.wox-reply-text');
        if (textarea) textarea.value = '';
        const err = row.querySelector('.wox-reply-error');
        if (err) { err.style.display = 'none'; err.textContent = ''; }
    }

    function markRead(id) {
        const btn = document.querySelector('.wox-reply-btn[data-id="' + id + '"]');
        if (!btn) return;
        const row = btn.closest('tr');
        if (row && row.classList.contains('wox-inbox-unread')) {
            row.classList.remove('wox-inbox-unread');
            const badge = document.querySelector('.wox-badge');
            if (badge) {
                const count = parseInt(badge.textContent, 10);
                badge.textContent = count > 1 ? count - 1 : '';
                if (badge.textContent === '') badge.style.display = 'none';
            }
        }
    }

    dashboard.addEventListener('click', function (e) {
        const btn = e.target.closest('.wox-reply-btn');
        if (btn) {
            e.preventDefault();
            const id = parseInt(btn.dataset.id, 10);
            openReply(id);
            return;
        }

        const cancel = e.target.closest('.wox-reply-cancel');
        if (cancel) {
            e.preventDefault();
            closeReply(parseInt(cancel.dataset.id, 10));
            return;
        }

        const send = e.target.closest('.wox-reply-send');
        if (!send) return;

        e.preventDefault();
        const id = parseInt(send.dataset.id, 10);
        const row = document.getElementById('wox-reply-' + id);
        if (!row) return;
        const textarea = row.querySelector('.wox-reply-text');
        const errEl = row.querySelector('.wox-reply-error');
        const spinner = row.querySelector('.wox-reply-spinner');
        if (!textarea) return;

        const text = textarea.value.trim();
        if (!text) {
            if (errEl) { errEl.textContent = 'Reply cannot be empty.'; errEl.style.display = 'inline'; }
            return;
        }

        send.disabled = true;
        if (errEl) errEl.style.display = 'none';
        if (spinner) spinner.style.display = 'inline';

        const formData = new FormData();
        formData.append('action', 'wox_reply_message');
        formData.append('conversation_id', id);
        formData.append('reply_text', text);
        formData.append('_wpnonce', woxDashboard.nonce);

        fetch(woxDashboard.ajaxUrl, {
            method: 'POST',
            body: formData,
        })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.success) {
                    closeReply(id);
                    updateStatusBadge(id, 'replied');
                } else {
                    if (errEl) { errEl.textContent = data.data || 'Failed to send.'; errEl.style.display = 'inline'; }
                }
            })
            .catch(function () {
                if (errEl) { errEl.textContent = 'Network error.'; errEl.style.display = 'inline'; }
            })
            .finally(function () {
                send.disabled = false;
                if (spinner) spinner.style.display = 'none';
            });
    });

    function updateStatusBadge(id, status) {
        const btn = document.querySelector('.wox-reply-btn[data-id="' + id + '"]');
        if (!btn) return;
        const row = btn.closest('tr');
        if (!row) return;
        const badge = row.querySelector('.wox-status-badge');
        if (badge) {
            badge.className = 'wox-status-badge wox-status-' + status;
            badge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
        }
    }
})();
