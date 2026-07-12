import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.store('confirmDialog', {
    show: false,
    message: '',
    resolve: null,

    ask(message) {
        this.message = message;
        this.show = true;

        return new Promise((resolve) => {
            this.resolve = resolve;
        });
    },

    confirm() {
        this.show = false;
        this.resolve?.(true);
    },

    cancel() {
        this.show = false;
        this.resolve?.(false);
    },
});

// Header cart icon opens this instead of navigating to /cart, so the shopper
// never leaves the page they're browsing. Item data is fetched on demand
// (not eagerly composed into every page) since most page loads never open it.
Alpine.store('cartDrawer', {
    open: false,
    loading: false,
    items: [],
    subtotal: '',
    count: 0,
    pendingIds: [],

    async show() {
        this.open = true;
        await this.refresh();
    },

    close() {
        this.open = false;
    },

    async refresh() {
        this.loading = true;
        try {
            const { data } = await window.axios.get('/cart/mini', { headers: { Accept: 'application/json' } });
            this.applyPayload(data);
        } finally {
            this.loading = false;
        }
    },

    // Guarded by pendingIds so a fast double-click on the same row can't fire
    // two overlapping requests whose responses could resolve out of order
    // and leave the displayed qty out of sync with the server.
    async updateQty(itemId, qty) {
        if (qty < 1 || this.pendingIds.includes(itemId)) return;
        this.pendingIds.push(itemId);
        try {
            const { data } = await window.axios.patch(`/cart/${itemId}`, { qty }, { headers: { Accept: 'application/json' } });
            this.applyPayload(data);
        } finally {
            this.pendingIds = this.pendingIds.filter((id) => id !== itemId);
        }
    },

    async remove(itemId) {
        if (this.pendingIds.includes(itemId)) return;
        this.pendingIds.push(itemId);
        try {
            const { data } = await window.axios.delete(`/cart/${itemId}`, { headers: { Accept: 'application/json' } });
            this.applyPayload(data);
        } finally {
            this.pendingIds = this.pendingIds.filter((id) => id !== itemId);
        }
    },

    applyPayload(data) {
        this.items = data.items;
        this.subtotal = data.subtotal;
        this.count = data.count;
    },
});

// Used as onsubmit="return confirmSubmit(event, 'ข้อความยืนยัน')" in place of
// the native confirm() dialog: blocks the submit, shows the styled modal,
// and resubmits the form once the user confirms.
window.confirmSubmit = function (event, message) {
    const form = event.target;
    event.preventDefault();

    Alpine.store('confirmDialog').ask(message).then((confirmed) => {
        if (confirmed) form.submit();
    });

    return false;
};

// Character-class variety alone scores "123456" as strong as any other 6+
// char digit string, even though it's one of the most guessed passwords in
// existence — so predictable strings are caught separately and force the
// score to 0 regardless of length/variety.
window.passwordStrengthIsPredictable = function (value) {
    const lower = value.toLowerCase();

    const commonPasswords = [
        'password', 'passw0rd', '123456', '1234567', '12345678', '123456789',
        '1234567890', 'qwerty', 'qwertyui', 'abc123', '111111', '000000',
        '123123', 'letmein', 'admin', 'iloveyou', 'welcome', 'monkey', 'dragon',
    ];
    if (commonPasswords.includes(lower)) return true;

    // Same character repeated throughout, e.g. "aaaaaa" or "111111".
    if (/^(.)\1+$/.test(lower)) return true;

    // Whole string is one ascending or descending run, e.g. "123456", "abcdef", "654321".
    if (lower.length >= 4) {
        let ascending = true;
        let descending = true;
        for (let i = 1; i < lower.length; i++) {
            const diff = lower.charCodeAt(i) - lower.charCodeAt(i - 1);
            if (diff !== 1) ascending = false;
            if (diff !== -1) descending = false;
        }
        if (ascending || descending) return true;
    }

    return false;
};

window.passwordStrengthScore = function (value) {
    if (!value) return 0;
    if (window.passwordStrengthIsPredictable(value)) return 0;

    let score = 0;
    if (value.length >= 8) score++;
    if (value.length >= 12) score++;
    if (/[a-z]/.test(value) && /[A-Z]/.test(value)) score++;
    if (/\d/.test(value)) score++;
    if (/[^A-Za-z0-9]/.test(value)) score++;

    return Math.min(score, 4);
};

window.passwordStrengthMeta = function (value) {
    const score = window.passwordStrengthScore(value);
    const labels = window.passwordStrengthLabels || ['ไม่ปลอดภัย', 'อ่อนแอ', 'พอใช้', 'ดี', 'แข็งแรงมาก'];
    const barColors = ['bg-red-400', 'bg-orange-400', 'bg-amber-400', 'bg-lime-500', 'bg-emerald-500'];
    const textColors = ['text-red-500', 'text-orange-500', 'text-amber-500', 'text-lime-600', 'text-emerald-600'];

    return {
        score,
        filled: Math.max(score, 1),
        label: labels[score],
        barColor: barColors[score],
        textColor: textColors[score],
    };
};

Alpine.start();
