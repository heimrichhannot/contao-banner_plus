// import iFrameResize from '../../public/assets/iframeResizer';

class BannerPlusBundleBE {
    static init() {
        BannerPlusBundleBE.initIframeResizer();
    }

    static initIframeResizer() {
        iFrameResize({
            log: false,
            checkOrigin: false,
            heightCalculationMethod: 'documentElementOffset'
        }, '.iframe-resized');

        let iframes = document.querySelectorAll('.iframe-resized');
        iframes.forEach( iframe => {
            iframe.addEventListener('load', e => {
                e.target.style.height = `${e.target.contentWindow.document.documentElement.offsetHeight}px`;
                e.target.style.border = 'none';
            });
        });
    }
}



document.addEventListener('DOMContentLoaded', BannerPlusBundleBE.init);