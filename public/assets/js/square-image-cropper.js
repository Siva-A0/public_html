(function () {
    if (window.initSquareImageCropper) {
        return;
    }

    function injectStyles() {
        if (document.getElementById('square-image-cropper-styles')) {
            return;
        }

        var style = document.createElement('style');
        style.id = 'square-image-cropper-styles';
        style.textContent = [
            '.square-cropper{display:grid;gap:12px;margin-top:12px}',
            '.square-cropper[hidden]{display:none!important}',
            '.square-cropper-stage{position:relative;width:min(100%,320px);aspect-ratio:1/1;overflow:hidden;border:1px solid #d7e1ec;border-radius:22px;background:linear-gradient(180deg,#f8fbfe 0%,#edf3f8 100%);touch-action:none;cursor:grab;box-shadow:inset 0 0 0 1px rgba(255,255,255,.4)}',
            '.square-cropper-stage.is-dragging{cursor:grabbing}',
            '.square-cropper-stage img{position:absolute;top:0;left:0;user-select:none;-webkit-user-drag:none;max-width:none;transform-origin:top left}',
            '.square-cropper-frame{position:absolute;inset:0;border-radius:22px;box-shadow:0 0 0 9999px rgba(15,23,42,.32) inset;pointer-events:none}',
            '.square-cropper-frame::after{content:"";position:absolute;inset:14px;border:2px solid rgba(255,255,255,.95);border-radius:18px;box-shadow:0 0 0 1px rgba(23,61,105,.16)}',
            '.square-cropper-controls{display:grid;gap:10px;width:min(100%,320px)}',
            '.square-cropper-slider-row{display:grid;grid-template-columns:auto 1fr auto;gap:12px;align-items:center;color:#35506f;font-size:13px;font-weight:700}',
            '.square-cropper-slider{width:100%}',
            '.square-cropper-actions{display:flex;gap:10px;flex-wrap:wrap}',
            '.square-cropper-btn{border:1px solid #d0dceb;border-radius:12px;padding:8px 12px;background:#fff;color:#21486d;font-size:13px;font-weight:700;cursor:pointer}',
            '.square-cropper-help{width:min(100%,320px);color:#6b819c;font-size:12px;line-height:1.5}',
            '.square-cropper-preview{width:min(100%,320px);aspect-ratio:1/1;object-fit:cover;border-radius:22px;border:1px solid #d7e1ec;background:#f4f7fb}',
            '.square-cropper-empty{width:min(100%,320px);padding:18px;border:1px dashed #d4dfeb;border-radius:18px;background:#f9fbfe;color:#6b819c;font-size:13px;text-align:center}'
        ].join('');
        document.head.appendChild(style);
    }

    function clamp(value, min, max) {
        return Math.min(Math.max(value, min), max);
    }

    window.initSquareImageCropper = function (options) {
        injectStyles();

        var fileInput = document.getElementById(options.fileInputId);
        var hiddenInput = document.getElementById(options.hiddenInputId);
        var stage = document.getElementById(options.stageId);
        var image = document.getElementById(options.imageId);
        var slider = document.getElementById(options.sliderId);
        var resetButton = document.getElementById(options.resetButtonId);
        var fileButton = options.fileButtonId ? document.getElementById(options.fileButtonId) : null;
        var fileName = options.fileNameId ? document.getElementById(options.fileNameId) : null;
        var preview = options.previewId ? document.getElementById(options.previewId) : null;
        var emptyState = options.emptyStateId ? document.getElementById(options.emptyStateId) : null;
        var wrapper = options.wrapperId ? document.getElementById(options.wrapperId) : null;
        var form = options.formId ? document.getElementById(options.formId) : (fileInput ? fileInput.form : null);

        if (!fileInput || !hiddenInput || !stage || !image || !slider || !resetButton || !form) {
            return;
        }

        var stageSize = 320;
        var naturalWidth = 0;
        var naturalHeight = 0;
        var baseScale = 1;
        var zoom = 1;
        var x = 0;
        var y = 0;
        var objectUrl = '';
        var hasLoadedSource = false;
        var isDragging = false;
        var startPointerX = 0;
        var startPointerY = 0;
        var startX = 0;
        var startY = 0;

        function revokeObjectUrl() {
            if (objectUrl) {
                URL.revokeObjectURL(objectUrl);
                objectUrl = '';
            }
        }

        function setWrapperVisibility(visible) {
            if (wrapper) {
                wrapper.hidden = !visible;
            }
        }

        function getDisplayedWidth() {
            return naturalWidth * baseScale * zoom;
        }

        function getDisplayedHeight() {
            return naturalHeight * baseScale * zoom;
        }

        function constrainPosition() {
            var displayedWidth = getDisplayedWidth();
            var displayedHeight = getDisplayedHeight();
            var minX = Math.min(0, stageSize - displayedWidth);
            var minY = Math.min(0, stageSize - displayedHeight);
            x = clamp(x, minX, 0);
            y = clamp(y, minY, 0);
        }

        function render() {
            if (!naturalWidth || !naturalHeight) {
                image.style.display = 'none';
                if (emptyState) {
                    emptyState.hidden = false;
                }
                if (preview) {
                    preview.hidden = false;
                }
                setWrapperVisibility(Boolean(preview && preview.getAttribute('src')));
                return;
            }

            constrainPosition();
            image.style.display = 'block';
            image.style.width = getDisplayedWidth() + 'px';
            image.style.height = getDisplayedHeight() + 'px';
            image.style.transform = 'translate(' + x + 'px,' + y + 'px)';

            if (emptyState) {
                emptyState.hidden = true;
            }
            if (preview) {
                preview.hidden = true;
            }
            setWrapperVisibility(true);
        }

        function resetPosition() {
            if (!naturalWidth || !naturalHeight) {
                return;
            }
            zoom = 1;
            slider.value = '1';
            baseScale = Math.max(stageSize / naturalWidth, stageSize / naturalHeight);
            x = (stageSize - getDisplayedWidth()) / 2;
            y = (stageSize - getDisplayedHeight()) / 2;
            constrainPosition();
            render();
        }

        function loadImageSource(src, shouldExport) {
            if (!src) {
                naturalWidth = 0;
                naturalHeight = 0;
                hasLoadedSource = false;
                hiddenInput.value = '';
                render();
                return;
            }

            image.onload = function () {
                naturalWidth = image.naturalWidth || 0;
                naturalHeight = image.naturalHeight || 0;
                hasLoadedSource = shouldExport === true;
                hiddenInput.value = '';
                resetPosition();
            };
            image.src = src;
        }

        function exportCrop() {
            if (!hasLoadedSource || !naturalWidth || !naturalHeight) {
                return '';
            }

            var outputSize = 720;
            var ratio = outputSize / stageSize;
            var canvas = document.createElement('canvas');
            canvas.width = outputSize;
            canvas.height = outputSize;
            var context = canvas.getContext('2d');
            context.imageSmoothingQuality = 'high';
            context.drawImage(
                image,
                x * ratio,
                y * ratio,
                getDisplayedWidth() * ratio,
                getDisplayedHeight() * ratio
            );
            return canvas.toDataURL('image/png');
        }

        if (fileButton) {
            fileButton.addEventListener('click', function () {
                fileInput.click();
            });
        }

        fileInput.addEventListener('change', function () {
            hiddenInput.value = '';
            revokeObjectUrl();

            if (!fileInput.files || !fileInput.files[0]) {
            if (fileName) {
                fileName.value = 'No new file selected';
            }
            loadImageSource(preview ? preview.getAttribute('src') : '', true);
            return;
        }

            if (fileName) {
                fileName.value = fileInput.files[0].name;
            }

            objectUrl = URL.createObjectURL(fileInput.files[0]);
            loadImageSource(objectUrl, true);
        });

        slider.addEventListener('input', function () {
            var nextZoom = parseFloat(slider.value || '1');
            if (!naturalWidth || !naturalHeight || !isFinite(nextZoom)) {
                return;
            }

            var previousWidth = getDisplayedWidth();
            var previousHeight = getDisplayedHeight();
            var centerX = (stageSize / 2) - x;
            var centerY = (stageSize / 2) - y;
            var ratioX = previousWidth > 0 ? centerX / previousWidth : 0.5;
            var ratioY = previousHeight > 0 ? centerY / previousHeight : 0.5;

            zoom = nextZoom;
            var nextWidth = getDisplayedWidth();
            var nextHeight = getDisplayedHeight();
            x = (stageSize / 2) - (nextWidth * ratioX);
            y = (stageSize / 2) - (nextHeight * ratioY);
            render();
        });

        resetButton.addEventListener('click', function () {
            resetPosition();
        });

        stage.addEventListener('pointerdown', function (event) {
            if (!naturalWidth || !naturalHeight) {
                return;
            }
            isDragging = true;
            startPointerX = event.clientX;
            startPointerY = event.clientY;
            startX = x;
            startY = y;
            stage.classList.add('is-dragging');
            if (stage.setPointerCapture) {
                stage.setPointerCapture(event.pointerId);
            }
        });

        stage.addEventListener('pointermove', function (event) {
            if (!isDragging) {
                return;
            }
            x = startX + (event.clientX - startPointerX);
            y = startY + (event.clientY - startPointerY);
            render();
        });

        function stopDragging(event) {
            if (!isDragging) {
                return;
            }
            isDragging = false;
            stage.classList.remove('is-dragging');
            if (event && stage.releasePointerCapture) {
                try {
                    stage.releasePointerCapture(event.pointerId);
                } catch (e) {}
            }
        }

        stage.addEventListener('pointerup', stopDragging);
        stage.addEventListener('pointercancel', stopDragging);
        stage.addEventListener('lostpointercapture', stopDragging);

        form.addEventListener('submit', function () {
            var croppedData = exportCrop();
            if (croppedData) {
                hiddenInput.value = croppedData;
            }
        });

        var initialSource = options.initialImageUrl || (preview ? preview.getAttribute('src') : '');
        if (initialSource) {
            if (fileName && !fileName.value) {
                fileName.value = 'Current photo loaded';
            }
            loadImageSource(initialSource, true);
        } else {
            setWrapperVisibility(false);
        }
    };
})();
