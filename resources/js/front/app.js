import '../../css/front/app.css';
import countryCode from './countryCode';
import phone from './phone';
import.meta.glob(['../../images/front/**', '../../images/flags/**']);

document.addEventListener('alpine:init', () => {
    Alpine.store('countryCode', countryCode);
    Alpine.data('phone', phone);
});

window.toast = function (message, options = {}) {
    let description = '';
    let type = 'default';
    let position = 'top-center';
    let html = '';

    if (typeof options.description != 'undefined') description = options.description;
    if (typeof options.type != 'undefined') type = options.type;
    if (typeof options.position != 'undefined') position = options.position;
    if (typeof options.html != 'undefined') html = options.html;

    window.dispatchEvent(
        new CustomEvent('toast-show', {
            detail: {
                type: type,
                message: message,
                description: description,
                position: position,
                html: html,
            },
        }),
    );
};
