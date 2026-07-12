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

Alpine.data('passwordStrength', () => ({
    password: '',

    get score() {
        const value = this.password;
        if (!value) return 0;

        let score = 0;
        if (value.length >= 8) score++;
        if (value.length >= 12) score++;
        if (/[a-z]/.test(value) && /[A-Z]/.test(value)) score++;
        if (/\d/.test(value)) score++;
        if (/[^A-Za-z0-9]/.test(value)) score++;

        return Math.min(score, 4);
    },

    get label() {
        const labels = window.passwordStrengthLabels || ['ไม่ปลอดภัย', 'อ่อนแอ', 'พอใช้', 'ดี', 'แข็งแรงมาก'];

        return labels[this.score];
    },

    get barColor() {
        return ['bg-gray-200', 'bg-red-400', 'bg-amber-400', 'bg-lime-500', 'bg-emerald-500'][this.score];
    },

    get textColor() {
        return ['text-gray-400', 'text-red-500', 'text-amber-500', 'text-lime-600', 'text-emerald-600'][this.score];
    },
}));

Alpine.start();
