(function () {
    if (!wox_chat || !wox_chat.phone) {
        return;
    }
    var phone = wox_chat.phone.replace(/[^0-9]/g, '');
    var message = encodeURIComponent(wox_chat.message || 'Hello');
    var link = document.querySelector('.wox-chat-button');
    if (!link) {
        return;
    }
    link.href = 'https://wa.me/' + phone + '?text=' + message;
    var position = wox_chat.position || 'right';
    var iconStyle = wox_chat.iconStyle || 'round';
    var size = parseInt(wox_chat.size, 10) || 60;
    var bottom = parseInt(wox_chat.bottom, 10) || 20;
    var sideOffset = parseInt(wox_chat.sideOffset, 10) || 20;
    link.className = 'wox-chat-button wox-chat-button--' + iconStyle + ' wox-chat-button--' + position;
    link.style.width = size + 'px';
    link.style.height = size + 'px';
    link.style.bottom = bottom + 'px';
    if (position === 'right') {
        link.style.left = '';
        link.style.right = sideOffset + 'px';
    } else {
        link.style.right = '';
        link.style.left = sideOffset + 'px';
    }
    var svg = link.querySelector('svg');
    if (svg) {
        var svgSize = Math.round(size * 0.5);
        svg.style.width = svgSize + 'px';
        svg.style.height = svgSize + 'px';
    }
})();
