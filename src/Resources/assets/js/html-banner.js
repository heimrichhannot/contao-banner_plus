document.addEventListener('DOMContentLoaded', function() {
    let iframes = document.querySelectorAll('.iframe-resized');
    iframes.forEach( iframe => {
        iframe.addEventListener('load', e => {
            e.target.style.height = e.target.contentWindow.document.body.scrollHeight + 'px';
            e.target.style.width = e.target.contentWindow.document.body.scrollWidth + 'px';
            e.target.style.border = 'none';
            e.target.style.overflow = 'hidden';
        });
    });
});