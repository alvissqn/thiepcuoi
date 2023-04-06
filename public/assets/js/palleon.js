/*jshint esversion: 9, undef: false, unused: false */
(function($) {
    "use strict";

    $.fn.palleon = function (options) {
        var selector = $(this);
        var windowWidth = document.body.clientWidth;
        var f_can_arr = [];
        // Default settings
        var settings = $.extend({
            baseURL: "./",
            fontFamily: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",
            fontSize: 60,
            fontWeight: 'normal',
            fontStyle: 'normal',
            canvasColor: 'transparent',
            fill: '#000',
            stroke: '#fff',
            strokeWidth: 0,
            textBackgroundColor: 'rgba(255,255,255,0)',
            textAlign: 'left',
            lineHeight: 1.2,
            borderColor: '#000',
            borderDashArray: [4, 4],
            borderOpacityWhenMoving: 0.5,
            borderScaleFactor: 2,
            editingBorderColor: 'rgba(0,0,0,0.5)',
            cornerColor: '#fff',
            cornerSize: 12,
            cornerStrokeColor: '#000',
            cornerStyle: 'circle',
            transparentCorners: false,
            cursorColor: '#000',
            cursorWidth: 2,
            enableGLFiltering: true,
            textureSize: 4096,
            customFunctions: function() {},
            saveTemplate: function() {},
            saveImage: function() {}
        }, options);

        // Define Variables
        var c = '',
            canvasLoading = false,
            canvasWrap = '',
            canvasOld = null,
            mode = 'none',
            img = '',
            imgurl = '',
            originalWidth = {},
            originalHeight = {},
            rotate = 0,
            scaleX = 1,
            scaleY = 1,
            originX = 'left',
            originY = 'top',
            canvas = '',
            filters = [],
            clipPath = '',
            overlay = '',
            brush = '',
            brushShadow = '',
            duotoneFilter = '',
            timeOut = 0,
            mmediaLibraryMode = 'add-to-canvas',
            shapeTypes = ['circle', 'square', 'rectangle', 'triangle', 'ellipse', 'trapezoid', 'emerald', 'star'],
            resizableShapeTypes = ['square', 'rectangle', 'triangle'],
            webSafeFonts = [
                ['Helvetica Neue', '\'Helvetica Neue\', \'Helvetica\', \'Arial\', sans-serif'],
                ['Impact', 'Impact, Charcoal, sans-serif'],
                ['Georgia', 'Georgia, serif'],
                ['Palatino Linotype', '\'Palatino Linotype\', \'Book Antiqua\', Palatino, serif'],
                ['Times New Roman', '\'Times New Roman\', Times, serif'],
                ['Arial', 'Arial, Helvetica, sans-serif'],
                ['Arial Black', '\'Arial Black\', Gadget, sans-serif'],
                ['Comic Sans', '\'Comic Sans MS\', cursive, sans-serif'],
                ['Lucida Sans', '\'Lucida Sans Unicode\', \'Lucida Grande\', sans-serif'],
                ['Tahoma', 'Tahoma, Geneva, sans-serif'],
                ['Trebuchet', '\'Trebuchet MS\', Helvetica, sans-serif'],
                ['Verdana', 'Verdana, Geneva, sans-serif'],
                ['Courier New', '\'Courier New\', Courier, monospace'],
                ['Lucida Console', '\'Lucida Console\', Monaco, monospace'],
            ];

        /* Initialize Plugins */
        selector.find(".crop-custom").css('display', 'none');

        /* Load Material Icons */
        var materialIcons = new FontFaceObserver("Material Icons");
        materialIcons.load(null, 10000).then(function() {
            $('#palleon').find('#palleon-main-loader').fadeOut(200);
            }).catch(function(e) {
            $('#palleon').find('#palleon-main-loader').hide();
        });

        /* LazyLoad */
        var lazyLoadInstance = new LazyLoad({
            callback_error: (img) => {
                img.setAttribute("src", settings.baseURL + "assets/placeholder.png");
                $(img).parent().css('min-height', 'auto');
                $(img).parent().find('.palleon-img-loader').remove();
            },
            callback_loaded: (img) => {
                $(img).parent().css('min-height', 'auto');
                $(img).parent().find('.palleon-img-loader').remove();
            }
        });

        // Populate Websafe Fonts
        for (var i = 0; i < webSafeFonts.length; i++) {
            selector.find('#websafe-fonts').append($('<option class="websafe-font"></option>').attr("value", webSafeFonts[i][1]).text(webSafeFonts[i][0]));
        }

        // Populate Google Fonts
        $.getJSON(settings.baseURL + 'json/google-fonts.json', function(fonts) {
            for (var i = 0; i < fonts.items.length; i++) {
                selector.find('#google-fonts').append($('<option class="google-font"></option>').attr("value", fonts.items[i].family).text(fonts.items[i].family));
            }
        });

        // Populate Material Icons
        $.getJSON(settings.baseURL + 'json/material-icons.json', function(fonts) {
            for (var i = 0; i < fonts.categories.length; i++) {
                var item = fonts.categories[i];
                for (var ii = 0; ii < item.icons.length; ii++) {
                    var url = settings.baseURL + 'files/icons/' + item.icons[ii].group_id + '/' + item.icons[ii].ligature;
                    selector.find('#palleon-icons .palleon-grid').append('<div class="palleon-element add-element" data-elsource="' + url + '" data-loader="no" title="' + item.icons[ii].name + '">' + '<span class="material-icons">' + item.icons[ii].ligature + '</div>');
                }
            }
        });

        // Select2
        selector.find('.palleon-select.palleon-select2').select2({
            theme: "dark",
            width: "100%",
            templateSelection: select2format,
            templateResult: select2format,
            allowHtml: true
        });

        // Spectrum
        let colorpicker1 = selector.find(".palleon-colorpicker.disallow-empty").spectrum({
            allowEmpty: false,
            showInitial: true,
            chooseText: 'Áp dụng',
            cancelText: 'Hủy',
            move: function() {
                colorPickerSetColor(this);
            },
            hide: function() {
                colorPickerSetColor(this);
            }
        });
        let colorpicker2 = selector.find(".palleon-colorpicker.allow-empty").spectrum({
            allowEmpty: true,
            showInitial: false,
            chooseText: 'Áp dụng',
            cancelText: 'Hủy',
            move: function() {
                colorPickerSetColor(this);
            },
            hide: function() {
                colorPickerSetColor(this);
            }
        });

        function colorPickerSetColor(sefl) {
            switch( $(sefl).attr('id') ){
                case 'palleon-shape-color':
                case 'shape-outline-color':
                    setShapeColor(sefl, false);
                    break;

                    case 'palleon-frame-color':
                        setFrameColor(sefl, false);
                    break;

                    case 'palleon-element-color':
                        setElementColor(sefl, false);
                    break;

                    case 'palleon-text-color':
                    case 'palleon-outline-color':
                    case 'palleon-text-background':
                        setTextColor(sefl, false);
                    break;

                    case 'img-border-color':
                        setImgBorderColor(sefl, false);
                    break;

                    case 'custom-image-background':
                        setCanvasBG(sefl, false);
                    break;

                    case 'custom-background':
                        setContainerBG(sefl, false);
                    break;
            }
        }

        // Toastr
        toastr.options.closeButton = true;
        toastr.options.positionClass = 'toast-bottom-left';
        toastr.options.progressBar = true;
        toastr.options.newestOnTop = true;
        toastr.options.showEasing = 'swing';
        toastr.options.hideEasing = 'linear';
        toastr.options.closeEasing = 'linear';


        // Pagination
        function setPagination(target) {
            var items = target.find('>*');
            var num = items.length;
            var perPage = parseInt(target.data('perpage'));
            if (num > perPage) {
                items.slice(perPage).hide();
                var paginationDiv = '<div id="' + target.attr('id') + '-pagination' + '" class="palleon-pagination"></div>';
                target.after(paginationDiv);
                selector.find('#' + target.attr('id') + '-pagination').pagination({
                    items: num,
                    itemsOnPage: perPage,
                    prevText: '<span class="material-icons">navigate_before</span>',
                    nextText: '<span class="material-icons">navigate_next</span>',
                    displayedPages: 3,
                    onPageClick: function (pageNumber, event) {
                        if (typeof event !== "undefined") {
                            event.preventDefault();
                        }
                        var showFrom = perPage * (pageNumber - 1);
                        var showTo = showFrom + perPage;
                        items.hide().slice(showFrom, showTo).show();
                    }
                });
                selector.find('#' + target.attr('id') + '-pagination').pagination('selectPage', 1);
            }
        }

        selector.find('.paginated').each(function() {
            setPagination($(this));
        });

        // Dataurl to blob
        function dataURLtoBlob(dataurl) {
            var arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
                bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
            while(n--){
                u8arr[n] = bstr.charCodeAt(n);
            }
            return new Blob([u8arr], {type:mime});
        }

        // Convert to data url
        function convertToDataURL(url, callback) {
            return new Promise(function(resolve, reject){
                let xhr = new XMLHttpRequest();
                xhr.onload = function() {
                    let reader = new FileReader();
                    reader.onloadend = function() {
                        callback(reader.result);
                        resolve(reader.result);
                    };
                    reader.readAsDataURL(xhr.response);
                };
                xhr.open('GET', url);
                xhr.responseType = 'blob';
                xhr.send();
            });
        }

        /* Open Panel */
        function openPanel() {
            selector.removeClass('panel-closed');
            selector.find(".palleon-icon-menu-btn").removeClass('active');
            selector.find("#palleon-icon-menu").removeClass('closed');
            selector.find("#palleon-toggle-left").removeClass('closed');
            selector.find("#palleon-toggle-left").find(".material-icons").html('chevron_left');
            selector.find("#palleon-icon-panel").show();
        }

        /* Close Panel */
        function closePanel() {
            selector.addClass('panel-closed');
            selector.find(".palleon-icon-menu-btn").removeClass('active');
            selector.find("#palleon-icon-menu").addClass('closed');
            selector.find("#palleon-toggle-left").addClass('closed');
            selector.find("#palleon-toggle-left").find(".material-icons").html('chevron_right');
            selector.find("#palleon-icon-panel").hide();
        }

        /* Left Panel Toggle */
        selector.find("#palleon-toggle-left").on("click", function () {
            if ($(this).hasClass('closed')) {
                openPanel();
            } else {
                closePanel();
            }
        });

        /* Right Panel Toggle */
        selector.find("#palleon-toggle-right").on("click", function () {
            if ($(this).hasClass('closed')) {
                selector.removeClass('layers-closed');
                $(this).removeClass('closed');
                $(this).find(".material-icons").html('chevron_right');
                selector.find("#palleon-right-col").show();
            } else {
                selector.addClass('layers-closed');
                $(this).addClass('closed');
                $(this).find(".material-icons").html('chevron_left');
                selector.find("#palleon-right-col").hide();
            }
        });

        selector.find(".palleon-toggle-right").on("click", function (e) {
            e.preventDefault();
            selector.find("#palleon-toggle-right").trigger('click');
        });

        /* Close panels if needed */
        if (windowWidth <= 1200) {
            selector.find("#palleon-toggle-right").trigger('click');
            selector.find("#palleon-toggle-left").trigger('click');
        }

        /* Icon Button */
        selector.find(".palleon-icon-menu-btn").on("click", function () {
            if ($(this).data('target')) {
                if ($(this).hasClass('active')) {
                    closePanel();
                } else {
                    openPanel();
                    $(this).addClass('active');
                    selector.find('.palleon-icon-panel-content').addClass('panel-hide');
                    selector.find($(this).data('target')).removeClass('panel-hide');
                }
            }
            if ($(this).attr('id') == 'palleon-btn-elements') {
                selector.find('#palleon-all-elements-open').trigger('click');
            }
        });

        /* Dropdown Menu */
        selector.find('.palleon-dropdown-wrap').on('click', function() {
            if ($(this).hasClass('opened')) {
                $(this).removeClass('opened');
                $(this).find('.palleon-dropdown').hide();
            } else {
                $(this).addClass('opened');
                $(this).find('.palleon-dropdown').show();
            }
        });

        /* Accordion */
        selector.find(".palleon-icon-panel-content ul.palleon-accordion > li > a").on("click", function (e) {
            e.preventDefault();
            var parent = $(this).parent().parent();
            if ($(this).parent().hasClass('opened')) {
                parent.find('li').removeClass('opened');
            } else {
                parent.find('li').removeClass('opened');
                $(this).parent().addClass('opened');
            }
        });

        /* Lock/Unlock Button */
        selector.find(".palleon-lock-unlock").on("click", function () {
            if ($(this).hasClass('active')) {
                $(this).removeClass('active');
                $(this).find('.material-icons').html('lock_open');
            } else {
                $(this).addClass('active');
                $(this).find('.material-icons').html('lock');
            }
        });

        /* Rangeslider */
        selector.find(".palleon-slider").on("input", function () {
            var wrapper = $(this).parent().parent();
            wrapper.find('.slider-label span').html($(this).val());
            selector.find('span.tm-count-zoom').html($(this).val());
        });

        /* Toggle conditional fields */
        selector.find('input[type="checkbox"]').on("change", function () {
            if ($(this).data('conditional')) {
                if ($(this).is(":checked")) {
                    selector.find($(this).data('conditional')).removeClass('d-none');
                } else {
                    selector.find($(this).data('conditional')).addClass('d-none');
                }
            }
        });

        /* Tabs */
        selector.find('.palleon-tabs-menu li').on('click', function () {
            var target = $(this).data('target');
            var wrapper = $(this).parent().parent();
            wrapper.find('> .palleon-tab').removeClass('active');
            $(target).addClass('active');
            wrapper.find('> .palleon-tabs-menu li').removeClass('active');
            $(this).addClass('active');
        });

        /* Numeric validation */
        selector.find('input[type="number"],.numeric-field').bind('input paste keyup keydown', function(){
            this.value = this.value.replace(/(?!^-)[^0-9.]/g, "").replace(/(\..*)\./g, '$1');
            if ($(this).data('max') && (this.value > $(this).data('max'))) {
                this.value = $(this).data('max');
            }
            if ($(this).data('min') && (this.value < $(this).data('min'))) {
                this.value = $(this).data('min');
            }
        });

        /* Numeric Plus */
        selector.find('.palleon-counter .counter-plus').on('click', function() {
            var input = $(this).parent().find('input.palleon-form-field');
            var val = parseInt(input.val()) + parseInt(input.data('step'));
            if (input.data('max') && (val > input.data('max'))) {
                val = input.data('max');
            }
            if (input.data('min') && (val < input.data('min'))) {
                val = input.data('min');
            }
            if (val < 0) {
                val = 0;
            }
            input.val(val);
            if ($(this).attr('id') == 'palleon-img-zoom-in') {
                adjustZoom(val, true);
            }
        });

        /* Numeric Minus */
        selector.find('.palleon-counter .counter-minus').on('click', function() {
            var input = $(this).parent().find('input.palleon-form-field');
            var val = parseInt(input.val()) - parseInt(input.data('step'));
            if (input.data('max') && (val > input.data('max'))) {
                val = input.data('max');
            }
            if (input.data('min') && (val < input.data('min'))) {
                val = input.data('min');
            }
            if (val < 0) {
                val = 0;
            }
            input.val(val);
            if ($(this).attr('id') == 'palleon-img-zoom-out') {
                adjustZoom(val, true);
            }
        });

        // Set Fabric Settings
        fabric.enableGLFiltering = settings.enableGLFiltering;
        fabric.textureSize = parseInt(settings.textureSize);
        fabric.Object.prototype.borderColor = settings.borderColor;
        fabric.Object.prototype.borderDashArray = settings.borderDashArray;
        fabric.Object.prototype.borderOpacityWhenMoving = settings.borderOpacityWhenMoving;
        fabric.Object.prototype.borderScaleFactor = settings.borderScaleFactor;
        fabric.Object.prototype.editingBorderColor = settings.editingBorderColor;
        fabric.Object.prototype.cornerColor = settings.cornerColor;
        fabric.Object.prototype.cornerSize = settings.cornerSize;
        fabric.Object.prototype.cornerStrokeColor = settings.cornerStrokeColor;
        fabric.Object.prototype.cornerStyle = settings.cornerStyle;
        fabric.Object.prototype.transparentCorners = settings.transparentCorners;
        fabric.Object.prototype.cursorColor = settings.cursorColor;
        fabric.Object.prototype.cursorWidth = settings.cursorWidth;
        fabric.Object.prototype.strokeUniform = true;
        fabric.Group.prototype.padding = 0;
        fabric.Object.prototype.erasable = false;

        /**
        * Add custom button
        * */
        let iconList = {
            'autorenew': "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' height='24' viewBox='0 0 24 24' width='24'%3E%3Cpath d='M0 0h24v24H0z' fill='none'/%3E%3Cpath fill='white' d='M12 6v3l4-4-4-4v3c-4.42 0-8 3.58-8 8 0 1.57.46 3.03 1.24 4.26L6.7 14.8c-.45-.83-.7-1.79-.7-2.8 0-3.31 2.69-6 6-6zm6.76 1.74L17.3 9.2c.44.84.7 1.79.7 2.8 0 3.31-2.69 6-6 6v-3l-4 4 4 4v-3c4.42 0 8-3.58 8-8 0-1.57-.46-3.03-1.24-4.26z'/%3E%3C/svg%3E",
            'delete': "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' height='24' viewBox='0 0 24 24' width='24'%3E%3Cpath d='M0 0h24v24H0z' fill='none'/%3E%3Cpath fill='white' d='M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z'/%3E%3C/svg%3E",
            'content_copy': "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' height='50' width='50' viewBox='0 0 24 24'%3E%3Cpath d='M0 0h24v24H0z' fill='none'/%3E%3Cpath fill='white' d='M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z'/%3E%3C/svg%3E"
        };
        let iconImg = {};
        $.each(iconList, function(name, src){
            let img = new Image();
            img.src = src
            iconImg[name] = img;
        });

        /* Delete object */
        let deleteBtnOption = {
            x: 0,
            y: -0.5,
            offsetY: -25,
            offsetX: 22,
            cursorStyle: 'pointer',
            render: renderIcon(iconImg['delete']),
            cornerSize: 22,
            mouseUpHandler: function(eventData, transform) {
                let id = transform.target.id
                selector.find("#palleon-layers-"+canvasId+" #" + id).find("a.delete-layer").click();
            },
        };
        let cloneBtnOption = {
            x: 0,
            y: -0.5,
            offsetY: -25,
            offsetX: -22,
            cursorStyle: 'pointer',
            render: renderIcon(iconImg['content_copy']),
            cornerSize: 22,
            mouseUpHandler: function(eventData, transform) {
                let id = transform.target.id
                selector.find("#palleon-layers-"+canvasId+" #" + id).find("a.duplicate-layer").click();
            }
        };
        fabric.Object.prototype.controls.mtr.visible = false;
        let originalControl = fabric.Object.prototype.controls.mtr;
        let rotateBtnOption = {
            x: 0,
            y: 0.5,
            offsetY: 35,
            offsetX: 0,
            cursorStyle: 'pointer',
            render: renderIcon(iconImg['autorenew']),
            cornerSize: 22,
            actionHandler: originalControl.actionHandler,
            withConnection: true,
            actionName: 'rotate',
        };

        fabric.Object.prototype.controls.deleteControl = new fabric.Control(deleteBtnOption);
        fabric.Object.prototype.controls.clone = new fabric.Control(cloneBtnOption);
        fabric.Object.prototype.controls.rotateIcon = new fabric.Control(rotateBtnOption);

        fabric.Textbox.prototype.controls.deleteControl = new fabric.Control(deleteBtnOption)
        fabric.Textbox.prototype.controls.clone = new fabric.Control(cloneBtnOption)
        fabric.Textbox.prototype.controls.rotateIcon = new fabric.Control(rotateBtnOption)

        function renderIcon(icon, bg) {
            return function renderIcon(ctx, left, top, styleOverride, fabricObject) {
                var size = this.cornerSize;
                ctx.save();
                ctx.beginPath();
                ctx.arc(left, top, size / 2 + 3, 0, 5 * Math.PI, false);
                ctx.strokeStyle = "#8b3dff";
                ctx.stroke();
                ctx.fillStyle = "#8b3dff";
                ctx.fill();
                ctx.lineWidth = 0;
                ctx.restore();

                ctx.save();
                ctx.translate(left, top);
                ctx.rotate(fabric.util.degreesToRadians(fabricObject.angle));
                ctx.drawImage(icon, -size/2, -size/2, size, size);
                ctx.restore();
            }
        }


        // Custom Image Filters
        fabric.Image.filters.Shift = fabric.util.createClass(fabric.Image.filters.ColorMatrix, {
            type: 'Shift',
            matrix: [
                0,0,1,0,0,
			    0,1,0,0,0,
			    1,0,0,0,0,
			    0,0,0,1,0
            ],
            mainParameter: false,
            colorsOnly: true
        });

        let canvasItems = {}; // Save canvas item
        let canvasId = 1;

        /* Create Canvas */

        createCanvasElm(canvasId, function(html){
            selector.find('#palleon-canvas-page').append(html);
        });
        // UI Draggable
        canvasWrap = selector.find('#palleon-canvas-page > div:first-child');
        canvasWrap.draggable({ disabled: true });
        c = canvasWrap.find('.palleon-canvas-container-wrap > canvas')[0];
        canvas = new fabric.Canvas(c, {selection: true, selectionKey: "ctrlKey"});
        canvas.backgroundColor = settings.canvasColor;
        canvasItems[canvasId] = canvas;
        initCanvasEvent();
        updateCanvasPageNum();
        updateNameByCanvasItem();
        initLayerEvent();

        /**
         * START SECTION
         * Add by: KiemCoder
        * */

        /* Create and init canvas */
        function createCanvas(itemId, backgroundColor = null, callback) {
            disactiveAllObject();
            selector.find('.palleon-canvas-wrap-active').removeClass('palleon-canvas-wrap-active');
            selector.find('.palleon-history-list-active').removeClass('palleon-history-list-active');
            // Init new canvas tag
            canvasWrap = selector.find('#palleon-canvas-page').children(`div[data-id="${itemId}"]`);
            canvasWrap.addClass('palleon-canvas-wrap-active');
            canvasWrap.draggable({ disabled: true });
            canvasLoad('loading');
            c = canvasWrap.find('.palleon-canvas-container-wrap > canvas')[0];
            canvas = new fabric.Canvas(c, {selection: true, selectionKey: "ctrlKey"});
            canvas.backgroundColor = backgroundColor ?? settings.canvasColor;
            canvasItems[itemId] = canvas;
            canvasId = itemId;
            init('canvas', callback);
            initCanvasEvent();
            updateCanvasPageNum();
            selector.find('#palleon-layers-wrap').append(`<ul class="palleon-layers" id="palleon-layers-${itemId}"></ul>`);
            initLayerEvent();
            selector.find('#palleon-history-list').append(`<ul id="palleon-history-list-${itemId}" class="palleon-template-list palleon-history-list-active" data-max="50"></ul>`);
            return itemId;
        }

        /* Click add page */
        $('#palleon-btn-add-page').click(function () {
            let firstId = selector.find('#palleon-canvas-page > div:first-child').attr('data-id');
            getJSON(canvasItems[firstId], function(json){
                let itemId = (new Date).getTime();
                createCanvasElm(itemId, function(html){
                    selector.find('#palleon-canvas-page').append(html);
                });
                createCanvas(itemId, json.background);
                canvasLoad('loading', true);
                setTimeout(function(){
                    canvasLoad('complete', false);
                }, 500);
            });
        });

        /* Create canvas HTML element */
        function createCanvasElm(id, callback) {
            removeCanvasPage(id);
            let html = `
                <div class="palleon-canvas-wrap-item" data-id="${id}">
                    <div class="palleon-canvas-item-header">
                        <div class="palleon-canvas-item-page"></div>
                        <div class="palleon-canvas-item-name"><input maxlength="255" type="text" autocomplete="off" placeholder="Chưa đặt tên..."></div>
                        <div class="palleon-canvas-item-action">
                            <span class="material-icons" data-action="ruler">square_foot</span>
                            <span class="material-icons" data-action="setting">settings</span>
                            <span class="material-icons" data-action="move_up">expand_less</span>
                            <span class="material-icons" data-action="move_down">expand_more</span>
                            <span class="material-icons" data-action="duplicate">content_copy</span>
                            <span class="material-icons" data-action="delete">delete</span>
                        </div>
                    </div>
                    <!-- Canvas Loader -->
                    <div class="palleon-canvas-overlay"></div>
                    <!-- Canvas - Don't remove canvas element! -->
                    <div class="palleon-canvas-container-wrap"><canvas></canvas></div>
                </div>
            `;
            callback(html);
        }

        /* Remove canvas page */
        function removeCanvasPage(id) {
            let page = selector.find('#palleon-canvas-page > div[data-id="'+id+'"]');
            if( page.length ) page.remove();
            delete canvasItems[id];
        }

        /* Update page numeric */
        function updateCanvasPageNum() {
            let prefix = 'Trang';
            selector.find('#palleon-canvas-page > div').each(function(i, item){
               $(this).find('.palleon-canvas-item-page').text(`${prefix} ${(i + 1)}: `);
                $(this).find('.palleon-canvas-item-action > span[data-action="move_up"], .palleon-canvas-item-action > span[data-action="move_down"]').hide();
               if( $(this).prev().length ) {
                   $(this).find('.palleon-canvas-item-action > span[data-action="move_up"]').show();
               }
                if( $(this).next().length ) {
                    $(this).find('.palleon-canvas-item-action > span[data-action="move_down"]').show();
                }
            });
            canvasLoad('complete');
        }

        let canvasPageEl = $('#palleon-canvas-page');

        /* Click select to canvas page */
        let canvasFocusId = null;
        function focusCanvasPage(self) {
            if( !canvas.backgroundImage ) {
                return setTimeout(function(){
                    focusCanvasPage(self);
                }, 200);
            }
            if( $(self).hasClass('palleon-canvas-wrap-active') || canvasLoading || canvas.isDrawingMode ) return;
            //if ( $(e.target).closest(".palleon-canvas-item-action").length ) return;
            let itemId = $(self).attr('data-id');
            canvas = canvasItems[itemId];
            canvasId = itemId;
            canvasFocusId = canvasId;
            $('.palleon-layers').hide();
            $('#palleon-layers-'+canvasId).show();
            checkLayers();
            $('#palleon-history-list > ul').removeClass('palleon-history-list-active').hide();
            $('#palleon-history-list-'+canvasId).addClass('palleon-history-list-active').show();
            selector.find('.palleon-canvas-wrap-active').removeClass('palleon-canvas-wrap-active');
            $(self).addClass('palleon-canvas-wrap-active');
            updateNameByCanvasItem();
            setDimentions({
                width: canvas.backgroundImage.width,
                height: canvas.backgroundImage.height
            });
            disactiveAllObject();
        }

        /* Enter page name */
        canvasPageEl.on('keyup', '.palleon-canvas-item-name input', function(){
            updateNameByCanvasItem();
        });

        /* Update name by canvas item */
        function updateNameByCanvasItem() {
            setFileName($('.palleon-canvas-wrap-active .palleon-canvas-item-name input').val(), '');
        }

        /* Click action */
        canvasPageEl.on('click', '.palleon-canvas-item-action > span', function(){
            let canvasWrapItem = $(this).parents('.palleon-canvas-wrap-item');
            let itemId = canvasWrapItem.attr('data-id');
            let canvasItem = canvasItems[itemId];
            let action = $(this).attr('data-action');
            let newItemId = (new Date).getTime();
            switch(action) {
                case 'delete': // Remove page
                    if( canvasPageEl.children().length <= 1 ) {
                        alert('Không thể xóa trang mặc định');
                    }else{
                        canvasLoad('loading', true);
                        delete canvasItems[itemId];
                        canvasWrapItem.remove();
                        setTimeout(function(){
                            canvasLoad('complete', false);
                        }, 500);
                    }
                    break;
                case 'duplicate': // Remove page
                    createCanvasElm(newItemId, function(html){
                        selector.find('#palleon-canvas-page').children(`div[data-id="${itemId}"]`).after(html);
                    });
                    createCanvas(newItemId);
                    duplcatePage(canvasItem);
                    break;
                case 'move_up': // Move up
                    createCanvasElm(newItemId, function(html){
                        canvasWrapItem.prev().before(html);
                    });
                    createCanvas(newItemId);
                    duplcatePage(canvasItem, canvasWrapItem);
                    break;
                case 'move_down': // Move down
                    createCanvasElm(newItemId, function(html){
                        canvasWrapItem.next().after(html);
                    });
                    createCanvas(newItemId);
                    duplcatePage(canvasItem, canvasWrapItem);
                    break;

                case 'setting': // Setting
                    $('#palleon-show-canvas-option').click();
                    break;

                case 'ruler': // Ruler
                    let ruleOptValue = $('#custom-ruler').val();
                    if( ruleOptValue == 1 ) {
                        // Off
                        $('#custom-ruler > option[value="0"]').prop('selected', true);
                    }else{
                        // On
                        $('#custom-ruler > option[value="1"]').prop('selected', true);
                        toastr.success("Bạn có thể kéo thước để tạo đường kẻ <br> Nhấn vào ô vuông màu trắng để xóa đường kẻ", "Đã bật thước");
                    }
                    initRuler();
                    break;
            }
            updateCanvasPageNum();
        });

        /* Duplicate page */
        function duplcatePage(canvasItem, removeEl = null) {
            canvasLoad('loading', true);
            setTimeout(function(){
                getJSON(canvasItem, function(json){
                    loadJSON(json, function(){
                        addToHistory('<span class="material-icons">flag</span>' + palleonParams.started);
                        adjustZoom( $('#palleon-img-zoom').val() );
                    });
                    if( removeEl ) removeEl.remove();
                    updateCanvasPageNum();
                    canvasLoad('complete', false);
                });
            }, 220);
        }

        /* Click unselect */
        selector.on('click', '#palleon-content', function(e){
            if( !$(e.target).is("canvas") ) {
                disactiveAllObject();
            }
        });
        selector.on('click', '#palleon-top-bar', function(e){
            disactiveAllObject();
        });

        /* Click create new */
        $('#palleon-new').click(function() {
            $('#modal-add-new .palleon-modal-close').show();
        });

        /* Unselect all object */
        function disactiveAllObject(){
            $.each(canvasItems, function(i, item){
                item.discardActiveObject().renderAll();
            });
            selector.find('#palleon-layers-wrap li.active').removeClass('active');
        }

        /* Get canvas JSON */
        function getJSON(canvasItem, callback) {
            let json = canvasItem.toJSON(['id', 'objectType','gradientFill','roundedCorders','mode','selectable','lockMovementX','lockMovementY','lockRotation','crossOrigin']);
            convertToDataURL(json.backgroundImage.src, function(dataUrl) {
                json.backgroundImage.src = dataUrl;
                callback(json);
            });
        }

        /* Canvas is loading */
        function canvasLoad(status, isLoading = null) {
            if( isLoading != null ) canvasLoading = isLoading;
            let loadEl = $('#modal-loading');
            if( status == 'loading' ) {
                // Loading
                loadEl.show();
            }else{
                // Complete
                if( !canvasLoading ) loadEl.hide();
            }
        }

        /**
         * END SECTION
         * */


        /* Set File Name */
        function setFileName(fileName, fileExtention) {
            if (fileName == '') {
                fileName = new Date().getTime();
            }
            if (fileExtention == '') {
                fileExtention = 'jpeg';
            } else if (fileExtention == 'jpg') {
                fileExtention = 'jpeg';
            }

            selector.find('.palleon-file-name').val(fileName);
            selector.find('.palleon-file-name').data('default', fileName);
            selector.find('#palleon-save-img-format').val(fileExtention);
            selector.find('#palleon-save-img-format').trigger('change');
        }

        /* Init */
        function init(getMode, callback = null) {
            canvasLoad('loading');
            canvasWrap.css('visibility', 'visible');
            selector.find('.palleon-content-bar').css('visibility', 'visible');
            mode = getMode;
            if (canvas.backgroundImage) {
                filters = canvas.backgroundImage.filters;
            }
            // Temp Canvas
            if (mode == 'canvas') {
                selector.find('#palleon-canvas-color').trigger('change');
                var newCanvas = document.createElement("canvas");
                var canvas2 = new fabric.Canvas(newCanvas);
                var canvas2Width = parseInt(selector.find('#palleon-canvas-width').val());
                var canvas2Height = parseInt(selector.find('#palleon-canvas-height').val());
                if (canvas2Width == '') {
                    canvas2Width = 800;
                }
                if (canvas2Height == '') {
                    canvas2Height = 800;
                }
                let firstCanvas =  canvasItems[Object.keys(canvasItems)[0]];
                if( firstCanvas && firstCanvas.backgroundImage ) {
                    canvas2Width = firstCanvas.backgroundImage.width;
                    canvas2Height = firstCanvas.backgroundImage.height;
                }
                canvas2.setWidth(canvas2Width);
                canvas2.setHeight(canvas2Height);
                canvas2.backgroundColor = 'transparent';
                var imgData = canvas2.toDataURL({ format: 'png', enableRetinaScaling: false});
                var blob = dataURLtoBlob(imgData);
                var newurl = URL.createObjectURL(blob);
                selector.find('#palleon-canvas-img').attr("src",newurl);
                canvas2.dispose();
            }

            // Canvas Init
            selector.find('#palleon-canvas-img-wrap').imagesLoaded( function() {
                img = selector.find('#palleon-canvas-img')[0];
                imgurl = selector.find('#palleon-canvas-img').attr('src');
                originalWidth[canvasId] = img.width;
                originalHeight[canvasId] = img.height;

                // Display image dimentions
                setDimentions(img);

                canvas.setDimensions({width: originalWidth[canvasId], height: originalHeight[canvasId]});

                fabric.Image.fromURL(imgurl, function(img) {
                    canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas), {
                        objectType: 'BG',
                        mode: mode,
                        scaleX: scaleX,
                        scaleY: scaleY,
                        selectable: false,
                        lockMovementX: true,
                        lockMovementY: true,
                        lockRotation: true,
                        erasable: true
                    }, { crossOrigin: 'anonymous' });
                    setTimeout(function(){
                        reset();
                        addToHistory('<span class="material-icons">flag</span>' + palleonParams.started);
                        initCenteringGuidelines(canvas);
                        initAligningGuidelines(canvas);
                        initRuler();
                        adjustZoom();
                        adjustZoom( $('#palleon-img-zoom').val() );
                        modeCheck();
                        if( callback ) callback();
                        canvasLoad('complete');
                    }, 100);
                });
            });
        }

        if(selector.find('#palleon-canvas-img').attr('src') != '') {
            mode = 'image';
            var fileName = selector.find('#palleon-canvas-img').data('filename');
            var fileExtention = selector.find('#palleon-canvas-img').attr('src').match(/\.[0-9a-z]+$/i)[0].replace(/\./g, "");
            setFileName(fileName, fileExtention);
            init(mode);
        }
        modeCheck();

        /* Reset */
        function reset() {
            // Vars
            rotate = 0;
            scaleX = 1;
            scaleY = 1;
            originX = 'left';
            originY = 'top';
            if( !canvas.backgroundImage ) {
                return setTimeout(function(){
                    reset();
                }, 100);
            }
            if (!selector.find('#keep-data').is(":checked")) {
                canvas.backgroundImage.filters = [];
                selector.find('#palleon-adjust .conditional-settings').addClass('d-none');
                selector.find('#palleon-brightness').prop('checked', false);
                selector.find('#brightness').val(0);
                selector.find('#palleon-contrast').prop('checked', false);
                selector.find('#contrast').val(0);
                selector.find('#palleon-saturation').prop('checked', false);
                selector.find('#saturation').val(0);
                selector.find('#palleon-hue').prop('checked', false);
                selector.find('#hue').val(0);
                selector.find('#palleon-filters input[type=checkbox]').prop('checked', false);
                selector.find('#palleon-gamma').prop('checked', false);
                selector.find('#gamma-red').val(1);
                selector.find('#gamma-green').val(1);
                selector.find('#gamma-blue').val(1);
                selector.find('#palleon-blend-color').prop('checked', false);
                selector.find('#blend-color-mode').val('add');
                selector.find('#blend-color-color').spectrum("set", "#ffffff");
                selector.find('#blend-color-alpha').val(0.5);
                selector.find('#blend-color-alpha').parent().parent().find('.slider-label span').html(0.5);
                selector.find('#palleon-duotone-color').prop('checked', false);
                selector.find('#duotone-light-color').spectrum("set", "green");
                selector.find('#duotone-dark-color').spectrum("set", "blue");
                selector.find('#palleon-swap-colors').prop('checked', false);
                selector.find('#palleon-blur').prop('checked', false);
                selector.find('#blur').val(0);
                selector.find('#palleon-noise').prop('checked', false);
                selector.find('#noise').val(0);
                selector.find('#palleon-pixelate').prop('checked', false);
                selector.find('#pixelate').val(1);

                var objects = canvas.getObjects();
                objects.filter(element => element.objectType != 'BG').forEach(element => canvas.remove(element));
                selector.find('#palleon-layers-'+canvasId+' li').remove();
                checkLayers();
            } else {
                canvas.backgroundImage.filters = filters;
                canvas.backgroundImage.applyFilters();
            }

            canvas.fire('selection:cleared');
            canvas.requestRenderAll();
        }

        /* Adjust Filter Controls */
        function adjustFilterControls(filters) {
            // Reset
            selector.find('#palleon-brightness').prop('checked', false);
            selector.find('#palleon-contrast').prop('checked', false);
            selector.find('#palleon-saturation').prop('checked', false);
            selector.find('#palleon-hue').prop('checked', false);
            selector.find('#grayscale').prop('checked', false);
            selector.find('#sepia').prop('checked', false);
            selector.find('#brownie').prop('checked', false);
            selector.find('#blackwhite').prop('checked', false);
            selector.find('#vintage').prop('checked', false);
            selector.find('#kodachrome').prop('checked', false);
            selector.find('#polaroid').prop('checked', false);
            selector.find('#technicolor').prop('checked', false);
            selector.find('#invert').prop('checked', false);
            selector.find('#sharpen').prop('checked', false);
            selector.find('#emboss').prop('checked', false);
            selector.find('#palleon-gamma').prop('checked', false);
            selector.find('#palleon-blend-color').prop('checked', false);
            selector.find('#palleon-duotone-color').prop('checked', false);
            selector.find('#palleon-blur').prop('checked', false);
            selector.find('#palleon-noise').prop('checked', false);
            selector.find('#palleon-pixelate').prop('checked', false);

            // Get Values
            if (filters.length !== 0) {
                $.each(filters, function( index, val ) {
                    if (val.type == 'Brightness') {
                        selector.find('#palleon-brightness').prop('checked', true);
                        selector.find('#brightness').val(val.brightness);
                        selector.find('#brightness').parent().parent().find('.slider-label span').html(val.brightness);
                    } else if (val.type == 'Contrast') {
                        selector.find('#palleon-contrast').prop('checked', true);
                        selector.find('#contrast').val(val.brightness);
                        selector.find('#contrast').parent().parent().find('.slider-label span').html(val.contrast);
                    } else if (val.type == 'Saturation') {
                        selector.find('#palleon-saturation').prop('checked', true);
                        selector.find('#saturation').val(val.brightness);
                        selector.find('#saturation').parent().parent().find('.slider-label span').html(val.saturation);
                    } else if (val.type == 'HueRotation') {
                        selector.find('#palleon-hue').prop('checked', true);
                        selector.find('#hue').val(val.rotation);
                        selector.find('#hue').parent().parent().find('.slider-label span').html(val.rotation);
                    } else if (val.type == 'Grayscale') {
                        selector.find('#grayscale').prop('checked', true);
                    } else if (val.type == 'Sepia') {
                        selector.find('#sepia').prop('checked', true);
                    } else if (val.type == 'Brownie') {
                        selector.find('#brownie').prop('checked', true);
                    } else if (val.type == 'BlackWhite') {
                        selector.find('#blackwhite').prop('checked', true);
                    } else if (val.type == 'Vintage') {
                        selector.find('#vintage').prop('checked', true);
                    } else if (val.type == 'Kodachrome') {
                        selector.find('#kodachrome').prop('checked', true);
                    } else if (val.type == 'Polaroid') {
                        selector.find('#polaroid').prop('checked', true);
                    } else if (val.type == 'Technicolor') {
                        selector.find('#technicolor').prop('checked', true);
                    } else if (val.type == 'Invert') {
                        selector.find('#invert').prop('checked', true);
                    } else if (val.type == 'Convolute') {
                        if (val.matrix == '[0,-1,0,-1,5,-1,0,-1,0]') {
                            selector.find('#sharpen').prop('checked', true);
                        } else if (val.matrix == '[1,1,1,1,0.7,-1,-1,-1,-1]'){
                            selector.find('#emboss').prop('checked', true);
                        } else if (val.matrix == '[-1,0,1,-2,0,2,-1,0,1]'){
                            selector.find('#sobelX').prop('checked', true);
                        } else if (val.matrix == '[-1,-2,-1,0,0,0,1,2,1]'){
                            selector.find('#sobelY').prop('checked', true);
                        }
                    } else if (val.type == 'Gamma') {
                        selector.find('#palleon-gamma').prop('checked', true);
                        selector.find('#gamma-red').val(val.gamma[0]);
                        selector.find('#gamma-red').parent().parent().find('.slider-label span').html(val.gamma[0]);
                        selector.find('#gamma-green').val(val.gamma[1]);
                        selector.find('#gamma-green').parent().parent().find('.slider-label span').html(val.gamma[1]);
                        selector.find('#gamma-blue').val(val.gamma[2]);
                        selector.find('#gamma-blue').parent().parent().find('.slider-label span').html(val.gamma[2]);
                    } else if (val.type == 'BlendColor') {
                        selector.find('#palleon-blend-color').prop('checked', true);
                        selector.find('#blend-color-mode').val(val.mode);
                        selector.find('#blend-color-color').val(val.color);
                        selector.find('#blend-color-alpha').val(val.alpha);
                        selector.find('#blend-color-alpha').parent().parent().find('.slider-label span').html(val.alpha);
                    } else if (val.type == 'Composed') {
                        selector.find('#palleon-duotone-color').prop('checked', true);
                        selector.find('#duotone-light-color').val(val.subFilters[1].color);
                        selector.find('#duotone-dark-color').val(val.subFilters[2].color);
                    } else if (val.type == 'Blur') {
                        selector.find('#palleon-blur').prop('checked', true);
                        selector.find('#blur').val(val.blur);
                        selector.find('#blur').parent().parent().find('.slider-label span').html(val.blur);
                    } else if (val.type == 'Noise') {
                        selector.find('#palleon-noise').prop('checked', true);
                        selector.find('#noise').val(val.noise);
                        selector.find('#noise').parent().parent().find('.slider-label span').html(val.noise);
                    } else if (val.type == 'Pixelate') {
                        selector.find('#palleon-pixelate').prop('checked', true);
                        selector.find('#pixelate').val(val.blocksize);
                        selector.find('#pixelate').parent().parent().find('.slider-label span').html(val.blocksize);
                    }
                });
            }

            selector.find('#palleon-brightness').trigger('change');
            selector.find('#palleon-contrast').trigger('change');
            selector.find('#palleon-saturation').trigger('change');
            selector.find('#palleon-hue').trigger('change');
            selector.find('#palleon-gamma').trigger('change');
            selector.find('#palleon-blend-color').trigger('change');
            selector.find('#palleon-blur').trigger('change');
            selector.find('#palleon-noise').trigger('change');
            selector.find('#palleon-pixelate').trigger('change');
        }

        /* Adjust Mode */
        function modeCheck() {
            if (mode == 'none') {
                selector.find('#palleon-icon-menu, #palleon-icon-panel').css('pointer-events', 'none');
                selector.find('.palleon-keep').hide();
                selector.find('#modal-add-new').show();
                selector.find('#palleon-save, #palleon-export').prop('disabled', true);
            } else {
                canvasWrap.css('visibility', 'visible');
                selector.find('.palleon-content-bar').css('visibility', 'visible');
                selector.find('#palleon-icon-menu, #palleon-icon-panel').css('pointer-events', 'auto');
                selector.find('.palleon-keep').show();
                selector.find('#modal-add-new').hide();
                selector.find('#palleon-save, #palleon-export').prop('disabled', false);
            }
            if (mode == 'canvas') {
                selector.find('#palleon-resize-lock').removeClass('active');
                selector.find('#palleon-crop-lock').removeClass('active');
                selector.find('.hide-on-canvas-mode').hide();
            } else {
                selector.find('#palleon-resize-lock').addClass('active');
                selector.find('#palleon-crop-lock').addClass('active');
                selector.find('.hide-on-canvas-mode').show();
            }
        }

        /* MODAL */

        /* Modal Open */
        selector.find('.palleon-modal-open').on('click', function(e) {
            e.preventDefault();
            var target = $(this).data('target');
            selector.find('.palleon-modal').hide();
            selector.find(target).show();
        });

        /* Modal Close */
        selector.find('.palleon-modal-close').on('click', function(e) {
            e.preventDefault();
            var target = $(this).data('target');
            selector.find(target).hide();
        });

        /* Upload Image */
        selector.find('#palleon-image-upload').on('change', function () {
            selector.find('.palleon-modal').hide();
            canvasWrap.css('visibility', 'visible');
            selector.find('.palleon-content-bar').css('visibility', 'visible');
            var reader = new FileReader();
            reader.onload = function(ev) {
                selector.find('#palleon-canvas-img').attr('src', reader.result);
                init('image');
            };
            reader.readAsDataURL(this.files[0]);
            var fileName = this.files[0].name.replace(/\.[^/.]+$/, "");
            var fileExtention = this.files[0].name.match(/\.[0-9a-z]+$/i)[0].replace(/\./g, "");
            setFileName(fileName, fileExtention);
        });

        /* Empty Canvas */
        selector.find('#palleon-canvas-create').on('click', function() {
            setFileName(new Date().getTime(), '');
            init('canvas');
        });

        /* TEMPLATE LIBRARY */

        /* Template Search */
        selector.find('#palleon-template-search').on('click', function() {
            var category = selector.find('#palleon-templates-menu').val();
            var input = $(this).parent().find('input');
            selector.find("#palleon-all-templates-noimg").addClass('d-none');
            selector.find('#palleon-templates-grid .grid-item').each(function() {
                $(this).attr('data-keyword', $(this).data('keyword').toLowerCase());
            });
            if ($(this).hasClass('cancel')) {
                selector.find('#palleon-templates-menu').val('all').change();
                selector.find('#palleon-templates-menu').parent().find('span.select2-container').css('opacity', 1);
                $(this).removeClass('cancel');
                $(this).find('.material-icons').html('search');
                $(this).removeClass('danger');
                $(this).addClass('primary');
                input.val('');
                selector.find('#palleon-templates-grid .grid-item').show();
                if (selector.find('#palleon-templates-grid-pagination').length) {
                    selector.find('#palleon-templates-grid-pagination').pagination('redraw');
                    selector.find('#palleon-templates-grid-pagination').pagination('selectPage', 1);
                }
                input.prop('disabled', false);
                selector.find('#palleon-templates-menu').prop('disabled', false);
            } else {
                selector.find('#palleon-templates-menu').parent().find('span.select2-container').css('opacity', 0.5);
                $(this).addClass('cancel');
                $(this).find('.material-icons').html('close');
                $(this).removeClass('primary');
                $(this).addClass('danger');
                var searchTerm = input.val().toLowerCase().replace(/\s/g,' ');
                if ((searchTerm == '' || searchTerm.length < 1) && category == 'all') {
                    selector.find('#palleon-templates-grid .grid-item').show();
                    if (selector.find('#palleon-templates-grid-pagination').length) {
                        selector.find('#palleon-templates-grid-pagination').pagination('redraw');
                        selector.find('#palleon-templates-grid-pagination').pagination('selectPage', 1);
                    }
                } else {
                    if (selector.find('#palleon-templates-grid-pagination').length) {
                        selector.find('#palleon-templates-grid-pagination').pagination('destroy');
                    }
                    if (category == 'all') {
                        if (searchTerm != '' || searchTerm.length > 1) {
                            selector.find('#palleon-templates-grid .grid-item').hide().filter('[data-keyword*="'+ searchTerm +'"]').show();
                        }
                    } else {
                        if (searchTerm != '' || searchTerm.length > 1) {
                            selector.find('#palleon-templates-grid .grid-item').hide().filter('[data-keyword*="'+ searchTerm +'"]').filter('[data-category*="'+ category +'"]').show();
                        } else {
                            selector.find('#palleon-templates-grid .grid-item').hide().filter('[data-category*="'+ category +'"]').show();
                        }
                    }
                    if (selector.find('#palleon-templates-grid .grid-item:visible').length === 0) {
                        selector.find("#palleon-all-templates-noimg").removeClass('d-none');
                    }
                }
                input.prop('disabled', true);
                selector.find('#palleon-templates-menu').prop('disabled', true);
            }

        });

        /* Save Template */
        selector.find('#palleon-json-save').on('click', function() {
            createCanvasJSON(function(data){
                let template = JSON.stringify(data);
                settings.saveTemplate.call(this, template);
                selector.find('.palleon-modal').hide();
            });
        });

        /* Download Template */
        selector.find('#palleon-json-download').on('click', function() {
            let name = selector.find('#palleon-json-download-name').val();
            createCanvasJSON(function(data){
                let json = JSON.stringify(data);
                let a = document.createElement("a");
                let file = new Blob([json], { type: "text/plain" });
                a.href = URL.createObjectURL(file);
                a.download = name + '.json';
                a.click();
                selector.find('.palleon-modal').hide();
            });
        });

        /* Create canvas JSON */
        function createCanvasJSON(callback) {
            let canvasJsonData = {};
            let promises = [];
            $.each(canvasItems, function(id, item) {
                let json = item.toJSON(['id', 'objectType','gradientFill','roundedCorders','mode','selectable','lockMovementX','lockMovementY','lockRotation','crossOrigin']);
                canvasJsonData[id] = {};
                canvasJsonData[id].id = id;
                canvasJsonData[id].name = selector.find('.palleon-canvas-wrap-item[data-id="'+id+'"] .palleon-canvas-item-name > input').val();
                let createBG = convertToDataURL(json.backgroundImage.src, function(dataUrl) {
                    json.backgroundImage.src = dataUrl;
                    canvasJsonData[id].data = json;
                });
                promises.push( createBG );
            });
            $.when.apply(null, promises).done(function(){
                callback(canvasJsonData);
            })
        }
        /* Load JSON */
        function loadAllJSON(jsonData, callback = null) {
            canvasLoad('loading', true);
            if( jsonData.version ){
                let randId = 1;
                let newJsonData = {};
                newJsonData[randId] = {
                    name: '',
                    id: randId,
                    data: jsonData
                };
                jsonData = newJsonData;
            }
            let item = Object.values(jsonData)[0];
            if( !item ) return canvasLoad('complete', false);
            let json = item.data;
            createCanvasElm(item.id, function(html){
                selector.find('#palleon-canvas-page').append(html);
                selector.find('#palleon-canvas-page > div[data-id="'+item.id+'"] .palleon-canvas-item-name > input').val(item.name);
            });
            createCanvas(item.id, json.background, function(){
                loadJSON(json, function(){
                    delete jsonData[item.id];
                    callback();
                    loadAllJSON(jsonData, callback);
                    adjustZoom( $('#palleon-img-zoom').val() );
                });
            });
        }

        /* Load JSON */
        function loadJSON(json, callback = null, disableLoad = false) {
            if( !disableLoad ) canvasLoad('loading');
            rotate = json.backgroundImage.angle;
            scaleX = json.backgroundImage.scaleX;
            scaleY = json.backgroundImage.scaleY;
            originX = json.backgroundImage.originX;
            originY = json.backgroundImage.originY;
            canvas.clear();
            selector.find("#palleon-layers-"+canvasId+" > li").remove();

            mode = json.backgroundImage.mode;
            var blob = dataURLtoBlob(json.backgroundImage.src);
            imgurl = URL.createObjectURL(blob);
            selector.find('#palleon-canvas-img').attr("src",imgurl);
            originalWidth[canvasId] = json.backgroundImage.width;
            originalHeight[canvasId] = json.backgroundImage.height;
            var dimentions = {width:originalWidth[canvasId], height:originalHeight[canvasId]};

            for (var i = 0; i < json.objects.length; i++) {
                if (json.objects[i].objectType == 'textbox') {
                    json.objects[i].fontFamily = json.objects[i].fontFamily + '-palleon';
                }
            }

            canvas.loadFromJSON(json, function() {
                var objects = canvas.getObjects();
                $.each(objects, function(i, obj){
                    if( obj.type == 'textbox' ) return;
                    objects[i].perPixelTargetFind = true;
                });
                var textboxes = objects.filter(element => element.objectType == 'textbox');
                loadTemplateFonts(textboxes);
                checkLayers();
                canvas.requestRenderAll();

                setFileName(new Date().getTime(), '');
                setDimentions(dimentions);
                adjustZoom();
                modeCheck();
                canvas.fire('selection:cleared');
                selector.find('#palleon-canvas-color').spectrum("set", canvas.backgroundColor);
                selector.find('#custom-image-background').spectrum("set", canvas.backgroundColor);
                setTimeout(function(){
                    selector.find("#palleon-layers > li").removeClass('active');
                    adjustFilterControls(json.backgroundImage.filters);
                }, 100);
                if( !disableLoad ) canvasLoad('complete');
                if( callback ) callback();
            }, function() {}, {
                crossOrigin: 'anonymous'
            });
        }

        /* Load Template Fonts */
        function loadTemplateFonts(objects) {
            if (objects.length !== 0) {
                $.each(objects, function( index, val ) {
                    var font = val.fontFamily.replace('-palleon', '');
                    val.fontFamily = settings.fontFamily;
                    var loadFonts = 'yes';
                    if (font == '') {
                        loadFonts = 'no';
                    } else {
                        for (var i = 0; i < webSafeFonts.length; i++) {
                            if (webSafeFonts[i][1] == font) {
                                loadFonts = 'no';
                                break;
                            }
                        }
                    }
                    if (loadFonts == 'yes') {
                        if (typeof palleonCustomFonts === 'undefined') {
                            WebFont.load({
                                google: {
                                families: [font + ':regular,bold', font + ':italic,regular,bold']
                                }
                            });
                        } else {
                            if (!palleonCustomFonts.fonts.includes(font)) {
                                WebFont.load({
                                    google: {
                                    families: [font + ':regular,bold', font + ':italic,regular,bold']
                                    }
                                });
                            }
                        }
                        var fontNormal = new FontFaceObserver(font, {
                            weight: 'normal',
                            style: 'normal'
                        });
                        var fontBold = new FontFaceObserver(font, {
                            weight: 'bold',
                            style: 'normal'
                        });
                        var fontNormalItalic = new FontFaceObserver(font, {
                            weight: 'normal',
                            style: 'italic'
                        });
                        var fontBoldItalic = new FontFaceObserver(font, {
                            weight: 'bold',
                            style: 'italic'
                        });
                        Promise.all([fontNormal.load(null, 5000), fontBold.load(null, 5000), fontNormalItalic.load(null, 5000), fontBoldItalic.load(null, 5000)]).then(function () {
                            val.set("fontFamily", font);
                            canvas.requestRenderAll();
                        }).catch(function(e) {
                            console.log(e);
                        });
                    } else {
                        val.set("fontFamily", font);
                        canvas.requestRenderAll();
                    }

                });
            }
        }

        /* Reset template */
        function resetTemplate() {
            canvasItems = {};
            $('#palleon-canvas-page > *').remove();
            $('#palleon-history-list > *').remove();
            $('#palleon-layers-wrap > *').remove();
            canvasWrap.css('visibility', 'visible');
            selector.find('.palleon-modal').hide();
            selector.find('.palleon-content-bar').css('visibility', 'visible');
            selector.find('#palleon-layers-'+canvasId+' li').remove();
            checkLayers();
        }

        /* Upload Template */
        selector.find('#palleon-json-upload').on('change', function (e) {
            resetTemplate();
            canvasLoad('loading');
            var reader = new FileReader();
            var json = '';
            reader.onload = function(ev) {
                json = JSON.parse(reader.result);
                loadAllJSON(json, function(){
                    addToHistory('<span class="material-icons">flag</span>' + palleonParams.started);
                });
            };
            reader.readAsText(e.target.files[0]);
            selector.find('.palleon-modal').hide();
        });

        /* Add Template */
        selector.find('.template-selection').on('click','.palleon-select-template',function(){
            resetTemplate();
            canvasLoad('loading');
            var objects = canvas.getObjects();
            objects.filter(element => element.objectType != 'BG').forEach(element => canvas.remove(element));
            $.getJSON($(this).data('json'), function(json) {
                loadAllJSON(json, function(){
                    addToHistory('<span class="material-icons">flag</span>' + palleonParams.started);
                });
            }).fail(function(jqxhr, textStatus, error) {
                toastr.error("Request Failed: " + error, palleonParams.error);
            });
        });

        /* Search My Templates */
        selector.find('#palleon-my-templates-search').on('click', function () {
            var input = $(this).parent().find('input');
            selector.find("#palleon-my-templates-noimg").addClass('d-none');
            selector.find('#palleon-my-templates li').each(function() {
                $(this).attr('data-keyword', $(this).data('keyword').toLowerCase());
            });
            if (input.val() == '') {
                return;
            }
            if ($(this).hasClass('cancel')) {
                $(this).removeClass('cancel');
                $(this).find('.material-icons').html('search');
                $(this).removeClass('danger');
                $(this).addClass('primary');
                input.val('');
                selector.find('#palleon-my-templates li').show();
                if (selector.find('#palleon-my-templates-pagination').length) {
                    selector.find('#palleon-my-templates-pagination').pagination('redraw');
                    selector.find('#palleon-my-templates-pagination').pagination('selectPage', 1);
                }
                input.prop('disabled', false);
            } else {
                $(this).addClass('cancel');
                $(this).find('.material-icons').html('close');
                $(this).removeClass('primary');
                $(this).addClass('danger');
                var searchTerm = input.val().toLowerCase().replace(/\s/g,' ');
                if ((searchTerm == '' || searchTerm.length < 1)) {
                    selector.find('#palleon-my-templates li').show();
                    if (selector.find('#palleon-my-templates-pagination').length) {
                        selector.find('#palleon-my-templates-pagination').pagination('redraw');
                        selector.find('#palleon-my-templates-pagination').pagination('selectPage', 1);
                    }
                } else {
                    if (selector.find('#palleon-my-templates-pagination').length) {
                        selector.find('#palleon-my-templates-pagination').pagination('destroy');
                    }
                    selector.find('#palleon-my-templates li').hide().filter('[data-keyword*="'+ searchTerm +'"]').show();
                    if (selector.find('#palleon-my-templates li:visible').length === 0) {
                        selector.find("#palleon-my-templates-noimg").removeClass('d-none');
                    }
                }
                input.prop('disabled', true);
            }
        });

        /* Download Image */
        selector.find('#palleon-download').on('click', function () {
            var name = selector.find('#palleon-download-name').val();
            var quality = parseFloat(selector.find('#palleon-download-quality').val());
            var format = selector.find('#palleon-download-format').val();
            var link = document.createElement("a");
            canvas.setZoom(1);
            selector.find('#palleon-img-zoom').val(100);
            var zoomWidth = originalHeight[canvasId];
            var zoomHeight = originalWidth[canvasId];
            if (rotate == 0 || rotate == 180 || rotate == -180) {
                zoomWidth = originalWidth[canvasId];
                zoomHeight = originalHeight[canvasId];
            }
            canvas.setWidth(zoomWidth);
            canvas.setHeight(zoomHeight);

            var imgData = canvas.toDataURL({ format: format, quality: quality, enableRetinaScaling: false});
            var blob = dataURLtoBlob(imgData);
            var objurl = URL.createObjectURL(blob);
            link.download = name + '.' + format;
            link.href = objurl;
            link.click();

            adjustZoom();
            canvas.requestRenderAll();
            selector.find('.palleon-modal').hide();
        });

        /* Download File Format Select */
        selector.find('#palleon-download-format').on('change', function () {
            if ($(this).val() == 'png') {
                selector.find('#palleon-download-quality').prop('disabled', true);
            } else {
                selector.find('#palleon-download-quality').prop('disabled', false);
            }
        });

        /* Save File Format Select */
        selector.find('#palleon-save-img-format').on('change', function () {
            if ($(this).val() == 'png') {
                selector.find('#palleon-save-img-quality').prop('disabled', true);
            } else {
                selector.find('#palleon-save-img-quality').prop('disabled', false);
            }
        });

        /* Download Image (Only text object) */
        selector.find('#palleon-download-print').on('click', function () {
            var name = 'IN CHỮ - '+selector.find('#palleon-download-print-name').val();
            var link = document.createElement("a");
            canvas.setZoom(1);
            selector.find('#palleon-img-zoom').val(100);
            var zoomWidth = originalHeight[canvasId];
            var zoomHeight = originalWidth[canvasId];
            if (rotate == 0 || rotate == 180 || rotate == -180) {
                zoomWidth = originalWidth[canvasId];
                zoomHeight = originalHeight[canvasId];
            }
            canvas.setWidth(zoomWidth);
            canvas.setHeight(zoomHeight);
            let canvasBG = canvas.backgroundColor;
            let format = 'png';
            let objects = canvas.getObjects();
            canvas.backgroundColor = 'transparent';
            $.each(objects, function(i, obj){
                if( obj.type == 'textbox' ) return;
                obj.set('visible', false);
            });
            var imgData = canvas.toDataURL({ format: format, quality: '1', enableRetinaScaling: false});
            var blob = dataURLtoBlob(imgData);
            var objurl = URL.createObjectURL(blob);
            link.download = name + '.' + format;
            link.href = objurl;
            link.click();

            canvas.backgroundColor = canvasBG;
            $.each(objects, function(i, obj){
                if( obj.type == 'textbox' ) return;
                obj.set('visible', true);
            });
            adjustZoom();
            canvas.requestRenderAll();
            selector.find('.palleon-modal').hide();
        });

        /* BLANK CANVAS */
        selector.find('#palleon-canvas-size-select').on('change', function() {
            var val = $(this).val();
            if (val == 'custom') {
                selector.find('#palleon-canvas-width').prop('disabled', false);
                selector.find('#palleon-canvas-height').prop('disabled', false);
            } else {
                selector.find('#palleon-canvas-width').prop('disabled', true);
                selector.find('#palleon-canvas-height').prop('disabled', true);
            }
            if (val == 'blog-banner') {
                selector.find('#palleon-canvas-width').val(2240);
                selector.find('#palleon-canvas-height').val(1260);
            } else if (val == 'fb-cover') {
                selector.find('#palleon-canvas-width').val(851);
                selector.find('#palleon-canvas-height').val(315);
            } else if (val == 'fb-ad') {
                selector.find('#palleon-canvas-width').val(1200);
                selector.find('#palleon-canvas-height').val(628);
            } else if (val == 'instagram') {
                selector.find('#palleon-canvas-width').val(1080);
                selector.find('#palleon-canvas-height').val(1080);
            } else if (val == 'pinterest') {
                selector.find('#palleon-canvas-width').val(750);
                selector.find('#palleon-canvas-height').val(1120);
            } else if (val == 'fb-post') {
                selector.find('#palleon-canvas-width').val(940);
                selector.find('#palleon-canvas-height').val(788);
            } else if (val == 'twitter-post') {
                selector.find('#palleon-canvas-width').val(1600);
                selector.find('#palleon-canvas-height').val(900);
            } else if (val == 'youtube') {
                selector.find('#palleon-canvas-width').val(1280);
                selector.find('#palleon-canvas-height').val(720);
            } else if (val == 'wallpaper') {
                selector.find('#palleon-canvas-width').val(1920);
                selector.find('#palleon-canvas-height').val(1080);
            }
        });

        // Canvas Background
        selector.find('#palleon-canvas-color').on('change', function() {
            var val = $(this).val();
            selector.find('#custom-image-background').spectrum("set", val);
            if (val == '') {
                canvas.backgroundColor = 'transparent';
                canvas.requestRenderAll();
            } else {
                canvas.backgroundColor = val;
                canvas.requestRenderAll();
            }
        });

        /* MEDIA LIBRARY */

        selector.find('#palleon-media-library').on('click', function() {
            mmediaLibraryMode = 'add-to-canvas';
        });

        selector.find('#palleon-img-media-library').on('click', function() {
            mmediaLibraryMode = 'add-as-object';
        });

        selector.find('#palleon-img-replace-media-library').on('click', function() {
            mmediaLibraryMode = 'replace-image';
        });

        /* Select Image */
        selector.find('#modal-media-library').on('click','.media-library-grid>.palleon-masonry-item>.palleon-masonry-item-inner',function(){
            var fullImg = $(this).find('img').data('full');
            var tempImg = new Image();
            if (mmediaLibraryMode == 'add-to-canvas') {
                var fullImgCheck = fullImg.substring(0 , fullImg.indexOf('?'));
                var fileName = $(this).find('img').data('filename');
                var fileExtention = '';
                if (fullImgCheck != '') {
                    fileExtention = fullImgCheck.match(/\.[0-9a-z]+$/i)[0].replace(/\./g, "");
                } else {
                    fileExtention = fullImg.match(/\.[0-9a-z]+$/i)[0].replace(/\./g, "");
                }
                setFileName(fileName, fileExtention);
                convertToDataURL(fullImg, function(dataUrl) {
                    tempImg.src = dataUrl;
                    tempImg.onload = function () {
                        selector.find('#palleon-canvas-img').attr('src', dataUrl);
                        init('image');
                    };
                });
            } else if (mmediaLibraryMode == 'add-as-object') {
                canvasLoad('loading');
                convertToDataURL(fullImg, function(dataUrl) {
                    tempImg.src = dataUrl;
                    tempImg.onload = function () {
                        var image = new fabric.Image(tempImg, {
                            objectType: 'image',
                            roundedCorders: 0,
                            stroke: '#fff',
                            strokeWidth: 0,
                            top: getScaledSize()[1] / 2,
                            left: getScaledSize()[0] / 2,
                            originX: 'center',
                            originY: 'center'
                        });
                        image.perPixelTargetFind = true;
                        canvas.add(image);
                        image.scaleToWidth(getScaledSize()[0] / 2);
                        if (image.isPartiallyOnScreen()) {
                            image.scaleToHeight(getScaledSize()[1] / 2);
                        }
                        canvas.setActiveObject(image);
                        canvas.requestRenderAll();
                        canvasLoad('complete');
                        canvas.fire('palleon:history', { type: 'image', text: palleonParams.added });
                    };
                });
            } else if (mmediaLibraryMode == 'replace-image') {
                canvasLoad('loading');
                convertToDataURL(fullImg, function(dataUrl) {
                    tempImg.src = dataUrl;
                    tempImg.onload = function () {
                        canvas.getActiveObject().setSrc(dataUrl);
                        canvas.requestRenderAll();
                        canvasLoad('complete');
                        canvas.fire('palleon:history', { type: 'image', text: palleonParams.replaced });
                    };
                });
            }
            selector.find('#modal-media-library').hide();
        });

        /* Search My Images */
        selector.find('#palleon-library-my-search').on('click', function () {
            var input = $(this).parent().find('input');
            selector.find("#palleon-library-my-noimg").addClass('d-none');
            if (input.val() == '') {
                return;
            }
            if ($(this).hasClass('cancel')) {
                $(this).removeClass('cancel');
                $(this).find('.material-icons').html('search');
                $(this).removeClass('danger');
                $(this).addClass('primary');
                input.val('');
                selector.find('#palleon-library-my .palleon-masonry-item').show();
                if (selector.find('#palleon-library-my-pagination').length) {
                    selector.find('#palleon-library-my-pagination').pagination('redraw');
                    selector.find('#palleon-library-my-pagination').pagination('selectPage', 1);
                }
                input.prop('disabled', false);
            } else {
                $(this).addClass('cancel');
                $(this).find('.material-icons').html('close');
                $(this).removeClass('primary');
                $(this).addClass('danger');
                var searchTerm = input.val().toLowerCase().replace(/\s/g,' ');
                if ((searchTerm == '' || searchTerm.length < 1)) {
                    selector.find('#palleon-library-my .palleon-masonry-item').show();
                    if (selector.find('#palleon-library-my-pagination').length) {
                        selector.find('#palleon-library-my-pagination').pagination('redraw');
                        selector.find('#palleon-library-my-pagination').pagination('selectPage', 1);
                    }
                } else {
                    if (selector.find('#palleon-library-my-pagination').length) {
                        selector.find('#palleon-library-my-pagination').pagination('destroy');
                    }
                    selector.find('#palleon-library-my .palleon-masonry-item').hide().filter('[data-keyword*="'+ searchTerm +'"]').show();
                    if (selector.find('#palleon-library-my .palleon-masonry-item:visible').length === 0) {
                        selector.find("#palleon-library-my-noimg").removeClass('d-none');
                    }
                }
                input.prop('disabled', true);
            }
        });

        /* Search All Images */
        selector.find('#palleon-library-all-search').on('click', function () {
            var input = $(this).parent().find('input');
            selector.find("#palleon-library-all-noimg").addClass('d-none');
            if (input.val() == '') {
                return;
            }
            if ($(this).hasClass('cancel')) {
                $(this).removeClass('cancel');
                $(this).find('.material-icons').html('search');
                $(this).removeClass('danger');
                $(this).addClass('primary');
                input.val('');
                selector.find('#palleon-library-all .palleon-masonry-item').show();
                if (selector.find('#palleon-library-all-pagination').length) {
                    selector.find('#palleon-library-all-pagination').pagination('redraw');
                    selector.find('#palleon-library-all-pagination').pagination('selectPage', 1);
                }
                input.prop('disabled', false);
            } else {
                $(this).addClass('cancel');
                $(this).find('.material-icons').html('close');
                $(this).removeClass('primary');
                $(this).addClass('danger');
                var searchTerm = input.val().toLowerCase().replace(/\s/g,' ');
                if ((searchTerm == '' || searchTerm.length < 1)) {
                    selector.find('#palleon-library-all .palleon-masonry-item').show();
                    if (selector.find('#palleon-library-all-pagination').length) {
                        selector.find('#palleon-library-all-pagination').pagination('redraw');
                        selector.find('#palleon-library-all-pagination').pagination('selectPage', 1);
                    }
                } else {
                    if (selector.find('#palleon-library-all-pagination').length) {
                        selector.find('#palleon-library-all-pagination').pagination('destroy');
                    }
                    selector.find('#palleon-library-all .palleon-masonry-item').hide().filter('[data-keyword*="'+ searchTerm +'"]').show();
                    if (selector.find('#palleon-library-all .palleon-masonry-item:visible').length === 0) {
                        selector.find("#palleon-library-all-noimg").removeClass('d-none');
                    }
                }
                input.prop('disabled', true);
            }
        });

        /* Save Image */
        selector.find('#palleon-save-img').on('click', function() {
            var quality = parseFloat(selector.find('#palleon-save-img-quality').val());
            var format = selector.find('#palleon-save-img-format').val();

            canvas.setZoom(1);
            selector.find('#palleon-img-zoom').val(100);
            var zoomWidth = originalHeight[canvasId];
            var zoomHeight = originalWidth[canvasId];
            if (rotate == 0 || rotate == 180 || rotate == -180) {
                zoomWidth = originalWidth[canvasId];
                zoomHeight = originalHeight[canvasId];
            }
            canvas.setWidth(zoomWidth);
            canvas.setHeight(zoomHeight);

            // http://fabricjs.com/docs/fabric.Canvas.html#toDataURL
            var imgData = canvas.toDataURL({ format: format, quality: quality, enableRetinaScaling: false});

            settings.saveImage.call(this, imgData);

            selector.find('.palleon-modal').hide();

            adjustZoom();
            canvas.requestRenderAll();
        });

        /* SVG LIBRARY */

        /* Select SVG */
        selector.find('.svg-library-grid').on('click','>.palleon-masonry-item>.palleon-masonry-item-inner',function(){
            var fullSVG = $(this).find('img').data('full');
            fabric.loadSVGFromURL(fullSVG,function(objects, options){
                var svg = fabric.util.groupSVGElements(objects, options);
                svg.set('originX', 'center');
                svg.set('originY', 'center');
                svg.set('left', getScaledSize()[0] / 2);
                svg.set('top', getScaledSize()[1] / 2);
                svg.set('objectType', 'customSVG');
                svg.scaleToWidth(getScaledSize()[0] / 2);
                svg.scaleToHeight(getScaledSize()[1] / 2);
                svg.perPixelTargetFind = true;
                canvas.add(svg);
                canvas.setActiveObject(svg);
                canvas.requestRenderAll();
            }, function() {}, {
                crossOrigin: 'anonymous'
            });
            selector.find('#modal-svg-library').hide();
        });

        /* Search My SVGs */
        selector.find('#palleon-svg-library-my-search').on('click', function () {
            var input = $(this).parent().find('input');
            selector.find("#palleon-svg-library-my-noimg").addClass('d-none');
            if (input.val() == '') {
                return;
            }
            if ($(this).hasClass('cancel')) {
                $(this).removeClass('cancel');
                $(this).find('.material-icons').html('search');
                $(this).removeClass('danger');
                $(this).addClass('primary');
                input.val('');
                selector.find('#palleon-svg-library-my .palleon-masonry-item').show();
                if (selector.find('#palleon-svg-library-my-pagination').length) {
                    selector.find('#palleon-svg-library-my-pagination').pagination('redraw');
                    selector.find('#palleon-svg-library-my-pagination').pagination('selectPage', 1);
                }
                input.prop('disabled', false);
            } else {
                $(this).addClass('cancel');
                $(this).find('.material-icons').html('close');
                $(this).removeClass('primary');
                $(this).addClass('danger');
                var searchTerm = input.val().toLowerCase().replace(/\s/g,' ');
                if ((searchTerm == '' || searchTerm.length < 1)) {
                    selector.find('#palleon-svg-library-my .palleon-masonry-item').show();
                    if (selector.find('#palleon-svg-library-my-pagination').length) {
                        selector.find('#palleon-svg-library-my-pagination').pagination('redraw');
                        selector.find('#palleon-svg-library-my-pagination').pagination('selectPage', 1);
                    }
                } else {
                    if (selector.find('#palleon-svg-library-my-pagination').length) {
                        selector.find('#palleon-svg-library-my-pagination').pagination('destroy');
                    }
                    selector.find('#palleon-svg-library-my .palleon-masonry-item').hide().filter('[data-keyword*="'+ searchTerm +'"]').show();
                    if (selector.find('#palleon-svg-library-my .palleon-masonry-item:visible').length === 0) {
                        selector.find("#palleon-svg-library-my-noimg").removeClass('d-none');
                    }
                }
                input.prop('disabled', true);
            }
        });

        /* Search All SVGs */
        selector.find('#palleon-svg-library-all-search').on('click', function () {
            var input = $(this).parent().find('input');
            selector.find("#palleon-library-all-noimg").addClass('d-none');
            if (input.val() == '') {
                return;
            }
            if ($(this).hasClass('cancel')) {
                $(this).removeClass('cancel');
                $(this).find('.material-icons').html('search');
                $(this).removeClass('danger');
                $(this).addClass('primary');
                input.val('');
                selector.find('#palleon-svg-library-all .palleon-masonry-item').show();
                if (selector.find('#palleon-svg-library-all-pagination').length) {
                    selector.find('#palleon-svg-library-all-pagination').pagination('redraw');
                    selector.find('#palleon-svg-library-all-pagination').pagination('selectPage', 1);
                }
                input.prop('disabled', false);
            } else {
                $(this).addClass('cancel');
                $(this).find('.material-icons').html('close');
                $(this).removeClass('primary');
                $(this).addClass('danger');
                var searchTerm = input.val().toLowerCase().replace(/\s/g,' ');
                if ((searchTerm == '' || searchTerm.length < 1)) {
                    selector.find('#palleon-svg-library-all .palleon-masonry-item').show();
                    if (selector.find('#palleon-svg-library-all-pagination').length) {
                        selector.find('#palleon-svg-library-all-pagination').pagination('redraw');
                        selector.find('#palleon-svg-library-all-pagination').pagination('selectPage', 1);
                    }
                } else {
                    if (selector.find('#palleon-svg-library-all-pagination').length) {
                        selector.find('#palleon-svg-library-all-pagination').pagination('destroy');
                    }
                    selector.find('#palleon-svg-library-all .palleon-masonry-item').hide().filter('[data-keyword*="'+ searchTerm +'"]').show();
                    if (selector.find('#palleon-svg-library-all .palleon-masonry-item:visible').length === 0) {
                        selector.find("#palleon-svg-library-all-noimg").removeClass('d-none');
                    }
                }
                input.prop('disabled', true);
            }
        });

        /* HISTORY */

        function objectName(type) {
            var layerName = palleonParams.object;
            var layerIcon = 'category';
            if (type == null) {
                layerName = palleonParams.object;
                layerIcon = 'category';
            } else if (type == 'textbox') {
                layerName = palleonParams.text;
                layerIcon = 'title';
            } else if (type == 'drawing') {
                layerName = palleonParams.freeDrawing;
                layerIcon = 'brush';
            } else if (type == 'frame') {
                layerName = palleonParams.frame;
                layerIcon = 'wallpaper';
            } else if (type == 'image') {
                layerName = palleonParams.image;
                layerIcon = 'image';
            } else if (type == 'circle') {
                layerName = palleonParams.circle;
            } else if (type == 'square') {
                layerName = palleonParams.square;
            } else if (type == 'rectangle') {
                layerName = palleonParams.rectangle;
            } else if (type == 'triangle') {
                layerName = palleonParams.triangle;
            } else if (type == 'ellipse') {
                layerName = palleonParams.ellipse;
            } else if (type == 'trapezoid') {
                layerName = palleonParams.trapezoid;
            } else if (type == 'emerald') {
                layerName = palleonParams.emerald;
            } else if (type == 'star') {
                layerName = palleonParams.star;
            } else if (type == 'element') {
                layerName = palleonParams.element;
                layerIcon = 'star';
            } else if (type == 'BG') {
                layerName = palleonParams.bg;
                layerIcon = 'image';
            } else if (type == 'customSVG') {
                layerName = palleonParams.customSvg;
            }
            return '<span class="material-icons">' + layerIcon + '</span>' + layerName;
        }

        // Add to history
        function addToHistory(action) {
            var list = selector.find('#palleon-history-list-'+canvasId+'');
            var today = new Date();
            var time = String(today.getHours()).padStart(2, '0') + ":" + String(today.getMinutes()).padStart(2, '0') + ":" + String(today.getSeconds()).padStart(2, '0');
            var json = canvas.toJSON(['id', 'objectType','gradientFill','roundedCorders','mode','selectable','lockMovementX','lockMovementY','lockRotation','crossOrigin']);
            let layerActive = $('#palleon-layers-wrap li.active').attr('id') ?? '';
            selector.find('#palleon-history').prop('disabled', false);
            list.find('li').removeClass('active');
            list.prepend('<li class="active" data-layer="'+layerActive+'"><div class="info">' + action + '<span class="time">' + time + '</span></div><div><button type="button" class="palleon-btn primary"><span class="material-icons">restore</span>Khôi phục</button><button type="button" class="palleon-btn danger"><span class="material-icons">clear</span>Xóa</button><script type="text/json">' + JSON.stringify(json) + '</script></div></li>');

            var count = list.find('li').length;
            var limit = list.data('max');

            if (count > limit) {
                list.find('li').last().remove();
            }

            selector.find('#palleon-history-count').html(list.find('li').length);

            var undo = list.find('li.active').next('li');
            var redo = list.find('li.active').prev('li');

            if (undo.length) {
                selector.find('#palleon-undo').prop('disabled', false);
            } else {
                selector.find('#palleon-undo').prop('disabled', true);
            }
            if (redo.length) {
                selector.find('#palleon-redo').prop('disabled', false);
            } else {
                selector.find('#palleon-redo').prop('disabled', true);
            }
        }

        // Undo
        selector.find('#palleon-undo').on('click',function(){
            var target = selector.find('#palleon-history-list-'+canvasId+' li.active').next('li');
            if (target.length) {
                target.find('.palleon-btn.primary').click();
                selector.find('#palleon-redo').prop('disabled', false);
            } else {
                selector.find('#palleon-undo').prop('disabled', true);
            }
        });

        // Redo
        selector.find('#palleon-redo').on('click',function(){
            var target = selector.find('#palleon-history-list-'+canvasId+' li.active').prev('li');
            if (target.length) {
                target.find('.palleon-btn.primary').trigger('click');
                selector.find('#palleon-undo').prop('disabled', false);
            } else {
                selector.find('#palleon-redo').prop('disabled', true);
            }
        });

        // Delete history
        selector.find('#palleon-history-list').on('click','.palleon-history-list-active .palleon-btn.danger',function(){
            $(this).parent().parent().remove();
            if (!$('#palleon-history-list-'+canvasId+' li').length) {
                selector.find('#palleon-history').prop('disabled', true);
                selector.find('#palleon-undo').prop('disabled', true);
                selector.find('#palleon-redo').prop('disabled', true);
                selector.find('.palleon-modal').hide();
            }
        });

        // Restore history
        selector.find('#palleon-history-list').on('click', '.palleon-history-list-active .palleon-btn.primary',function(){
            selector.find('#palleon-history-list-'+canvasId+' li').removeClass('active');
            $(this).parent().parent().addClass('active');
            var undo = selector.find('#palleon-history-list-'+canvasId+' li.active').next('li');
            var redo = selector.find('#palleon-history-list-'+canvasId+' li.active').prev('li');
            if (undo.length) {
                selector.find('#palleon-undo').prop('disabled', false);
            } else {
                selector.find('#palleon-undo').prop('disabled', true);
            }
            if (redo.length) {
                selector.find('#palleon-redo').prop('disabled', false);
            } else {
                selector.find('#palleon-redo').prop('disabled', true);
            }
            var json = JSON.parse($(this).parent().find('script').html());
            let layerActive = $(this).parents('li').attr('data-layer');
            selector.find('.palleon-modal').hide();
            convertToDataURL(json.backgroundImage.src, function(dataUrl) {
                /*canvas.clear();
                */
                selector.find("#palleon-layers-"+canvasId+" > li").remove();
                json.backgroundImage.src = dataUrl;
                /*loadJSON(json, null, true);*/
                canvas.loadFromJSON(json, function() {
                    var objects = canvas.getObjects();
                    $.each(objects, function(i, obj){
                        if( obj.oldid == layerActive ) {
                            canvas.setActiveObject(obj);
                        }
                        if( obj.type == 'textbox' ) return;
                        objects[i].perPixelTargetFind = true;
                    });
                    var textboxes = objects.filter(element => element.objectType == 'textbox');
                    loadTemplateFonts(textboxes);
                    canvas.requestRenderAll();
                }, function() {}, {
                    crossOrigin: 'anonymous'
                });
            });
        });

        /* Clear history */
        selector.find('#palleon-clear-history').on('click',function(){
            var answer = window.confirm("Are you sure you want clear the history?");
            if (answer) {
                selector.find('#palleon-history-list-'+canvasId+' li').remove();
                selector.find('#palleon-history').prop('disabled', true);
                selector.find('#palleon-undo').prop('disabled', true);
                selector.find('#palleon-redo').prop('disabled', true);
                selector.find('.palleon-modal').hide();
            }
        });

        /* EVENTS */
        function initCanvasEvent() {
            canvas.on('palleon:history', function(e) {
                addToHistory(objectName(e.type) + ' ' + e.text);
            });

            var isObjectMoving  = false;

            canvas.on('mouse:down', function (e) {
                let self = $(this.wrapperEl).parents('.palleon-canvas-wrap-item');
                canvas.clearContext(canvas.contextTop);
                canvasOld = self;
            });
            canvas.on('mouse:up', function (e) {
                var obj = e.target;
                if (obj !== null) {
                    var objType = obj.objectType;
                    if( obj.lockRotation ) layerToggle([obj], e);
                    if (isObjectMoving) {
                        addToHistory(objectName(objType) + ' ' + palleonParams.moved);
                    }
                }
            });

            canvas.on('object:moving', function (e) {
                isObjectMoving = true;
                var tempW = originalHeight[canvasId];
                var tempH = originalWidth[canvasId];
                if (rotate == 0 || rotate == 180 || rotate == -180) {
                    tempW = originalWidth[canvasId];
                    tempH = originalHeight[canvasId];
                }
                var obj = e.target;
                var objWidth = obj.getScaledWidth();
                var objHeight = obj.getScaledHeight();
                if (obj.isPartiallyOnScreen() && obj.objectType == 'clipPath') {
                    // top - left
                    if (obj.top < 0 && obj.left < 0) {
                        obj.top = 0;
                        obj.left = 0;
                        obj.lockMovementX = true;
                        obj.lockMovementY = true;
                    }
                    // top - right
                    else if (obj.top < 0 && objWidth + obj.left > tempW) {
                        obj.top = 0;
                        obj.left = tempW - objWidth;
                        obj.lockMovementX = true;
                        obj.lockMovementY = true;
                    }
                    // bottom - left
                    else if (objHeight + obj.top > tempH && obj.left < 0) {
                        obj.top = tempH - objHeight;
                        obj.left = 0;
                        obj.lockMovementX = true;
                        obj.lockMovementY = true;
                    }
                    // bottom - right
                    else if (objHeight + obj.top > tempH && objWidth + obj.left > tempW) {
                        obj.top = tempH - objHeight;
                        obj.left = tempW - objWidth;
                        obj.lockMovementX = true;
                        obj.lockMovementY = true;
                    }
                    // top
                    else if (obj.top < 0) {
                        obj.top = 0;
                        obj.lockMovementY = true;
                    }
                    // left
                    else if (obj.left < 0) {
                        obj.left = 0;
                        obj.lockMovementX = true;
                    }
                    // right
                    else if (objWidth + obj.left > tempW) {
                        obj.left = tempW - objWidth;
                        obj.lockMovementX = true;
                    }
                    // bottom
                    else if (objHeight + obj.top > tempH) {
                        obj.top = tempH - objHeight;
                        obj.lockMovementY = true;
                    }
                    obj.setCoords();
                }
            });

            canvas.on('object:scaling', function (e) {
                var tempW = originalHeight[canvasId];
                var tempH = originalWidth[canvasId];
                if (rotate == 0 || rotate == 180 || rotate == -180) {
                    tempW = originalWidth[canvasId];
                    tempH = originalHeight[canvasId];
                }
                var obj = e.target;
                var objWidth = obj.getScaledWidth();
                var objHeight = obj.getScaledHeight();
                if (obj.isPartiallyOnScreen() && obj.objectType == 'clipPath') {
                    // Max Width
                    if(objWidth >= tempW){
                        obj.set({scaleX: tempW/obj.width});
                        obj.lockScalingX = true;
                    }
                    // Max Height
                    if(objHeight >= tempH){
                        obj.set({scaleY: tempH/obj.height});
                        obj.lockScalingY = true;
                    }
                    // top
                    if (obj.top < 0) {
                        obj.top = 0;
                        obj.lockScalingX = true;
                        obj.lockScalingY = true;
                        obj.setCoords();
                    }
                    // left
                    if (obj.left < 0) {
                        obj.left = 0;
                        obj.lockScalingX = true;
                        obj.lockScalingY = true;
                        obj.setCoords();
                    }
                    // right
                    if (objWidth + obj.left > tempW) {
                        obj.left = tempW - objWidth;
                        obj.lockScalingX = true;
                        obj.lockScalingY = true;
                        obj.setCoords();
                    }
                    // bottom
                    if (objHeight + obj.top > tempH) {
                        obj.top = tempH - objHeight;
                        obj.lockScalingX = true;
                        obj.lockScalingY = true;
                        obj.setCoords();
                    }
                }
            });

            canvas.on('object:added', function (e) {
                var obj = e.target;
                if ( obj.objectType != 'clipPath' ) {
                    if ( !obj.objectType && canvas.isDrawingMode ) {
                        obj.set('objectType', 'drawing');
                        /*obj.set('selectable', false);
                        obj.set('lockMovementX', true);
                        obj.set('lockMovementY', true);
                        obj.set('lockRotation', true);*/
                        obj.perPixelTargetFind = true;
                    }
                    var order = canvas.getObjects().indexOf(obj);
                    var output = '';
                    var layerName = 'Object';
                    var layerIcon = 'category';
                    var visibility = 'layer-visible';
                    var visibilityTag = 'visibility';
                    var lock = 'layer-unlocked';
                    var lockTag = 'lock_open';

                    if (obj.visible == false) {
                        visibility = 'layer-hidden';
                        visibilityTag = 'visibility_off';
                    }

                    if (obj.selectable == false) {
                        lock = 'layer-locked';
                        lockTag = 'lock';
                    }
                    if( obj.id ) obj.set('oldid', obj.id);
                    obj.id = new Date().getTime();

                    selector.find("#palleon-layers-"+canvasId+" > li").removeClass('active');

                    if (obj.objectType == 'textbox') {
                        layerName = obj.text;
                        layerIcon = 'title';
                    } else if (obj.objectType == 'drawing') {
                        layerName = palleonParams.freeDrawing;
                        layerIcon = 'brush';
                    } else if (obj.objectType == 'frame') {
                        layerName = palleonParams.frame;
                        layerIcon = 'wallpaper';
                    } else if (obj.objectType == 'image') {
                        layerName = palleonParams.image;
                        layerIcon = 'image';
                    } else if (obj.objectType == 'circle') {
                        layerName = palleonParams.circle;
                    } else if (obj.objectType == 'square') {
                        layerName = palleonParams.square;
                    } else if (obj.objectType == 'rectangle') {
                        layerName = palleonParams.rectangle;
                    } else if (obj.objectType == 'triangle') {
                        layerName = palleonParams.triangle;
                    } else if (obj.objectType == 'ellipse') {
                        layerName = palleonParams.ellipse;
                    } else if (obj.objectType == 'trapezoid') {
                        layerName = palleonParams.trapezoid;
                    } else if (obj.objectType == 'emerald') {
                        layerName = palleonParams.emerald;
                    } else if (obj.objectType == 'star') {
                        layerName = palleonParams.star;
                    } else if (obj.objectType == 'element') {
                        layerName = palleonParams.element;
                        layerIcon = 'star';
                    } else if (obj.objectType == 'customSVG') {
                        layerName = palleonParams.customSvg;
                    }

                    output = '<li id="' + obj.id + '" data-type="' + obj.objectType + '" class="layer-' + obj.objectType + ' active" data-sort="' + order + '"><span class="material-icons">' + layerIcon + '</span><div class="layer-name">' + layerName + '</div><div class="layer-icons"><a class="material-icons lock-layer ' + lock + '" title="' + palleonParams.lockunlock + '">' + lockTag + '</a><a class="material-icons layer-visibility ' + visibility + '" title="' + palleonParams.showhide + '">' + visibilityTag + '</a><a class="material-icons duplicate-layer" title="' + palleonParams.duplicate + '">content_copy</a><a class="material-icons delete-layer" title="' + palleonParams.delete + '">clear</a></div></li>';
                    selector.find('#palleon-layers-'+canvasId+'').prepend(output);
                    deleteLayerEvent(obj.id);
                    cloneLayerEvent(obj.id);
                    visibilityLayerEvent(obj.id);
                    lockLayerEvent(obj.id);
                    clickLayerEvent(obj.id);
                    selector.find('#palleon-layers-'+canvasId+'').sortable('refresh');
                    checkLayers();
                    if( canvas.isDrawingMode && obj.objectType == 'drawing' ) {
                        // Add to draw path to history
                        if( !obj.version ) canvas.fire('palleon:history', { type: obj.objectType, text: palleonParams.added });
                    }
                }
            });

            canvas.on('object:modified', function (e) {
                var obj = e.target;
                if( !canvas.getActiveObject() ) return disactiveAllObject();
                if (obj.objectType == 'textbox' && obj.id) {
                    selector.find("#palleon-layers-"+canvasId+" #" + obj.id + " .layer-name").html(obj.text);
                    selector.find('#text-rotate').val(parseInt(canvas.getActiveObject().angle));
                    selector.find('#text-rotate').parent().parent().find('.slider-label span').html(parseInt(canvas.getActiveObject().angle));
                }
                if (obj.objectType == 'image' && obj.id) {
                    selector.find('#img-rotate').val(parseInt(canvas.getActiveObject().angle));
                    selector.find('#img-rotate').parent().parent().find('.slider-label span').html(parseInt(canvas.getActiveObject().angle));
                }
                if ((obj.objectType == 'element' || obj.objectType == 'customSVG') && obj.id) {
                    selector.find('#element-rotate').val(parseInt(canvas.getActiveObject().angle));
                    selector.find('#element-rotate').parent().parent().find('.slider-label span').html(parseInt(canvas.getActiveObject().angle));
                }
                if (shapeTypes.includes(obj.objectType) && obj.id) {
                    selector.find('#shape-rotate').val(parseInt(canvas.getActiveObject().angle));
                    selector.find('#shape-rotate').parent().parent().find('.slider-label span').html(parseInt(canvas.getActiveObject().angle));
                }
                if (obj.objectType == 'clipPath') {
                    obj.lockScalingX = false;
                    obj.lockScalingY = false;
                    obj.lockMovementX = false;
                    obj.lockMovementY = false;
                }
            });

            // Selection Events
            canvas.on('selection:created', function (e) {
                var obj = e.selected;
                layerToggle(obj, e);
            });

            canvas.on('selection:updated', function (e) {
                var obj = e.selected;
                layerToggle(obj, e);
                selector.find('#palleon-font-family').trigger('change');
            });

            canvas.on('selection:cleared', function () {
                selector.find('#palleon-text-settings').hide();
                selector.find('#palleon-image-settings').hide();
                selector.find('#palleon-shape-settings').hide();
                selector.find('#palleon-custom-element-options').hide();
                selector.find('#palleon-custom-element-options-info').show();
                selector.find("#palleon-layers-"+canvasId+" > li").removeClass('active');
            });

            canvas.on('mouse:over', function(e) {
                let self = $(this.wrapperEl).parents('.palleon-canvas-wrap-item')[0];
                focusCanvasPage(self);
                if(e.target){
                    //$(self).removeClass('palleon-canvas-wrap-focus');
                    if(e.target != canvas.getActiveObject() ){
                        e.target._renderControls(e.target.canvas.contextTop, {
                            hasControls: false
                        });
                    }
                }else{
                    //$(self).addClass('palleon-canvas-wrap-focus');
                }
            });

            canvas.on('mouse:out', function(e) {
                if(e.target){
                    e.target.canvas.clearContext(e.target.canvas.contextTop);
                    canvas.renderAll();
                    //$(self).addClass('palleon-canvas-wrap-focus');
                }else{
                    //$(self).removeClass('palleon-canvas-wrap-focus');
                }
            });
            canvasWrap.on('mouseleave', function(e){
                if( canvasOld ) focusCanvasPage(canvasOld);
            });

        }

        /* Horizontal Align Center */
        selector.find('.palleon-horizontal-center').on('click', function() {
            var obj = canvas.getActiveObject();
            obj.set('originX', 'center');
            obj.set('left', getScaledSize()[0] / 2);
            canvas.requestRenderAll();
        });

        /* Vertical Align Center */
        selector.find('.palleon-vertical-center').on('click', function() {
            var obj = canvas.getActiveObject();
            obj.set('originY', 'center');
            obj.set('top', getScaledSize()[1] / 2);
            canvas.requestRenderAll();
        });


        /* Layers */
        function initLayerEvent() {
            $('.palleon-layers').hide();
            $('#palleon-layers-'+canvasId).show();
            selector.find("#palleon-layers-"+canvasId+"").sortable({
                placeholder: "layer-placeholder",
                axis: 'y',
                update: function(e,ui) {
                    var objects = canvas.getObjects();
                    $('#palleon-layers-'+canvasId+' li').each(function(index, value) {
                        $(this).attr("data-sort", index + 1);
                        objects.filter(element => element.id == value.id).forEach(element => element.moveTo(-(index + 1)));
                    });
                    canvas.requestRenderAll();
                },
                create: function(e,ui) {
                    checkLayers();
                },
            }).disableSelection();
        }

        /* Delete Layer Event */
        function deleteLayerEvent(id) {
            var item = selector.find("#palleon-layers-"+canvasId+" #" + id);
            item.find("a.delete-layer").on('click', function(e) {
                e.preventDefault();
                var objects = canvas.getObjects();
                objects.filter(element => element.id == id).forEach(element => canvas.remove(element));
                item.remove();
                canvas.requestRenderAll();
                selector.find('#palleon-layers-'+canvasId+'').sortable('refresh');
                checkLayers();
                canvas.fire('palleon:history', { type: item.data('type'), text: palleonParams.removed });
                canvas.discardActiveObject();
            });
        }

        /* Clone Layer Event */
        function cloneLayerEvent(id) {
            var item = selector.find("#palleon-layers-"+canvasId+" #" + id);
            item.find("a.duplicate-layer").on('click', function(e) {
                e.preventDefault();
                var objects = canvas.getObjects();
                objects.filter(element => element.id == id).forEach(element => element.clone(function(cloned) {
                    cloned.set('id', new Date().getTime());
                    cloned.set('objectType', element.objectType);
                    cloned.left += 20;
                    cloned.top += 20;
                    if( element.objectType != 'textbox' ) cloned.perPixelTargetFind = true;
                    canvas.add(cloned);
                    canvas.setActiveObject(cloned);
                }));
                canvas.requestRenderAll();
                selector.find('#palleon-layers-'+canvasId+'').sortable('refresh');
                canvas.fire('palleon:history', { type: item.data('type'), text: palleonParams.added });
            });
        }

        /* Visibility Layer Event */
        function visibilityLayerEvent(id) {
            var item = selector.find("#palleon-layers-"+canvasId+" #" + id);
            item.find("a.layer-visibility").on('click', function(e) {
                e.preventDefault();
                var objects = canvas.getObjects();
                if ($(this).hasClass('layer-visible')) {
                    $(this).removeClass('layer-visible');
                    $(this).addClass('layer-hidden');
                    $(this).html('visibility_off');
                    objects.filter(element => element.id == id).forEach(element => element.set('visible', false));
                } else if ($(this).hasClass('layer-hidden')) {
                    $(this).removeClass('layer-hidden');
                    $(this).addClass('layer-visible');
                    $(this).html('visibility');
                    objects.filter(element => element.id == id).forEach(element => element.set('visible', true));
                }
                canvas.requestRenderAll();
            });
        }

        /* Lock/Unlock Layer Event */
        function lockLayerEvent(id) {
            var item = selector.find("#palleon-layers-"+canvasId+" #" + id);
            item.find("a.lock-layer").on('click', function(e) {
                e.preventDefault();
                lockLayerExecute(this);
            });
        }
        function lockLayerExecute(self) {
            setTimeout(function(){
                var obj = canvas.getActiveObject();
                if( !obj ) return lockLayerExecute(self);
                if ($(self).hasClass('layer-unlocked')) {
                    $(self).removeClass('layer-unlocked');
                    $(self).addClass('layer-locked');
                    $(self).html('lock');
                    obj.set({lockMovementX: true, lockMovementY: true, lockRotation:true, selectable:false});
                    canvas.discardActiveObject().renderAll();
                } else if ($(self).hasClass('layer-locked')) {
                    $(self).removeClass('layer-locked');
                    $(self).addClass('layer-unlocked');
                    $(self).html('lock_open');
                    obj.set({lockMovementX: false, lockMovementY: false, lockRotation:false, selectable:true});
                }
                canvas.requestRenderAll();
            }, 100);
        }

        /* Layer Click Event */
        function clickLayerEvent(id) {
            var item = selector.find("#palleon-layers-"+canvasId+" #" + id);
            item.on('click', function(e) {
                if( !e.ctrlKey ){
                    selector.find("#palleon-layers-"+canvasId+" > li").removeClass('active');
                }
                let objects = canvas.getObjects();
                let id = $(this).attr('id');
                let isActive = false;
                if( $(this).hasClass('active') ){
                    isActive = true;
                }
                $(this).addClass('active');
                let objs = [];
                $.each(objects, function(i, item){
                    let activeLayerEl = selector.find("#palleon-layers-"+canvasId+" > li#"+item.id);
                    if( item.id == id && isActive && e.ctrlKey ){
                        activeLayerEl.removeClass('active');
                    }else if( activeLayerEl.hasClass('active') ){
                        objs.push(item);
                    }
                });
                canvas.discardActiveObject();
                if( objs.length > 1 ) {
                    // Multiple select
                    let sel = new fabric.ActiveSelection(objs, {canvas});
                    canvas.setActiveObject(sel);
                }else{
                    // Select one
                    objects.filter(element => element.id == id).forEach(element => canvas.setActiveObject(element));
                }

               canvas.requestRenderAll();
            });
        }

        function selectAllObject() {
            let objects = canvas.getObjects();
            canvas.discardActiveObject();
            let sel = new fabric.ActiveSelection(objects, {
                canvas: canvas,
            });
            canvas.setActiveObject(sel);
            canvas.requestRenderAll();
        }

        function unSelectAllObject() {
            disactiveAllObject();
        }

        /* Layer Click Event */
        function checkLayers() {
            if ( !selector.find(".palleon-layers li").length) {
                selector.find("#palleon-no-layer").show();
            } else {
                selector.find("#palleon-no-layer").hide();
            }
            if (! selector.find("#palleon-layers-"+canvasId+" li").length) {
                selector.find("#palleon-layer-delete-wrap").css('visibility', 'hidden');
            } else {
                selector.find("#palleon-layer-delete-wrap").css('visibility', 'visible');
            }
        }

        /* Layer Toggle */
        function layerToggle(obj, e) {
            if(e.e){
                e = e.e;
            }else{
                e.ctrlKey = false;
            }
            if( !e.ctrlKey ) selector.find("#palleon-layers-"+canvasId+" li").removeClass('active');
            if (obj.length >= 2) {
                for (var i = 0; i < obj.length; i++) {
                    selector.find("#palleon-layers-"+canvasId+" #" + obj[i].id).addClass('active');
                }
            } else {
                obj = obj[0];
                if (obj.objectType) {
                    // Textbox
                    if (obj.objectType == 'textbox') {
                        selector.find('#palleon-text-settings').show();
                        setTextSettings(obj);
                        if (!selector.find('#palleon-btn-text').hasClass('active')) {
                            selector.find('#palleon-btn-text').trigger('click');
                        }
                        selector.find('#palleon-font-family').trigger('change');
                    } else {
                        selector.find('#palleon-text-settings').hide();
                    }
                    // Image
                    if (obj.objectType == 'image') {
                        selector.find('#palleon-image-settings').show();
                        setImageSettings(obj);
                        if (!selector.find('#palleon-btn-image').hasClass('active')) {
                            selector.find('#palleon-btn-image').trigger('click');
                        }
                    } else {
                        selector.find('#palleon-image-settings').hide();
                    }
                    // Frames
                    if (obj.objectType == 'frame') {
                        if (!selector.find('#palleon-btn-frames').hasClass('active')) {
                            selector.find('#palleon-btn-frames').trigger('click');
                        }
                    }
                    // Elements & Custom SVG
                    if (obj.objectType == 'element' || obj.objectType == 'customSVG') {
                        selector.find('#palleon-custom-element-options').show();
                        selector.find('#palleon-custom-element-options-info').hide();
                        setElementSettings(obj);
                        if (!selector.find('#palleon-btn-elements').hasClass('active')) {
                            selector.find('#palleon-btn-elements').trigger('click');
                        }
                        selector.find('#palleon-custom-element-open').trigger('click');
                    } else {
                        selector.find('#palleon-custom-element-options').hide();
                        selector.find('#palleon-custom-element-options-info').show();
                    }
                    // Shapes
                    if (shapeTypes.includes(obj.objectType)) {
                        if (resizableShapeTypes.includes(obj.objectType)) {
                            selector.find('#shape-custom-width-wrap').show();
                        } else {
                            selector.find('#shape-custom-width-wrap').hide();
                        }
                        selector.find('#palleon-shape-settings').show();
                        setShapeSettings(obj);
                        if (!selector.find('#palleon-btn-shapes').hasClass('active')) {
                            selector.find('#palleon-btn-shapes').trigger('click');
                        }
                    } else {
                        selector.find('#palleon-shape-settings').hide();
                    }
                    if (obj.id) {
                        selector.find("#palleon-layers-"+canvasId+" #" + obj.id).addClass('active');
                    }
                } else {
                    $.each(obj, function( index, val ) {
                        selector.find("#palleon-layers-"+canvasId+" #" + val.id).addClass('active');
                    });
                }
            }
        }

        /* Layer Delete */
        selector.find('#palleon-layer-delete').on('click', function() {
            var answer = window.confirm("Xác nhận xóa các layer?");
            if (answer) {
                var type = selector.find('#palleon-layer-select').val();
                var objects = canvas.getObjects();
                if (type == 'all') {
                    objects.forEach(element => canvas.remove(element));
                    selector.find("#palleon-layers-"+canvasId+" > li").remove();
                } else {
                    objects.filter(element => element.objectType == type).forEach(element => canvas.remove(element));
                    selector.find("#palleon-layers-"+canvasId+" > li.layer-" + type).remove();
                }
                canvas.requestRenderAll();
                selector.find('#palleon-layers-'+canvasId+'').sortable('refresh');
                checkLayers();
            }
        });

        /* Set Background Image */
        function setBackgroundImage() {
            fabric.Image.fromURL(imgurl, function(img) {
                canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas), {
                    objectType: 'BG',
                    mode: mode,
                    top: 0,
                    left: 0,
                    scaleX: scaleX,
                    scaleY: scaleY,
                    selectable: false,
                    angle: rotate,
                    originX: originX,
                    originY: originY,
                    lockMovementX: true,
                    lockMovementY: true,
                    lockRotation: true,
                    erasable: true
                }, { crossOrigin: 'anonymous' });
            });
        }

        /* Adjust Zoom */
        function adjustZoom(zoom, setAll = false) {
            var zoomWidth = originalHeight[canvasId];
            var zoomHeight = originalWidth[canvasId];
            if (rotate == 0 || rotate == 180 || rotate == -180) {
                zoomWidth = originalWidth[canvasId];
                zoomHeight = originalHeight[canvasId];
            }
            let setZoomValue = null;
            if (zoom) {
                zoom = zoom / 100;
                setZoomValue = zoom;
            } else {
                var currentZoom = selector.find('#palleon-img-zoom').val();
                var requiredRatio = 100;
                var ratio = 1;
                var ratio2 = 0;
                if (zoomWidth < selector.find('#palleon-content').width() && zoomHeight < selector.find('#palleon-content').height()) {
                    setZoomValue = 1;
                    selector.find('#palleon-img-zoom').val(100);
                } else {
                    if (zoomWidth > selector.find('#palleon-content').width()) {
                        ratio = (selector.find('#palleon-content').width() - 60) / zoomWidth;
                        requiredRatio = ratio.toFixed(2) * 100;
                        if (currentZoom > requiredRatio) {
                            setZoomValue = ratio.toFixed(2);
                            selector.find('#palleon-img-zoom').val(ratio.toFixed(2) * 100);
                            ratio2 = ratio.toFixed(2);
                        }
                    }
                    if (zoomHeight > selector.find('#palleon-content').height()) {
                        ratio = selector.find('#palleon-content').height() / zoomHeight;
                        requiredRatio = ratio.toFixed(2) * 100;
                        if (currentZoom > requiredRatio) {
                            if (ratio2 === 0 || ratio2 > ratio.toFixed(2)) {
                                setZoomValue = ratio.toFixed(2);
                                selector.find('#palleon-img-zoom').val(ratio.toFixed(2) * 100);
                            }
                        }
                    }
                }
            }
            if( setZoomValue ) {
                if( setAll ) {
                    $.each(canvasItems, function(i, item){
                        item.setZoom(setZoomValue);
                        item.setWidth(zoomWidth * item.getZoom());
                        item.setHeight(zoomHeight * item.getZoom());

                        if (item.isDrawingMode === true) {
                            if (selector.find('#palleon-erase-btn').hasClass('active')) {
                                selector.find('#eraser-width').trigger('input');
                            }
                            if (selector.find('#palleon-draw-btn').hasClass('active')) {
                                selector.find('#brush-width').trigger('input');
                            }
                        }
                    });
                }else{
                    canvas.setZoom(setZoomValue);
                    canvas.setWidth(zoomWidth * canvas.getZoom());
                    canvas.setHeight(zoomHeight * canvas.getZoom());

                    if (canvas.isDrawingMode === true) {
                        if (selector.find('#palleon-erase-btn').hasClass('active')) {
                            selector.find('#eraser-width').trigger('input');
                        }
                        if (selector.find('#palleon-draw-btn').hasClass('active')) {
                            selector.find('#brush-width').trigger('input');
                        }
                    }
                }
            }
            initRuler();
        }

        /* Pan */
        selector.find('#palleon-img-drag').on('click', function() {
            if ($(this).hasClass('active')) {
                $(this).removeClass('active');
                canvasWrap.find(".palleon-canvas-overlay").hide();
                canvasWrap.draggable('disable');
            } else {
                $(this).addClass('active');
                canvasWrap.find(".palleon-canvas-overlay").show();
                canvasWrap.draggable('enable');
            }
        });

        /* Zoom */
        selector.find('.palleon-counter input.palleon-form-field').on('input', function() {
            var val = parseInt($(this).val());
            adjustZoom(val, true);
        });

        /* Resize Input Update */
        var setDimentions = function(e) {
            selector.find('#palleon-resize-width').val(Math.round(e.width));
            selector.find('#palleon-resize-height').val(Math.round(e.height));
            selector.find('#palleon-img-width').html(Math.round(e.width));
            selector.find('#palleon-img-height').html(Math.round(e.height));
            selector.find('#palleon-crop-width').val(Math.round(e.width / 2));
            selector.find('#palleon-crop-height').val(Math.round(e.height / 2));
            if (mode == 'image') {
                selector.find('#palleon-resize-width').data('max', Math.round(e.width));
                selector.find('#palleon-resize-height').data('max', Math.round(e.height));
                selector.find('#palleon-crop-width').data('max', Math.round(e.width));
                selector.find('#palleon-crop-height').data('max', Math.round(e.height));
            } else {
                selector.find('#palleon-resize-width').data('max', '');
                selector.find('#palleon-resize-height').data('max', '');
            }
            originalWidth[canvasId] = Math.round(e.width);
            originalHeight[canvasId] = Math.round(e.height);
        };

        /* CROP */
        function updateImage() {
            var objects = canvas.getObjects();
            objects.filter(element => element.objectType != 'BG').forEach(element => element.set("visible", false));
            canvas.backgroundColor = 'transparent';

            var imgData = canvas.toDataURL({ format: 'png', enableRetinaScaling: false});
            var blob = dataURLtoBlob(imgData);
            imgurl = URL.createObjectURL(blob);
            selector.find('#palleon-canvas-img').attr("src",imgurl);

            canvas.backgroundColor = selector.find('#custom-image-background').val();
            objects.filter(element => element.objectType != 'BG').forEach(element => element.set("visible", true));
        }

        function setClipPath() {
            var objects = canvas.getObjects();
            clipPath.moveTo(9999);
            canvas.setActiveObject(clipPath);
            selector.find('#palleon-crop-btns').removeClass('disabled');
            selector.find(".palleon-icon-panel-content ul.palleon-accordion > li, #palleon-icon-menu, #palleon-top-bar, #palleon-right-col").css('pointer-events', 'none');
            selector.find(".palleon-icon-panel-content ul.palleon-accordion > li.accordion-crop").css('pointer-events', 'auto');
            objects.filter(element => element.objectType != 'clipPath' && element.objectType != 'BG').forEach(element => element.set('selectable', false));
        }

        /* Crop Style Select */
        selector.find('#palleon-crop-style').on("change", function(){
            if ($(this).val() != '') {
                $(this).select2("enable", false);
            }
            // Freeform
            if ($(this).val() == 'freeform') {
                clipPath = new fabric.Rect({
                    fill: 'rgba(156, 39, 176, 0.3)',
                    width: originalWidth[canvasId] / 2,
                    height: originalHeight[canvasId] / 2,
                    excludeFromExport: true,
                    objectType: 'clipPath',
                });
                clipPath.controls = {
                    ...fabric.Rect.prototype.controls,
                    mtr: new fabric.Control({ visible: false })
                };
                canvas.add(clipPath);

                setClipPath();
            }
            // Custom
            else if ($(this).val() == 'custom') {
                selector.find(".crop-custom").css('display', 'flex');
                var width = parseInt(selector.find('#palleon-crop-width').val());
                var height = parseInt(selector.find('#palleon-crop-height').val());
                clipPath = new fabric.Rect({
                    fill: 'rgba(156, 39, 176, 0.3)',
                    width: width,
                    height: height,
                    excludeFromExport: true,
                    objectType: 'clipPath',
                });
                clipPath.controls = {
                    ...fabric.Rect.prototype.controls,
                    mtr: new fabric.Control({ visible: false }),
                    ml: new fabric.Control({ visible: false }),
                    mb: new fabric.Control({ visible: false }),
                    mr: new fabric.Control({ visible: false }),
                    mt: new fabric.Control({ visible: false }),
                    tl: new fabric.Control({ visible: false }),
                    bl: new fabric.Control({ visible: false }),
                    tr: new fabric.Control({ visible: false }),
                    br: new fabric.Control({ visible: false })
                };
                canvas.add(clipPath);

                setClipPath();
            }
            // Square
            else if ($(this).val() == 'square') {
                var squaresize = originalHeight[canvasId] / 2;
                if (originalWidth[canvasId] >= originalHeight[canvasId]) {
                    squaresize = originalWidth[canvasId] / 2;
                }

                clipPath = new fabric.Rect({
                    fill: 'rgba(156, 39, 176, 0.3)',
                    width: squaresize,
                    height: squaresize,
                    excludeFromExport: true,
                    objectType: 'clipPath',
                });
                clipPath.controls = {
                    ...fabric.Rect.prototype.controls,
                    mtr: new fabric.Control({ visible: false }),
                    ml: new fabric.Control({ visible: false }),
                    mb: new fabric.Control({ visible: false }),
                    mr: new fabric.Control({ visible: false }),
                    mt: new fabric.Control({ visible: false })
                };
                canvas.add(clipPath);

                setClipPath();
            }
            // Original
            else if ($(this).val() == 'original') {
                clipPath = new fabric.Rect({
                    fill: 'rgba(156, 39, 176, 0.3)',
                    width: originalWidth[canvasId] / 2,
                    height: originalHeight[canvasId] / 2,
                    excludeFromExport: true,
                    objectType: 'clipPath',
                });
                clipPath.controls = {
                    ...fabric.Rect.prototype.controls,
                    mtr: new fabric.Control({ visible: false }),
                    ml: new fabric.Control({ visible: false }),
                    mb: new fabric.Control({ visible: false }),
                    mr: new fabric.Control({ visible: false }),
                    mt: new fabric.Control({ visible: false })
                };
                canvas.add(clipPath);

                setClipPath();
            }else {
                var objects = canvas.getObjects();
                objects.filter(element => element.objectType != 'clipPath' && element.objectType != 'BG' && element.objectType != 'drawing').forEach(element => element.set('selectable', true));
                selector.find(".crop-custom").css('display', 'none');
                selector.find('#palleon-crop-btns').addClass('disabled');
                selector.find(".palleon-icon-panel-content ul.palleon-accordion > li, #palleon-icon-menu, #palleon-top-bar, #palleon-right-col").css('pointer-events', 'auto');
            }
        });

        /* Crop Cancel Button */
        selector.find('#palleon-crop-cancel').on("click", function(){
            selector.find('#palleon-crop-btns').addClass('disabled');
            selector.find('#palleon-crop-style').select2("enable");
            selector.find('#palleon-crop-style').val('');
            selector.find('#palleon-crop-style').trigger('change');
            canvas.remove(overlay);
            canvas.remove(clipPath);
        });

        /* Crop Apply Button */
        selector.find('#palleon-crop-apply').on("click", function(){
            var answer = window.confirm("Bạn có chắc muốn cắt ảnh?");
            if (answer) {
                selector.find('#palleon-crop-btns').addClass('disabled');
                selector.find('#palleon-crop-style').select2("enable");
                selector.find('#palleon-crop-style').val('');
                selector.find('#palleon-crop-style').trigger('change');
                canvas.setZoom(1);
                selector.find('#palleon-img-zoom').val(100);
                var clipPos = clipPath.getBoundingRect();
                canvas.setWidth(clipPos.width - 1);
                canvas.setHeight(clipPos.height - 1);

                canvas.backgroundImage.set({ top: -clipPos.top, left: -clipPos.left});

                canvas.remove(overlay);
                canvas.remove(clipPath);

                updateImage();

                // Wait for the placeholder image fully load
                selector.find('#palleon-canvas-img-wrap').imagesLoaded( function() {
                    originalWidth[canvasId] = canvas.width;
                    originalHeight[canvasId] = canvas.height;
                    rotate = 0;
                    originX = 'left';
                    originY = 'top';
                    scaleX = 1;
                    scaleY = 1;
                    setBackgroundImage();
                    setDimentions(canvas);
                    adjustZoom();
                    canvas.requestRenderAll();
                });
            }

        });

        /* Crop Width Input */
        selector.find('#palleon-crop-width').bind('input paste', function() {
            if (selector.find('#palleon-crop-lock').hasClass('active')) {
                var width = $(this).data('max');
                var height = selector.find('#palleon-crop-height').data('max');
                var ratio = width / height;
                selector.find('#palleon-crop-height').val(Math.round(this.value / ratio));
            }
            clipPath.set('width', parseInt($(this).val()));
            clipPath.set('height', parseInt(selector.find('#palleon-crop-height').val()));
            canvas.requestRenderAll();
        });

        /* Crop Height Input */
        selector.find('#palleon-crop-height').bind('input paste', function() {
            if (selector.find('#palleon-crop-lock').hasClass('active')) {
                var height = $(this).data('max');
                var width = selector.find('#palleon-crop-width').data('max');
                var ratio = height / width;
                selector.find('#palleon-crop-width').val(Math.round(this.value / ratio));
            }
            clipPath.set('height', parseInt($(this).val()));
            clipPath.set('width', parseInt(selector.find('#palleon-crop-width').val()));
            canvas.requestRenderAll();
        });

        /* Resize Canvas */
        function resizeCanvas() {
            var inputWidth = parseInt(selector.find('#palleon-resize-width').val());
            var inputHeight = parseInt(selector.find('#palleon-resize-height').val());

            originalWidth[canvasId] = inputWidth;
            originalHeight[canvasId] = inputHeight;

            canvas.setZoom(1);
            selector.find('#palleon-img-zoom').val(100);
            canvas.setWidth(inputWidth);
            canvas.setHeight(inputHeight);

            if (rotate == 0 || rotate == 180 || rotate == -180) {
                scaleX = canvas.width / selector.find('#palleon-canvas-img')[0].width;
                scaleY = canvas.height / selector.find('#palleon-canvas-img')[0].height;
            } else {
                scaleX = canvas.height / selector.find('#palleon-canvas-img')[0].width;
                scaleY = canvas.width / selector.find('#palleon-canvas-img')[0].height;
            }

            canvas.backgroundImage.set({ scaleX: scaleX, scaleY: scaleY});

            updateImage();

            // Wait for the placeholder image fully load
            selector.find('#palleon-canvas-img-wrap').imagesLoaded( function() {
                originalWidth[canvasId] = canvas.width;
                originalHeight[canvasId] = canvas.height;
                rotate = 0;
                originX = 'left';
                originY = 'top';
                scaleX = 1;
                scaleY = 1;
                setBackgroundImage();
                setDimentions(canvas);
                adjustZoom();
                canvas.requestRenderAll();
            });

        }

        /* Resize Canvas Button */
        selector.find('#palleon-resize-apply').on('click', function() {
            var answer = window.confirm("Bạn có chắc muốn thay đổi kích cỡ ảnh ?");
            if (answer) {
                resizeCanvas();
            }
        });

        /* Resize Width Input */
        selector.find('#palleon-resize-width').bind('input paste', function(){
            if (selector.find('#palleon-resize-lock').hasClass('active')) {
                var width = $(this).data('max');
                var height = selector.find('#palleon-resize-height').data('max');
                var ratio = width / height;
                selector.find('#palleon-resize-height').val(Math.round(this.value / ratio));
            }
        });

        /* Resize Height Input */
        selector.find('#palleon-resize-height').bind('input paste', function(){
            if (selector.find('#palleon-resize-lock').hasClass('active')) {
                var height = $(this).data('max');
                var width = selector.find('#palleon-resize-width').data('max');
                var ratio = height / width;
                selector.find('#palleon-resize-width').val(Math.round(this.value / ratio));
            }
        });

        /* Rotate Canvas */
        function rotateCanvas(direction) {
            if (rotate == 0 || rotate == 180 || rotate == -180) {
                canvas.setDimensions({width: originalHeight[canvasId], height: originalWidth[canvasId]});
                scaleX = canvas.height / img.width;
                scaleY = canvas.width / img.height;
            } else {
                canvas.setDimensions({width: originalWidth[canvasId], height: originalHeight[canvasId]});
                scaleX = canvas.width / img.width;
                scaleY = canvas.height / img.height;
            }
            if (direction == 'right') {
                if (rotate == 0) {
                    rotate = 90;
                    originX = 'left';
                    originY = 'bottom';
                } else if (rotate == 90) {
                    rotate = 180;
                    originX = 'right';
                    originY = 'bottom';
                } else if (rotate == 180) {
                    rotate = 270;
                    originX = 'right';
                    originY = 'top';
                } else if (rotate == 270) {
                    rotate = 0;
                    originX = 'left';
                    originY = 'top';
                } else if (rotate == -90) {
                    rotate = 0;
                    originX = 'left';
                    originY = 'top';
                } else if (rotate == -180) {
                    rotate = -90;
                    originX = 'right';
                    originY = 'top';
                } else if (rotate == -270) {
                    rotate = -180;
                    originX = 'right';
                    originY = 'bottom';
                }
            } else if (direction == 'left') {
                if (rotate == 0) {
                    rotate = -90;
                    originX = 'right';
                    originY = 'top';
                } else if (rotate == -90) {
                    rotate = -180;
                    originX = 'right';
                    originY = 'bottom';
                } else if (rotate == -180) {
                    rotate = -270;
                    originX = 'left';
                    originY = 'bottom';
                } else if (rotate == -270) {
                    rotate = 0;
                    originX = 'left';
                    originY = 'top';
                } else if (rotate == 90) {
                    rotate = 0;
                    originX = 'left';
                    originY = 'top';
                } else if (rotate == 180) {
                    rotate = 90;
                    originX = 'left';
                    originY = 'bottom';
                } else if (rotate == 270) {
                    rotate = 180;
                    originX = 'right';
                    originY = 'bottom';
                }
            }
            canvas.backgroundImage.set({ scaleX: scaleX, scaleY: scaleY, angle: rotate, originX: originX, originY: originY});

            setDimentions(canvas);
            adjustZoom();
            canvas.requestRenderAll();
            canvas.fire('palleon:history', { type: 'BG', text: palleonParams.rotated });
        }

        /* Rotate Right */
        selector.find('#palleon-rotate-right').on('click', function() {
            rotateCanvas('right');
        });

        /* Rotate Left */
        selector.find('#palleon-rotate-left').on('click', function() {
            rotateCanvas('left');
        });

        /* Flip X */
        selector.find('#palleon-flip-horizontal').on('click', function() {
            if( !canvas.backgroundImage ) return;
            canvas.backgroundImage.toggle('flipX');
            canvas.requestRenderAll();
            canvas.fire('palleon:history', { type: 'BG', text: palleonParams.flipped });
        });

        /* Flip Y */
        selector.find('#palleon-flip-vertical').on('click', function() {
            if( !canvas.backgroundImage ) return;
            canvas.backgroundImage.toggle('flipY');
            canvas.requestRenderAll();
            canvas.fire('palleon:history', { type: 'BG', text: palleonParams.flipped });
        });

        /* Brightness Toggle */
        selector.find('#palleon-brightness').on("change", function () {
            if( !canvas.backgroundImage ) return;
            if ($(this).is(":checked")) {
                canvas.backgroundImage.filters.push(new fabric.Image.filters.Brightness());
            } else {
                selector.find('#brightness').val(0);
                selector.find('#brightness').parent().parent().find('.slider-label span').html(0);
                canvas.backgroundImage.filters = canvas.backgroundImage.filters.filter(element => element.type != 'Brightness');
                canvas.backgroundImage.applyFilters();
            }
            canvas.requestRenderAll();
        });

        /* Brightness */
        selector.find('#brightness').on("input", function () {
            canvas.backgroundImage.filters.filter(element => element.type == 'Brightness').forEach(element => element.brightness = parseFloat(this.value));
            canvas.backgroundImage.applyFilters();
            canvas.requestRenderAll();
        });

        selector.find('#brightness').on("change", function (e) {
            if( !canvas.backgroundImage ) return;
            if (e.originalEvent){
                addToHistory(palleonParams.bg + ' ' + palleonParams.edited);
            }
        });

        /* Contrast Toggle */
        selector.find('#palleon-contrast').on("change", function () {
            if( !canvas.backgroundImage ) return;
            if ($(this).is(":checked")) {
                canvas.backgroundImage.filters.push(new fabric.Image.filters.Contrast());
            } else {
                selector.find('#contrast').val(0);
                selector.find('#contrast').parent().parent().find('.slider-label span').html(0);
                canvas.backgroundImage.filters = canvas.backgroundImage.filters.filter(element => element.type != 'Contrast');
                canvas.backgroundImage.applyFilters();
            }
            canvas.requestRenderAll();
        });

        /* Contrast */
        selector.find('#contrast').on("input", function () {
            canvas.backgroundImage.filters.filter(element => element.type == 'Contrast').forEach(element => element.contrast = parseFloat(this.value));
            canvas.backgroundImage.applyFilters();
            canvas.requestRenderAll();
        });

        selector.find('#contrast').on("change", function (e) {
            if( !canvas.backgroundImage ) return;
            if (e.originalEvent){
                addToHistory(palleonParams.bg + ' ' + palleonParams.edited);
            }
        });

        /* Saturation Toggle */
        selector.find('#palleon-saturation').on("change", function () {
            if( !canvas.backgroundImage ) return;
            if ($(this).is(":checked")) {
                canvas.backgroundImage.filters.push(new fabric.Image.filters.Saturation());
            } else {
                selector.find('#saturation').val(0);
                selector.find('#saturation').parent().parent().find('.slider-label span').html(0);
                canvas.backgroundImage.filters = canvas.backgroundImage.filters.filter(element => element.type != 'Saturation');
                canvas.backgroundImage.applyFilters();
            }
            canvas.requestRenderAll();
        });

        /* Saturation */
        selector.find('#saturation').on("input", function () {
            canvas.backgroundImage.filters.filter(element => element.type == 'Saturation').forEach(element => element.saturation = parseFloat(this.value));
            canvas.backgroundImage.applyFilters();
            canvas.requestRenderAll();
        });

        selector.find('#saturation').on("change", function (e) {
            if( !canvas.backgroundImage ) return;
            if (e.originalEvent){
                addToHistory(palleonParams.bg + ' ' + palleonParams.edited);
            }
        });

        /* Hue Toggle */
        selector.find('#palleon-hue').on("change", function () {
            if( !canvas.backgroundImage ) return;
            if ($(this).is(":checked")) {
                canvas.backgroundImage.filters.push(new fabric.Image.filters.HueRotation());
            } else {
                selector.find('#hue').val(0);
                selector.find('#hue').parent().parent().find('.slider-label span').html(0);
                canvas.backgroundImage.filters = canvas.backgroundImage.filters.filter(element => element.type != 'HueRotation');
                canvas.backgroundImage.applyFilters();
            }
            canvas.requestRenderAll();
        });

        /* Hue */
        selector.find('#hue').on("input", function () {
            if( !canvas.backgroundImage ) return;
            canvas.backgroundImage.filters.filter(element => element.type == 'HueRotation').forEach(element => element.rotation = parseFloat(this.value));
            canvas.backgroundImage.applyFilters();
            canvas.requestRenderAll();
        });

        selector.find('#hue').on("change", function (e) {
            if( !canvas.backgroundImage ) return;
            if (e.originalEvent){
                addToHistory(palleonParams.bg + ' ' + palleonParams.edited);
            }
        });

        /* Filters */
        selector.find('#palleon-filters input[type=checkbox]').on("change", function (e) {
            if( !canvas.backgroundImage ) return;
            if ($(this).is(":checked")) {
                if ($(this).attr('id') == 'grayscale') {
                    canvas.backgroundImage.filters.push(new fabric.Image.filters.Grayscale());
                } else if ($(this).attr('id') == 'sepia') {
                    canvas.backgroundImage.filters.push(new fabric.Image.filters.Sepia());
                } else if ($(this).attr('id') == 'blackwhite') {
                    canvas.backgroundImage.filters.push(new fabric.Image.filters.BlackWhite());
                } else if ($(this).attr('id') == 'brownie') {
                    canvas.backgroundImage.filters.push(new fabric.Image.filters.Brownie());
                } else if ($(this).attr('id') == 'vintage') {
                    canvas.backgroundImage.filters.push(new fabric.Image.filters.Vintage());
                } else if ($(this).attr('id') == 'kodachrome') {
                    canvas.backgroundImage.filters.push(new fabric.Image.filters.Kodachrome());
                } else if ($(this).attr('id') == 'technicolor') {
                    canvas.backgroundImage.filters.push(new fabric.Image.filters.Technicolor());
                } else if ($(this).attr('id') == 'polaroid') {
                    canvas.backgroundImage.filters.push(new fabric.Image.filters.Polaroid());
                } else if ($(this).attr('id') == 'shift') {
                    canvas.backgroundImage.filters.push(new fabric.Image.filters.Shift());
                } else if ($(this).attr('id') == 'invert') {
                    canvas.backgroundImage.filters.push(new fabric.Image.filters.Invert());
                } else if ($(this).attr('id') == 'sharpen') {
                    selector.find('#emboss').prop('checked', false);
                    selector.find('#sobelX').prop('checked', false);
                    selector.find('#sobelY').prop('checked', false);
                    canvas.backgroundImage.filters = canvas.backgroundImage.filters.filter(element => element.type != 'Convolute');
                    canvas.backgroundImage.filters.push(new fabric.Image.filters.Convolute({
                        matrix: [0,-1,0,-1,5,-1,0,-1,0]
                    }));
                } else if ($(this).attr('id') == 'emboss') {
                    selector.find('#sharpen').prop('checked', false);
                    selector.find('#sobelX').prop('checked', false);
                    selector.find('#sobelY').prop('checked', false);
                    canvas.backgroundImage.filters = canvas.backgroundImage.filters.filter(element => element.type != 'Convolute');
                    canvas.backgroundImage.filters.push(new fabric.Image.filters.Convolute({
                        matrix: [1,1,1,1,0.7,-1,-1,-1,-1]
                    }));
                } else if ($(this).attr('id') == 'sobelX') {
                    selector.find('#emboss').prop('checked', false);
                    selector.find('#sharpen').prop('checked', false);
                    selector.find('#sobelY').prop('checked', false);
                    canvas.backgroundImage.filters = canvas.backgroundImage.filters.filter(element => element.type != 'Convolute');
                    canvas.backgroundImage.filters.push(new fabric.Image.filters.Convolute({
                        matrix: [-1,0,1,-2,0,2,-1,0,1]
                    }));
                } else if ($(this).attr('id') == 'sobelY') {
                    selector.find('#emboss').prop('checked', false);
                    selector.find('#sharpen').prop('checked', false);
                    selector.find('#sobelX').prop('checked', false);
                    canvas.backgroundImage.filters = canvas.backgroundImage.filters.filter(element => element.type != 'Convolute');
                    canvas.backgroundImage.filters.push(new fabric.Image.filters.Convolute({
                        matrix: [-1,-2,-1,0,0,0,1,2,1]
                    }));
                }
            } else {
                if ($(this).attr('id') == 'grayscale') {
                    canvas.backgroundImage.filters = canvas.backgroundImage.filters.filter(element => element.type != 'Grayscale');
                } else if ($(this).attr('id') == 'sepia') {
                    canvas.backgroundImage.filters = canvas.backgroundImage.filters.filter(element => element.type != 'Sepia');
                } else if ($(this).attr('id') == 'blackwhite') {
                    canvas.backgroundImage.filters = canvas.backgroundImage.filters.filter(element => element.type != 'BlackWhite');
                } else if ($(this).attr('id') == 'brownie') {
                    canvas.backgroundImage.filters = canvas.backgroundImage.filters.filter(element => element.type != 'Brownie');
                } else if ($(this).attr('id') == 'vintage') {
                    canvas.backgroundImage.filters = canvas.backgroundImage.filters.filter(element => element.type != 'Vintage');
                } else if ($(this).attr('id') == 'kodachrome') {
                    canvas.backgroundImage.filters = canvas.backgroundImage.filters.filter(element => element.type != 'Kodachrome');
                } else if ($(this).attr('id') == 'technicolor') {
                    canvas.backgroundImage.filters = canvas.backgroundImage.filters.filter(element => element.type != 'Technicolor');
                } else if ($(this).attr('id') == 'polaroid') {
                    canvas.backgroundImage.filters = canvas.backgroundImage.filters.filter(element => element.type != 'Polaroid');
                } else if ($(this).attr('id') == 'shift') {
                    canvas.backgroundImage.filters = canvas.backgroundImage.filters.filter(element => element.type != 'Shift');
                } else if ($(this).attr('id') == 'invert') {
                    canvas.backgroundImage.filters = canvas.backgroundImage.filters.filter(element => element.type != 'Invert');
                } else if ($(this).attr('id') == 'sharpen') {
                    selector.find('#emboss').prop('checked', false);
                    selector.find('#sobelX').prop('checked', false);
                    selector.find('#sobelY').prop('checked', false);
                    canvas.backgroundImage.filters = canvas.backgroundImage.filters.filter(element => element.type != 'Convolute');
                } else if ($(this).attr('id') == 'emboss') {
                    selector.find('#sharpen').prop('checked', false);
                    selector.find('#sobelX').prop('checked', false);
                    selector.find('#sobelY').prop('checked', false);
                    canvas.backgroundImage.filters = canvas.backgroundImage.filters.filter(element => element.type != 'Convolute');
                } else if ($(this).attr('id') == 'sobelX') {
                    selector.find('#emboss').prop('checked', false);
                    selector.find('#sharpen').prop('checked', false);
                    selector.find('#sobelY').prop('checked', false);
                    canvas.backgroundImage.filters = canvas.backgroundImage.filters.filter(element => element.type != 'Convolute');
                } else if ($(this).attr('id') == 'sobelY') {
                    selector.find('#emboss').prop('checked', false);
                    selector.find('#sharpen').prop('checked', false);
                    selector.find('#sobelX').prop('checked', false);
                    canvas.backgroundImage.filters = canvas.backgroundImage.filters.filter(element => element.type != 'Convolute');
                }
            }
            canvas.backgroundImage.applyFilters();
            canvas.requestRenderAll();
            if (e.originalEvent){
                addToHistory(palleonParams.bg + ' ' + palleonParams.edited);
            }
        });

        /* Gamma Toggle */
        selector.find('#palleon-gamma').on("change", function () {
            if( !canvas.backgroundImage ) return;
            if ($(this).is(":checked")) {
                canvas.backgroundImage.filters.push(new fabric.Image.filters.Gamma());
            } else {
                selector.find('#gamma-red').val(1);
                selector.find('#gamma-red').parent().parent().find('.slider-label span').html(1);
                selector.find('#gamma-green').val(1);
                selector.find('#gamma-green').parent().parent().find('.slider-label span').html(1);
                selector.find('#gamma-blue').val(1);
                selector.find('#gamma-blue').parent().parent().find('.slider-label span').html(1);
                canvas.backgroundImage.filters = canvas.backgroundImage.filters.filter(element => element.type != 'Gamma');
                canvas.backgroundImage.applyFilters();
            }
            canvas.requestRenderAll();
        });

        /* Gamma Settings */
        selector.find('#palleon-gamma-settings input').on("input", function () {
            var v1 = parseFloat($('#gamma-red').val());
            var v2 = parseFloat($('#gamma-green').val());
            var v3 = parseFloat($('#gamma-blue').val());
            var gammaArray = [v1, v2, v3];
            canvas.backgroundImage.filters.filter(element => element.type == 'Gamma').forEach(element => element.gamma = gammaArray);
            canvas.backgroundImage.applyFilters();
            canvas.requestRenderAll();
        });

        selector.find('#palleon-gamma-settings input').on("change", function (e) {
            if( !canvas.backgroundImage ) return;
            if (e.originalEvent){
                addToHistory(palleonParams.bg + ' ' + palleonParams.edited);
            }
        });

        /* Blur Toggle */
        selector.find('#palleon-blur').on("change", function () {
            if( !canvas.backgroundImage ) return;
            if ($(this).is(":checked")) {
                canvas.backgroundImage.filters.push(new fabric.Image.filters.Blur());
            } else {
                selector.find('#blur').val(0);
                selector.find('#blur').parent().parent().find('.slider-label span').html(0);
                canvas.backgroundImage.filters = canvas.backgroundImage.filters.filter(element => element.type != 'Blur');
                canvas.backgroundImage.applyFilters();
            }
            canvas.requestRenderAll();
        });

        /* Blur */
        selector.find('#blur').on("change", function (e) {
            if( !canvas.backgroundImage ) return;
            canvas.backgroundImage.filters.filter(element => element.type == 'Blur').forEach(element => element.blur = parseFloat(this.value));
            canvas.backgroundImage.applyFilters();
            canvas.requestRenderAll();
            if (e.originalEvent){
                addToHistory(palleonParams.bg + ' ' + palleonParams.edited);
            }
        });

        /* Noise Toggle */
        selector.find('#palleon-noise').on("change", function () {
            if( !canvas.backgroundImage ) return;
            if ($(this).is(":checked")) {
                canvas.backgroundImage.filters.push(new fabric.Image.filters.Noise());
            } else {
                selector.find('#noise').val(0);
                selector.find('#noise').parent().parent().find('.slider-label span').html(0);
                canvas.backgroundImage.filters = canvas.backgroundImage.filters.filter(element => element.type != 'Noise');
                canvas.backgroundImage.applyFilters();
            }
            canvas.requestRenderAll();
        });

        /* Noise */
        selector.find('#noise').on("input", function () {
            canvas.backgroundImage.filters.filter(element => element.type == 'Noise').forEach(element => element.noise = parseInt(this.value, 10));
            canvas.backgroundImage.applyFilters();
            canvas.requestRenderAll();
        });

        selector.find('#noise').on("change", function (e) {
            if( !canvas.backgroundImage ) return;
            if (e.originalEvent){
                addToHistory(palleonParams.bg + ' ' + palleonParams.edited);
            }
        });

        /* Pixelate Toggle */
        selector.find('#palleon-pixelate').on("change", function () {
            if( !canvas.backgroundImage ) return;
            if ($(this).is(":checked")) {
                canvas.backgroundImage.filters.push(new fabric.Image.filters.Pixelate());
            } else {
                selector.find('#pixelate').val(1);
                selector.find('#pixelate').parent().parent().find('.slider-label span').html(1);
                canvas.backgroundImage.filters = canvas.backgroundImage.filters.filter(element => element.type != 'Pixelate');
                canvas.backgroundImage.applyFilters();
            }
            canvas.requestRenderAll();
        });

        /* Pixelate */
        selector.find('#pixelate').on("change", function (e) {
            canvas.backgroundImage.filters.filter(element => element.type == 'Pixelate').forEach(element => element.blocksize = parseInt(this.value, 10));
            canvas.backgroundImage.applyFilters();
            canvas.requestRenderAll();
            if (e.originalEvent){
                addToHistory(palleonParams.bg + ' ' + palleonParams.edited);
            }
        });

        /* Blend Color Toggle */
        selector.find('#palleon-blend-color').on("change", function () {
            if( !canvas.backgroundImage ) return;
            if ($(this).is(":checked")) {
                var mode = selector.find('#blend-color-mode').val();
                var color = selector.find('#blend-color-color').val();
                var alpha = parseFloat(selector.find('#blend-color-alpha').val());
                canvas.backgroundImage.filters.push(new fabric.Image.filters.BlendColor());
                canvas.backgroundImage.filters.filter(element => element.type == 'BlendColor').forEach(element => element.mode = mode,element => element.color = color,element => element.alpha = parseFloat(alpha));
            } else {
                selector.find('#blend-color-mode').val('add');
                selector.find('#blend-color-color').spectrum("set", "#ffffff");
                selector.find('#blend-color-alpha').val(0.5);
                selector.find('#blend-color-alpha').parent().parent().find('.slider-label span').html(0.5);
                canvas.backgroundImage.filters = canvas.backgroundImage.filters.filter(element => element.type != 'BlendColor');
            }
            canvas.backgroundImage.applyFilters();
            canvas.requestRenderAll();
        });

        /* Blend Mode */
        selector.find('#blend-color-mode').on("change", function () {
            if( !canvas.backgroundImage ) return;
            canvas.backgroundImage.filters.filter(element => element.type == 'BlendColor').forEach(element => element.mode = this.value);
            canvas.backgroundImage.applyFilters();
            canvas.requestRenderAll();
        });

        /* Blend Color */
        selector.find('#blend-color-color').on("change", function () {
            if( !canvas.backgroundImage ) return;
            canvas.backgroundImage.filters.filter(element => element.type == 'BlendColor').forEach(element => element.color = this.value);
            canvas.backgroundImage.applyFilters();
            canvas.requestRenderAll();
        });

        /* Blend Alpha */
        selector.find('#blend-color-alpha').on("input", function () {
            canvas.backgroundImage.filters.filter(element => element.type == 'BlendColor').forEach(element => element.alpha = parseFloat(this.value));
            canvas.backgroundImage.applyFilters();
            canvas.requestRenderAll();
        });

        /* Duotone Toggle */
        selector.find('#palleon-duotone-color').on("change", function () {
            if( !canvas.backgroundImage ) return;
            if ($(this).is(":checked")) {
                duotoneFilter = new fabric.Image.filters.Composed({
                    subFilters: [
                      new fabric.Image.filters.Grayscale({ mode: 'luminosity' }), // make it black and white
                      new fabric.Image.filters.BlendColor({ color: selector.find('#duotone-light-color').val() }), // apply light color
                      new fabric.Image.filters.BlendColor({ color: selector.find('#duotone-dark-color').val(), mode: 'lighten' }), // apply a darker color
                    ]
                });
                canvas.backgroundImage.filters.push(duotoneFilter);
            } else {
                selector.find('#duotone-light-color').spectrum("set", "green");
                selector.find('#duotone-dark-color').spectrum("set", "blue");
                canvas.backgroundImage.filters = canvas.backgroundImage.filters.filter(element => element.type != 'Composed');
            }
            canvas.backgroundImage.applyFilters();
            canvas.requestRenderAll();
        });

        /* Duotone Light Color */
        selector.find('#duotone-light-color').on("change", function () {
            if( !canvas.backgroundImage ) return;
            canvas.backgroundImage.filters = canvas.backgroundImage.filters.filter(element => element.type != 'Composed');
            canvas.backgroundImage.filters.push(duotoneFilter);
            duotoneFilter.subFilters[1].color = $(this).val();
            canvas.backgroundImage.applyFilters();
            canvas.requestRenderAll();
        });

        /* Duotone Dark Color */
        selector.find('#duotone-dark-color').on("change", function () {
            if( !canvas.backgroundImage ) return;
            canvas.backgroundImage.filters = canvas.backgroundImage.filters.filter(element => element.type != 'Composed');
            canvas.backgroundImage.filters.push(duotoneFilter);
            duotoneFilter.subFilters[2].color = $(this).val();
            canvas.backgroundImage.applyFilters();
            canvas.requestRenderAll();
        });

        /* Swap Colors Apply */
        selector.find('#palleon-swap-apply').on('click', function() {
            var swapColors = new fabric.Image.filters.SwapColor({
                colorSource: selector.find('#color-source').val(),
                colorDestination: selector.find('#color-destination').val(),
            });
            canvas.backgroundImage.filters.push(swapColors);
            canvas.backgroundImage.applyFilters();
            canvas.requestRenderAll();
            $(this).prop('disabled', true);
            selector.find('#palleon-swap-remove').prop('disabled', false);
        });

        /* Swap Colors Remove */
        selector.find('#palleon-swap-remove').on('click', function() {
            canvas.backgroundImage.filters = canvas.backgroundImage.filters.filter(element => element.type != 'SwapColor');
            canvas.backgroundImage.applyFilters();
            canvas.requestRenderAll();
            $(this).prop('disabled', true);
            selector.find('#palleon-swap-apply').prop('disabled', false);
        });

        /* Swap Colors Toggle */
        selector.find('#palleon-swap-colors').on("change", function () {
            if( !canvas.backgroundImage ) return;
            if (!$(this).is(":checked")) {
                selector.find('#palleon-swap-remove').trigger('click');
            }
        });

        /* Shadow Fields */
        var shadowFields = ['text', 'image', 'shape', 'element', 'customSVG'];

        $.each(shadowFields, function( index, value ) {
            selector.find('#palleon-' + value + '-shadow').on("change", function () {
                if( !canvas.getActiveObject() ) return;
                var shadow = new fabric.Shadow({
                    color: selector.find('#' + value + '-shadow-color').val(),
                    blur: selector.find('#' + value + '-shadow-blur').val(),
                    offsetX: selector.find('#' + value + '-shadow-offset-x').val(),
                    offsetY: selector.find('#' + value + '-shadow-offset-y').val(),
                });
                if ($(this).is(":checked")) {
                    canvas.getActiveObject().shadow = shadow;
                } else {
                    canvas.getActiveObject().shadow = null;
                }
                canvas.requestRenderAll();
            });
            selector.find('#' + value + '-shadow-color').bind("change", function () {
                canvas.getActiveObject().shadow.color = $(this).val();
                canvas.requestRenderAll();
            });
            selector.find('#' + value + '-shadow-settings input[type=number]').bind("input paste keyup keydown", function () {
                var val = $(this).val();
                if ($(this).attr('id') == value + '-shadow-blur') {
                    canvas.getActiveObject().shadow.blur = parseInt(val);
                } else if ($(this).attr('id') == value + '-shadow-offset-x') {
                    canvas.getActiveObject().shadow.offsetX = parseInt(val);
                } else if ($(this).attr('id') == value + '-shadow-offset-y') {
                    canvas.getActiveObject().shadow.offsetY = parseInt(val);
                }
                canvas.requestRenderAll();
            });
        });

        /* Gradient Fields */
        function updateGradient(value) {
            let color = selector.find('#palleon-' + value + '-gradient').val();
            var obj = canvas.getActiveObject();
            if( !obj ) return;
          	var i = 0;
            obj.set('gradientFill', color);
            var colorStops = '';
            if (selector.find('#' + value + '-gradient-color-3').val() == '' && selector.find('#' + value + '-gradient-color-4').val() == '') {
                colorStops = [
                    { offset: 0, color: selector.find('#' + value + '-gradient-color-1').val() },
                    { offset: 1, color: selector.find('#' + value + '-gradient-color-2').val() }
                ];
            } else if (selector.find('#' + value + '-gradient-color-3').val() != '' && selector.find('#' + value + '-gradient-color-4').val() == '') {
                colorStops = [
                    { offset: 0, color: selector.find('#' + value + '-gradient-color-1').val() },
                    { offset: 0.5, color: selector.find('#' + value + '-gradient-color-2').val() },
                    { offset: 1, color: selector.find('#' + value + '-gradient-color-3').val() }
                ];
            } else if (selector.find('#' + value + '-gradient-color-1').val() != '' && selector.find('#' + value + '-gradient-color-2').val() != '' && selector.find('#' + value + '-gradient-color-3').val() != '' && selector.find('#' + value + '-gradient-color-4').val() != '') {
                colorStops = [
                    { offset: 0, color: selector.find('#' + value + '-gradient-color-1').val() },
                    { offset: 0.25, color: selector.find('#' + value + '-gradient-color-2').val() },
                    { offset: 0.75, color: selector.find('#' + value + '-gradient-color-3').val() },
                    { offset: 1, color: selector.find('#' + value + '-gradient-color-4').val() }
                ];
            }
            if (selector.find('#palleon-' + value + '-gradient').val() == 'vertical') {
                selector.find('#' + value + '-gradient-settings').show();
                selector.find('#' + value + '-fill-color').hide();
                obj.set('fill', new fabric.Gradient({
                    type: 'linear',
                    gradientUnits: 'percentage',
                    coords: { x1: 0, y1: 0, x2: 0, y2: 1 },
                    colorStops: colorStops
                }));
                if (obj.objectType == 'element' || obj.objectType == 'customSVG') {
                    if (obj._objects) {
                        for (i = 0; i < obj._objects.length; i++) {
                            if (obj._objects[i].fill != '') {
                                obj._objects[i].set({
                                fill: new fabric.Gradient({
                                    type: 'linear',
                                    gradientUnits: 'percentage',
                                    coords: { x1: 0, y1: 0, x2: 0, y2: 1 },
                                    colorStops: colorStops
                                })
                                });
                            }
                        }
                    }
                }
            } else if (selector.find('#palleon-' + value + '-gradient').val() == 'horizontal') {
                selector.find('#' + value + '-gradient-settings').show();
                selector.find('#' + value + '-fill-color').hide();
                obj.set('fill', new fabric.Gradient({
                    type: 'linear',
                    gradientUnits: 'percentage',
                    coords: { x1: 0, y1: 0, x2: 1, y2: 0 },
                    colorStops: colorStops
                }));
                if (obj.objectType == 'element' || obj.objectType == 'customSVG') {
                    if (obj._objects) {
                        for (i = 0; i < obj._objects.length; i++) {
                            if (obj._objects[i].fill != '') {
                                obj._objects[i].set({
                                fill: new fabric.Gradient({
                                    type: 'linear',
                                    gradientUnits: 'percentage',
                                    coords: { x1: 0, y1: 0, x2: 1, y2: 0 },
                                    colorStops: colorStops
                                })
                                });
                            }
                        }
                    }
                }
            } else {
                selector.find('#' + value + '-gradient-settings').hide();
                selector.find('#' + value + '-fill-color').show();
                obj.set('fill', selector.find('#palleon-' + value + '-color').val());
                if (obj.objectType == 'element' || obj.objectType == 'customSVG') {
                    if (obj._objects) {
                        for (i = 0; i < obj._objects.length; i++) {
                            if (obj._objects[i].fill != '') {
                                obj._objects[i].set('fill', selector.find('#palleon-' + value + '-color').val());
                            }
                        }
                    }
                }
            }
            canvas.requestRenderAll();
        }

        var gradientFields = ['text', 'shape', 'element', 'customSVG'];

        $.each(gradientFields, function( index, value ) {
            selector.find('#palleon-' + value + '-gradient').on("change", function () {
                updateGradient(value);
            });
            selector.find('#' + value + '-gradient-color-1').on("change", function () {
                updateGradient(value);
            });
            selector.find('#' + value + '-gradient-color-2').on("change", function () {
                updateGradient(value);
            });
            selector.find('#' + value + '-gradient-color-3').on("change", function () {
                updateGradient(value);
            });
            selector.find('#' + value + '-gradient-color-4').on("change", function () {
                updateGradient(value);
            });
        });

        /* Get Scaled Size */
        function getScaledSize() {
            var width = canvas.backgroundImage.getScaledHeight();
            var height = canvas.backgroundImage.getScaledWidth();
            if (rotate == 0 || rotate == 180 || rotate == -180) {
                width = canvas.backgroundImage.getScaledWidth();
                height = canvas.backgroundImage.getScaledHeight();
            }
            return [width, height];
        }

        /* Add Text */
        selector.find('#palleon-add-text').on('click', function() {
            var text = new fabric.Textbox(palleonParams.textbox,{
                objectType: 'textbox',
                gradientFill: 'none',
                fontSize: settings.fontSize,
                fontFamily: settings.fontFamily,
                fontWeight: settings.fontWeight,
                fontStyle: settings.fontStyle,
                lineHeight: settings.lineHeight,
                fill: settings.fill,
                stroke: settings.stroke,
                strokeWidth: settings.strokeWidth,
                textBackgroundColor: settings.textBackgroundColor,
                textAlign: settings.textAlign,
                width: getScaledSize()[0] / 2,
                top: getScaledSize()[1] / 2,
                left: getScaledSize()[0] / 2,
                originX: 'center',
                originY: 'center'
            });
            /*text.controls = {
                ...fabric.Rect.prototype.controls,
                //ml: new fabric.Control({ visible: false }),
                //mr: new fabric.Control({ visible: false }),
                mb: new fabric.Control({ visible: false }),
                mt: new fabric.Control({ visible: false }),
                mtr: new fabric.Control({ visible: false })
            };*/
            text.perPixelTargetFind = false;
            canvas.add(text);
            canvas.setActiveObject(text);
            canvas.fire('palleon:history', { type: 'textbox', text: palleonParams.added });
        });


        /* Set Text Settings */
        function setTextSettings(text) {
            selector.find('#palleon-text-input').val(text.text);
            selector.find('#palleon-font-family').val(text.fontFamily);
            selector.find('#palleon-font-family').trigger('change');

            if (text.gradientFill == 'none') {
                selector.find('#palleon-text-gradient').val('none');
                selector.find('#palleon-text-color').spectrum("set", text.fill);
            } else if (text.gradientFill == 'vertical') {
                selector.find('#palleon-text-gradient').val('vertical');
                if (text.fill.colorStops.length == 4) {
                    selector.find('#text-gradient-color-1').spectrum("set", text.fill.colorStops[0].color);
                    selector.find('#text-gradient-color-2').spectrum("set", text.fill.colorStops[1].color);
                    selector.find('#text-gradient-color-3').spectrum("set", text.fill.colorStops[2].color);
                    selector.find('#text-gradient-color-4').spectrum("set", text.fill.colorStops[3].color);
                } else if (text.fill.colorStops.length == 3) {
                    selector.find('#text-gradient-color-1').spectrum("set", text.fill.colorStops[0].color);
                    selector.find('#text-gradient-color-2').spectrum("set", text.fill.colorStops[1].color);
                    selector.find('#text-gradient-color-3').spectrum("set", text.fill.colorStops[2].color);
                    selector.find('#text-gradient-color-4').spectrum("set", '');
                } else if (text.fill.colorStops.length == 2) {
                    selector.find('#text-gradient-color-1').spectrum("set", text.fill.colorStops[0].color);
                    selector.find('#text-gradient-color-2').spectrum("set", text.fill.colorStops[1].color);
                    selector.find('#text-gradient-color-3').spectrum("set", '');
                    selector.find('#text-gradient-color-4').spectrum("set", '');
                }
            } else if (text.gradientFill == 'horizontal') {
                selector.find('#palleon-text-gradient').val('horizontal');
                if (text.fill.colorStops.length == 4) {
                    selector.find('#text-gradient-color-1').spectrum("set", text.fill.colorStops[0].color);
                    selector.find('#text-gradient-color-2').spectrum("set", text.fill.colorStops[1].color);
                    selector.find('#text-gradient-color-3').spectrum("set", text.fill.colorStops[2].color);
                    selector.find('#text-gradient-color-4').spectrum("set", text.fill.colorStops[3].color);
                } else if (text.fill.colorStops.length == 3) {
                    selector.find('#text-gradient-color-1').spectrum("set", text.fill.colorStops[0].color);
                    selector.find('#text-gradient-color-2').spectrum("set", text.fill.colorStops[1].color);
                    selector.find('#text-gradient-color-3').spectrum("set", text.fill.colorStops[2].color);
                    selector.find('#text-gradient-color-4').spectrum("set", '');
                } else if (text.fill.colorStops.length == 2) {
                    selector.find('#text-gradient-color-1').spectrum("set", text.fill.colorStops[0].color);
                    selector.find('#text-gradient-color-2').spectrum("set", text.fill.colorStops[1].color);
                    selector.find('#text-gradient-color-3').spectrum("set", '');
                    selector.find('#text-gradient-color-4').spectrum("set", '');
                }
            }
            selector.find('#palleon-text-gradient').trigger('change');

            if (text.fontWeight == 'bold') {
                selector.find("#format-bold").addClass('active');
            } else {
                selector.find("#format-bold").removeClass('active');
            }
            if (text.fontStyle == 'italic') {
                selector.find("#format-italic").addClass('active');
            } else {
                selector.find("#format-italic").removeClass('active');
            }
            if (text.underline == true) {
                selector.find("#format-underline").addClass('active');
            } else {
                selector.find("#format-underline").removeClass('active');
            }
            if (text.textAlign == 'left') {
                selector.find('.format-align').removeClass('active');
                selector.find('#format-align-left').addClass('active');
            }
            if (text.textAlign == 'right') {
                selector.find('.format-align').removeClass('active');
                selector.find('#format-align-right').addClass('active');
            }
            if (text.textAlign == 'center') {
                selector.find('.format-align').removeClass('active');
                selector.find('#format-align-center').addClass('active');
            }
            if (text.textAlign == 'justify') {
                selector.find('.format-align').removeClass('active');
                selector.find('#format-align-justify').addClass('active');
            }

            selector.find('#palleon-font-size').val(text.fontSize);
            selector.find('#palleon-outline-size').val(text.strokeWidth);
            selector.find('#palleon-line-height').val(text.lineHeight);
            selector.find('#palleon-letter-spacing').val(text.charSpacing);
            selector.find('#palleon-outline-color').spectrum("set", text.stroke);
            selector.find('#palleon-text-background').spectrum("set", text.textBackgroundColor);

            if (text.shadow == null) {
                selector.find('#palleon-text-shadow').prop('checked', false);
            } else {
                selector.find('#palleon-text-shadow').prop('checked', true);
                selector.find('#text-shadow-color').spectrum("set", text.shadow.color);
                selector.find('#text-shadow-blur').val(text.shadow.blur);
                selector.find('#text-shadow-offset-x').val(text.shadow.offsetX);
                selector.find('#text-shadow-offset-y').val(text.shadow.offsetY);
            }
            selector.find('#palleon-text-shadow').trigger('change');

            if (text.flipX == true) {
                selector.find("#text-flip-x").addClass('active');
            } else {
                selector.find("#text-flip-x").removeClass('active');
            }

            if (text.flipY == true) {
                selector.find("#text-flip-y").addClass('active');
            } else {
                selector.find("#text-flip-y").removeClass('active');
            }

            selector.find('#text-skew-x').val(text.skewX);
            selector.find('#text-skew-x').parent().parent().find('.slider-label span').html(text.skewX);
            selector.find('#text-skew-y').val(text.skewY);
            selector.find('#text-skew-y').parent().parent().find('.slider-label span').html(text.skewY);
            selector.find('#text-rotate').val(parseInt(text.angle));
            selector.find('#text-rotate').parent().parent().find('.slider-label span').html(parseInt(text.angle));
        }

        /* Text Input */
        selector.find('#palleon-text-input').bind('input paste', function(){
            setValueActiveObject("text", $(this).val());
            selector.find("#palleon-layers-"+canvasId+" #" + canvas.getActiveObject().id + " .layer-name").html(canvas.getActiveObject().text);
            canvas.requestRenderAll();
        });

        selector.find('#palleon-text-input').bind('focusout', function(){
            canvas.fire('palleon:history', { type: 'textbox', text: palleonParams.edited});
        });

        /* Font Family */
        selector.find('#palleon-font-family').on('change', function() {
            var font = $(this).val();
            var loadFonts = 'yes';
            for (var i = 0; i < webSafeFonts.length; i++) {
                if (webSafeFonts[i][1] == font) {
                    loadFonts = 'no';
                    break;
                }
            }
            if (loadFonts == 'yes') {
                if (typeof palleonCustomFonts === 'undefined') {
                    WebFont.load({
                        google: {
                        families: [font + ':regular,bold', font + ':italic,regular,bold']
                        }
                    });
                } else {
                    if (!palleonCustomFonts.fonts.includes(font)) {
                        WebFont.load({
                            google: {
                            families: [font + ':regular,bold', font + ':italic,regular,bold']
                            }
                        });
                    }
                }
                var fontNormal = new FontFaceObserver(font, {
                    weight: 'normal',
                    style: 'normal'
                  });
                var fontBold = new FontFaceObserver(font, {
                    weight: 'bold',
                    style: 'normal'
                });
                var fontNormalItalic = new FontFaceObserver(font, {
                    weight: 'normal',
                    style: 'italic'
                  });
                var fontBoldItalic = new FontFaceObserver(font, {
                    weight: 'bold',
                    style: 'italic'
                });
                Promise.all([fontNormal.load(null, 5000), fontBold.load(null, 5000), fontNormalItalic.load(null, 5000), fontBoldItalic.load(null, 5000)]).then(function () {
                    setValueActiveObject("fontFamily", font);
                    canvas.requestRenderAll();
                }).catch(function(e) {
                    console.log(e);
                });
            } else {
                setValueActiveObject("fontFamily", font);
                canvas.requestRenderAll();
            }
        });

        /* Text Format Buttons */
        selector.find("#palleon-text-format-btns > .palleon-btn").on('click', function () {
            if ($(this).hasClass('active')) {
                if ($(this).attr('id') == 'format-bold') {
                    setValueActiveObject("fontWeight", 'normal');
                    $(this).removeClass('active');
                }
                if ($(this).attr('id') == 'format-italic') {
                    setValueActiveObject("fontStyle", 'normal');
                    $(this).removeClass('active');
                }
                if ($(this).attr('id') == 'format-underlined') {
                    setValueActiveObject("underline", false);
                    $(this).removeClass('active');
                }
            } else {
                if ($(this).attr('id') == 'format-bold') {
                    setValueActiveObject("fontWeight", 'bold');
                }
                if ($(this).attr('id') == 'format-italic') {
                    setValueActiveObject("fontStyle", 'italic');
                }
                if ($(this).attr('id') == 'format-underlined') {
                    setValueActiveObject("underline", true);
                }
                if ($(this).attr('id') == 'format-align-left') {
                    setValueActiveObject("textAlign", 'left');
                }
                if ($(this).attr('id') == 'format-align-right') {
                    setValueActiveObject("textAlign", 'right');
                }
                if ($(this).attr('id') == 'format-align-center') {
                    setValueActiveObject("textAlign", 'center');
                }
                if ($(this).attr('id') == 'format-align-justify') {
                    setValueActiveObject("textAlign", 'justify');
                }

                selector.find('.format-align').removeClass('active');
                $(this).addClass('active');
            }
            canvas.requestRenderAll();
            canvas.fire('palleon:history', { type: 'textbox', text: palleonParams.edited});
        });

        /* Text Numeric Fields */
        selector.find('#palleon-text-settings input[type=number]').bind('input paste keyup keydown', function() {
            var val = $(this).val();
            if ($(this).attr('id') == 'palleon-font-size') {
                setValueActiveObject("fontSize", parseInt(val));
            } else if ($(this).attr('id') == 'palleon-outline-size') {
                setValueActiveObject("strokeWidth", parseInt(val));
            } else if ($(this).attr('id') == 'palleon-line-height') {
                setValueActiveObject("lineHeight", parseFloat(val));
            } else if ($(this).attr('id') == 'palleon-letter-spacing') {
                setValueActiveObject("charSpacing", parseInt(val));
            }
            canvas.requestRenderAll();
        });

        selector.find('#palleon-text-settings input[type=number]').bind('input', function() {
            window.clearTimeout(timeOut);
            timeOut = setTimeout(function(){
                canvas.fire('palleon:history', { type: 'textbox', text: palleonParams.edited});
            }, 500);
        });

        /* Text Color Fields */
        selector.find('#palleon-text-settings .palleon-colorpicker').bind('change', function() {
            setTextColor(this, true);
        });
        function setTextColor(self, his) {
            var val = $(self).val();
            if ($(self).attr('id') == 'palleon-text-color') {
                setValueActiveObject("fill", val);
            } else if ($(self).attr('id') == 'palleon-outline-color') {
                setValueActiveObject("stroke", val);
            } else if ($(self).attr('id') == 'palleon-text-background') {
                setValueActiveObject("textBackgroundColor", val);
            }
            canvas.requestRenderAll();
            if( his ) canvas.fire('palleon:history', { type: 'textbox', text: palleonParams.edited});
        }


        /* Text Flip Buttons */
        selector.find("#palleon-text-flip-btns > .palleon-btn").on('click', function () {
            if ($(this).hasClass('active')) {
                if ($(this).attr('id') == 'text-flip-x') {
                    setValueActiveObject("flipX", false);
                } else if ($(this).attr('id') == 'text-flip-y') {
                    setValueActiveObject("flipY", false);
                }
                $(this).removeClass('active');
            } else {
                if ($(this).attr('id') == 'text-flip-x') {
                    setValueActiveObject("flipX", true);
                } else if ($(this).attr('id') == 'text-flip-y') {
                    setValueActiveObject("flipY", true);
                }
                $(this).addClass('active');
            }
            canvas.requestRenderAll();
            canvas.fire('palleon:history', { type: 'textbox', text: palleonParams.edited});
        });

        /* Text Skew, Rotate, Opacity */
        selector.find('#palleon-text-settings input[type=range]').bind('input click', function() {
            var val = $(this).val();
            if ($(this).attr('id') == 'text-skew-x') {
                setValueActiveObject("skewX", parseInt(val));
            } else if ($(this).attr('id') == 'text-skew-y') {
                setValueActiveObject("skewY", parseInt(val));
            } else if ($(this).attr('id') == 'text-rotate') {
                setValueActiveObject("angle", parseInt(val));
            } else if ($(this).attr('id') == 'text-opacity') {
                setValueActiveObject("opacity", parseFloat(val));
            }
            canvas.requestRenderAll();
        });

        selector.find('#palleon-text-settings input[type=range]').bind('change', function() {
            canvas.fire('palleon:history', { type: 'textbox', text: palleonParams.edited});
        });

        /* Select2 icon support */
        function select2format(icon) {
            var originalOption = icon.element;
            if ($(originalOption).data('icon')) {
                return $('<div class="select2-item"><span class="material-icons">' + $(originalOption).data('icon') + '</span>' + icon.text + '</div>');
            } else {
                return $('<div class="select2-item">' + icon.text + '</div>');
            }
        }

        /* Set Image Settings */
        function setImageSettings(img) {
            selector.find('#img-border-radius').val(img.roundedCorders);
            selector.find('#img-border-radius').parent().parent().find('.slider-label span').html(img.roundedCorders);
            if (img.shadow == null) {
                selector.find('#palleon-image-shadow').prop('checked', false);
            } else {
                selector.find('#palleon-image-shadow').prop('checked', true);
                selector.find('#image-shadow-color').spectrum("set", img.shadow.color);
                selector.find('#image-shadow-blur').val(img.shadow.blur);
                selector.find('#image-shadow-offset-x').val(img.shadow.offsetX);
                selector.find('#image-shadow-offset-y').val(img.shadow.offsetY);
            }
            selector.find('#palleon-image-shadow').trigger('change');
            selector.find('#img-border-width').val(img.strokeWidth);
            selector.find('#img-border-color').spectrum("set", img.stroke);
            selector.find('#img-opacity').val(img.opacity);
            selector.find('#img-opacity').parent().parent().find('.slider-label span').html(img.opacity);
            selector.find('#img-skew-x').val(img.skewX);
            selector.find('#img-skew-x').parent().parent().find('.slider-label span').html(img.skewX);
            selector.find('#img-skew-y').val(img.skewY);
            selector.find('#img-skew-y').parent().parent().find('.slider-label span').html(img.skewY);
            selector.find('#img-rotate').val(parseInt(img.angle));
            selector.find('#img-rotate').parent().parent().find('.slider-label span').html(parseInt(img.angle));
        }

        /* Upload Image */
        selector.find('#palleon-img-upload').on('change', function (e) {
            var reader = new FileReader();
            reader.onload = function (event) {
                var imgObj = new Image();
                    convertToDataURL(event.target.result, function(dataUrl) {
                        imgObj.src = dataUrl;
                        imgObj.onload = function () {
                            var image = new fabric.Image(imgObj);
                            image.set({
                                objectType: 'image',
                                roundedCorders: 0,
                                stroke: '#fff',
                                strokeWidth: 0,
                                top: getScaledSize()[1] / 2,
                                left: getScaledSize()[0] / 2,
                                originX: 'center',
                                originY: 'center'
                            });
                            image.perPixelTargetFind = true;
                            canvas.add(image);
                            image.scaleToWidth(getScaledSize()[0] / 2);
                            if (image.isPartiallyOnScreen()) {
                                image.scaleToHeight(getScaledSize()[1] / 2);
                            }
                            canvas.setActiveObject(image);
                            canvas.requestRenderAll();
                        };
                    });
            };
            reader.readAsDataURL(e.target.files[0]);
            canvas.fire('palleon:history', { type: 'image', text: palleonParams.added });
        });

        /* Image Flip X */
        selector.find('#img-flip-horizontal').on('click', function() {
            canvas.getActiveObject().toggle('flipX');
            canvas.requestRenderAll();
            canvas.fire('palleon:history', { type: 'image', text: palleonParams.edited });
        });

        /* Image Flip Y */
        selector.find('#img-flip-vertical').on('click', function() {
            canvas.getActiveObject().toggle('flipY');
            canvas.requestRenderAll();
            canvas.fire('palleon:history', { type: 'image', text: palleonParams.edited });
        });

        /* Rounded Corners */
        var roundedCorners = (fabricObject, cornerRadius) => new fabric.Rect({
            width: fabricObject.width,
            height: fabricObject.height,
            rx: cornerRadius / fabricObject.scaleX,
            ry: cornerRadius / fabricObject.scaleY,
            left: -fabricObject.width / 2,
            top: -fabricObject.height / 2
        });

        /* Image Border Radius */
        selector.find('#img-border-radius').on("input", function () {
            setValueActiveObject('clipPath', roundedCorners(canvas.getActiveObject(), parseInt($(this).val())));
            setValueActiveObject("roundedCorders", parseInt($(this).val()));
            canvas.requestRenderAll();
        });

        selector.find('#img-border-radius').bind('change', function() {
            canvas.fire('palleon:history', { type: 'image', text: palleonParams.edited});
        });

        /* Image Border Color */
        selector.find('#img-border-color').bind('change', function() {
            setImgBorderColor(this, true);
        });
        function setImgBorderColor(self, his) {
            setValueActiveObject("stroke", $(self).val());
            canvas.requestRenderAll();
            if( his ) canvas.fire('palleon:history', { type: 'image', text: palleonParams.edited});
        }

        /* Image Border Width */
        selector.find('#palleon-image-settings input[type=number]').on("input paste", function () {
            var val = parseInt($(this).val());
            if ($(this).attr('id') == 'img-border-width') {
                setValueActiveObject('strokeWidth', val);
            }
            canvas.requestRenderAll();
        });

        selector.find('#palleon-image-settings input[type=number]').bind('input', function() {
            window.clearTimeout(timeOut);
            timeOut = setTimeout(function(){
                canvas.fire('palleon:history', { type: 'image', text: palleonParams.edited});
            }, 500);
        });

        /* Image Skew, Rotate, Opacity */
        selector.find('#palleon-image-settings input[type=range]').bind('input click', function() {
            var val = $(this).val();
            if ($(this).attr('id') == 'img-skew-x') {
                setValueActiveObject("skewX", parseInt(val));
            } else if ($(this).attr('id') == 'img-skew-y') {
                setValueActiveObject("skewY", parseInt(val));
            } else if ($(this).attr('id') == 'img-rotate') {
                setValueActiveObject("angle", parseInt(val));
            } else if ($(this).attr('id') == 'img-opacity') {
                setValueActiveObject("opacity", parseFloat(val));
            }
            canvas.requestRenderAll();
        });

        selector.find('#palleon-image-settings input[type=range]').bind('change', function() {
            canvas.fire('palleon:history', { type: 'image', text: palleonParams.edited});
        });

        /* Set Shape Settings */
        function setShapeSettings(shape) {
            selector.find('#shape-outline-width').val(shape.strokeWidth);
            if (shape.gradientFill == 'none') {
                selector.find('#palleon-shape-gradient').val('none');
                selector.find('#palleon-shape-color').spectrum("set", shape.fill);
            } else if (shape.gradientFill == 'vertical') {
                selector.find('#palleon-shape-gradient').val('vertical');
                if (shape.fill.colorStops.length == 4) {
                    selector.find('#shape-gradient-color-1').spectrum("set", shape.fill.colorStops[0].color);
                    selector.find('#shape-gradient-color-2').spectrum("set", shape.fill.colorStops[1].color);
                    selector.find('#shape-gradient-color-3').spectrum("set", shape.fill.colorStops[2].color);
                    selector.find('#shape-gradient-color-4').spectrum("set", shape.fill.colorStops[3].color);
                } else if (shape.fill.colorStops.length == 3) {
                    selector.find('#shape-gradient-color-1').spectrum("set", shape.fill.colorStops[0].color);
                    selector.find('#shape-gradient-color-2').spectrum("set", shape.fill.colorStops[1].color);
                    selector.find('#shape-gradient-color-3').spectrum("set", shape.fill.colorStops[2].color);
                    selector.find('#shape-gradient-color-4').spectrum("set", '');
                } else if (shape.fill.colorStops.length == 2) {
                    selector.find('#shape-gradient-color-1').spectrum("set", shape.fill.colorStops[0].color);
                    selector.find('#shape-gradient-color-2').spectrum("set", shape.fill.colorStops[1].color);
                    selector.find('#shape-gradient-color-3').spectrum("set", '');
                    selector.find('#shape-gradient-color-4').spectrum("set", '');
                }
            } else if (shape.gradientFill == 'horizontal') {
                selector.find('#palleon-shape-gradient').val('horizontal');
                if (shape.fill.colorStops.length == 4) {
                    selector.find('#shape-gradient-color-1').spectrum("set", shape.fill.colorStops[0].color);
                    selector.find('#shape-gradient-color-2').spectrum("set", shape.fill.colorStops[1].color);
                    selector.find('#shape-gradient-color-3').spectrum("set", shape.fill.colorStops[2].color);
                    selector.find('#shape-gradient-color-4').spectrum("set", shape.fill.colorStops[3].color);
                } else if (shape.fill.colorStops.length == 3) {
                    selector.find('#shape-gradient-color-1').spectrum("set", shape.fill.colorStops[0].color);
                    selector.find('#shape-gradient-color-2').spectrum("set", shape.fill.colorStops[1].color);
                    selector.find('#shape-gradient-color-3').spectrum("set", shape.fill.colorStops[2].color);
                    selector.find('#shape-gradient-color-4').spectrum("set", '');
                } else if (shape.fill.colorStops.length == 2) {
                    selector.find('#shape-gradient-color-1').spectrum("set", shape.fill.colorStops[0].color);
                    selector.find('#shape-gradient-color-2').spectrum("set", shape.fill.colorStops[1].color);
                    selector.find('#shape-gradient-color-3').spectrum("set", '');
                    selector.find('#shape-gradient-color-4').spectrum("set", '');
                }
            }
            selector.find('#palleon-shape-gradient').trigger('change');

            selector.find('#shape-outline-color').spectrum("set", shape.stroke);
            if (shape.shadow == null) {
                selector.find('#palleon-shape-shadow').prop('checked', false);
            } else {
                selector.find('#palleon-shape-shadow').prop('checked', true);
                selector.find('#shape-shadow-color').spectrum("set", shape.shadow.color);
                selector.find('#shape-shadow-blur').val(shape.shadow.blur);
                selector.find('#shape-shadow-offset-x').val(shape.shadow.offsetX);
                selector.find('#shape-shadow-offset-y').val(shape.shadow.offsetY);
            }
            selector.find('#palleon-shape-shadow').trigger('change');

            selector.find('#shape-opacity').val(shape.opacity);
            selector.find('#shape-opacity').parent().parent().find('.slider-label span').html(shape.opacity);
            selector.find('#shape-skew-x').val(shape.skewX);
            selector.find('#shape-skew-x').parent().parent().find('.slider-label span').html(shape.skewX);
            selector.find('#shape-skew-y').val(shape.skewX);
            selector.find('#shape-skew-y').parent().parent().find('.slider-label span').html(shape.skewY);
            selector.find('#shape-rotate').val(parseInt(shape.angle));
            selector.find('#shape-rotate').parent().parent().find('.slider-label span').html(parseInt(shape.angle));

            selector.find('#shape-custom-width').val('');
            selector.find('#shape-custom-height').val('');
        }

        /* Select Shape */
        selector.find('#palleon-shape-select').on('change', function() {
            var val = $(this).val();
            if (val == 'none' || val == 'custom') {
                selector.find('#palleon-shape-add').prop('disabled', true);
            } else {
                selector.find('#palleon-shape-add').prop('disabled', false);
            }
        });

        /* Add Shape */
        selector.find('#palleon-shape-add').on('click', function() {
            var val = selector.find('#palleon-shape-select').val();
            var shape = '';
            var polygon = '';
            if (val == 'circle') {
                shape = new fabric.Circle({
                    radius: 50,
                    fill: '#fff',
                    stroke: '#000',
                    strokeWidth: 0,
                    objectType: 'circle',
                    width:100,
                    height:100,
                    gradientFill: 'none',
                    top: getScaledSize()[1] / 2,
                    left: getScaledSize()[0] / 2,
                    originX: 'center',
                    originY: 'center'
                });
                shape.controls = {
                    ...fabric.Rect.prototype.controls,
                    ml: new fabric.Control({ visible: false }),
                    mb: new fabric.Control({ visible: false }),
                    mr: new fabric.Control({ visible: false }),
                    mt: new fabric.Control({ visible: false }),
                    mtr: new fabric.Control({ visible: false }),
                };
            } else if (val == 'ellipse') {
                shape = new fabric.Ellipse({
                    rx: 75,
                    ry: 50,
                    fill: '#fff',
                    stroke: '#000',
                    strokeWidth: 0,
                    objectType: 'ellipse',
                    width:100,
                    height:100,
                    gradientFill: 'none',
                    top: getScaledSize()[1] / 2,
                    left: getScaledSize()[0] / 2,
                    originX: 'center',
                    originY: 'center'
                });
            } else if (val == 'square') {
                shape = new fabric.Rect({
                    radius: 50,
                    fill: '#fff',
                    stroke: '#000',
                    strokeWidth: 0,
                    objectType: 'square',
                    width:100,
                    height:100,
                    gradientFill: 'none',
                    top: getScaledSize()[1] / 2,
                    left: getScaledSize()[0] / 2,
                    originX: 'center',
                    originY: 'center'
                });
                shape.controls = {
                    ...fabric.Rect.prototype.controls,
                    ml: new fabric.Control({ visible: false }),
                    mb: new fabric.Control({ visible: false }),
                    mr: new fabric.Control({ visible: false }),
                    mt: new fabric.Control({ visible: false }),
                    mtr: new fabric.Control({ visible: false }),
                };
            } else if (val == 'rectangle') {
                shape = new fabric.Rect({
                    radius: 50,
                    fill: '#fff',
                    stroke: '#000',
                    strokeWidth: 0,
                    objectType: 'rectangle',
                    width:200,
                    height:150,
                    gradientFill: 'none',
                    top: getScaledSize()[1] / 2,
                    left: getScaledSize()[0] / 2,
                    originX: 'center',
                    originY: 'center'
                });
            } else if (val == 'triangle') {
                shape = new fabric.Triangle({
                    radius: 50,
                    fill: '#fff',
                    stroke: '#000',
                    strokeWidth: 0,
                    objectType: 'triangle',
                    width:100,
                    height:100,
                    gradientFill: 'none',
                    top: getScaledSize()[1] / 2,
                    left: getScaledSize()[0] / 2,
                    originX: 'center',
                    originY: 'center'
                });
            } else if (val == 'trapezoid') {
                polygon = [ {x:-100,y:-50},{x:100,y:-50},{x:150,y:50},{x:-150,y:50} ];
                shape = new fabric.Polygon(polygon,{
                    fill: '#fff',
                    stroke: '#000',
                    strokeWidth: 0,
                    objectType: 'trapezoid',
                    width:100,
                    height:100,
                    gradientFill: 'none',
                    top: getScaledSize()[1] / 2,
                    left: getScaledSize()[0] / 2,
                    originX: 'center',
                    originY: 'center'
                });
            } else if (val == 'emerald') {
                polygon = [{x:850,y:75},
                    {x:958,y:137.5},
                    {x:958,y:262.5},
                    {x:850,y:325},
                    {x:742,y:262.5},
                    {x:742,y:137.5}];
                shape = new fabric.Polygon(polygon,{
                    fill: '#fff',
                    stroke: '#000',
                    strokeWidth: 0,
                    objectType: 'emerald',
                    width:100,
                    height:100,
                    gradientFill: 'none',
                    top: getScaledSize()[1] / 2,
                    left: getScaledSize()[0] / 2,
                    originX: 'center',
                    originY: 'center'
                });
            } else if (val == 'star') {
                polygon = [{x:350,y:75},
                    {x:380,y:160},
                    {x:470,y:160},
                    {x:400,y:215},
                    {x:423,y:301},
                    {x:350,y:250},
                    {x:277,y:301},
                    {x:303,y:215},
                    {x:231,y:161},
                    {x:321,y:161}];
                shape = new fabric.Polygon(polygon,{
                    fill: '#fff',
                    stroke: '#000',
                    strokeWidth: 0,
                    objectType: 'star',
                    width:100,
                    height:100,
                    gradientFill: 'none',
                    top: getScaledSize()[1] / 2,
                    left: getScaledSize()[0] / 2,
                    originX: 'center',
                    originY: 'center'
                });
            }
            shape.perPixelTargetFind = true;
            canvas.add(shape);
            shape.scaleToWidth(getScaledSize()[0] / 6);
            if (shape.isPartiallyOnScreen()) {
                shape.scaleToHeight(getScaledSize()[1] / 6);
            }
            canvas.setActiveObject(shape);
            canvas.requestRenderAll();
            canvas.fire('palleon:history', { type: val, text: palleonParams.added });
        });

        /* Shape Color Fields */
        selector.find('#palleon-shape-settings .palleon-colorpicker').bind('change', function() {
            setShapeColor(this, true);
        });
        function setShapeColor(self, his) {
            var val = $(self).val();
            if ($(self).attr('id') == 'palleon-shape-color') {
                setValueActiveObject('fill', val);
            } else if ($(self).attr('id') == 'shape-outline-color') {
                setValueActiveObject('stroke', val);
            }
            canvas.requestRenderAll();
            if( his ) canvas.fire('palleon:history', { type: canvas.getActiveObject().objectType, text: palleonParams.edited });
        }

        /* Shape Skew, Rotate, Opacity */
        selector.find('#palleon-shape-settings input[type=range]').bind('input click', function() {
            var val = $(this).val();
            if ($(this).attr('id') == 'shape-skew-x') {
                setValueActiveObject("skewX", parseInt(val));
            } else if ($(this).attr('id') == 'shape-skew-y') {
                setValueActiveObject("skewY", parseInt(val));
            } else if ($(this).attr('id') == 'shape-rotate') {
                setValueActiveObject("angle", parseInt(val));
            } else if ($(this).attr('id') == 'shape-opacity') {
                setValueActiveObject("opacity", parseFloat(val));
            }
            canvas.requestRenderAll();
        });

        selector.find('#palleon-shape-settings input[type=range]').bind('change', function() {
            canvas.fire('palleon:history', { type: canvas.getActiveObject().objectType, text: palleonParams.edited });
        });

        /* Shape Numeric Fields */
        selector.find('#palleon-shape-settings input[type=number]').bind('input paste', function() {
            var val = parseInt($(this).val());
            if ($(this).attr('id') == 'shape-outline-width') {
                setValueActiveObject('strokeWidth', val);
            } else if ($(this).attr('id') == 'shape-custom-width') {
                setValueActiveObject("width", val);
                setValueActiveObject("scaleX", 1);
            } else if ($(this).attr('id') == 'shape-custom-height') {
                setValueActiveObject("height", val);
                setValueActiveObject("scaleY", 1);
            }
            canvas.requestRenderAll();
        });

        selector.find('#palleon-shape-settings input[type=number]').bind('input', function() {
            window.clearTimeout(timeOut);
            timeOut = setTimeout(function(){
                canvas.fire('palleon:history', { type: canvas.getActiveObject().objectType, text: palleonParams.edited});
            }, 500);
        });

         /* FRAMES */

        /* Filter frames */
        var filterframes = function(searchTerm) {
            selector.find('#palleon-frames-wrap li').hide().filter('[data-keyword*="'+ searchTerm +'"]').show();
        };

        /* Search frame Input */
        selector.find('#palleon-frame-search').on('keyup input', function () {
            selector.find("#palleon-noframes").hide();
            var searchTerm = $(this).val().toLowerCase().replace(/\s/g,' ');
            if ((searchTerm == '') || (searchTerm.length < 1)) {
                selector.find('#palleon-frames-wrap li').show();
                selector.find('#palleon-frame-search-icon').html('search');
                selector.find('#palleon-frame-search-icon').removeClass('cancel');
            } else {
                selector.find('#palleon-frame-search-icon').html('clear');
                selector.find('#palleon-frame-search-icon').addClass('cancel');
                filterframes(searchTerm);
                if (selector.find('#palleon-frames-wrap li:visible').length === 0) {
                    selector.find("#palleon-noframes").show();
                }
            }
        });

        /* Search frame Icon */
        selector.find('#palleon-frame-search-icon').on('click', function () {
            if ($(this).hasClass('cancel')) {
                $(this).removeClass('cancel');
                $(this).html('search');
                selector.find('#palleon-frame-search').val('');
                selector.find('#palleon-frames-wrap li').show();
                selector.find("#palleon-noframes").hide();
            }
        });

        /* Add frame */
        selector.find('.palleon-frames-grid').on('click','.palleon-frame img',function(){
            canvasLoad('loading');
            var frame = $(this).parent().parent();
            var svgUrl = frame.data('elsource');
            selector.find('.palleon-frames-grid .palleon-frame').removeClass('active');
            frame.addClass('active');
            fabric.loadSVGFromURL(svgUrl,function(objects, options){
                var svg = fabric.util.groupSVGElements(objects, options);
                var svgWidth = svg.width;
                var svgHeight = svg.height;
                svg.set('originX', 'center');
                svg.set('originY', 'center');
                svg.set('left', getScaledSize()[0] / 2);
                svg.set('top', getScaledSize()[1] / 2);
                svg.set('scaleX', (getScaledSize()[0]+2) / svgWidth);
                svg.set('scaleY', (getScaledSize()[1]+2) / svgHeight);
                svg.set('objectType', 'frame');
                svg.perPixelTargetFind = true;
                canvas.add(svg);
                canvas.setActiveObject(svg);
                canvas.requestRenderAll();
                canvasLoad('complete');
            }, function() {}, {
                crossOrigin: 'anonymous'
            });
            canvas.fire('palleon:history', { type: 'frame', text: palleonParams.added });
        });

        /* Frame color */
        selector.find('#palleon-frame-color').bind('change', function() {
            setFrameColor(this, true);
        });

        function setFrameColor(self, his) {
            var val = $(self).val();
            var objects = canvas.getObjects().filter(element => element.objectType == 'frame');
            $.each(objects, function(index, value) {
                if (value.fill != '') {
                    value.set('fill', val);
                }
                if (value._objects) {
                    for (var i = 0; i < value._objects.length; i++) {
                        if (value._objects[i].fill != '') {
                            value._objects[i].set({
                                fill: val
                            });
                        }
                    }
                }
            });
            canvas.requestRenderAll();
            if( his ) canvas.fire('palleon:history', { type: 'frame', text: palleonParams.edited});
        }

        /* Rotate Frame */
        function rotateFrame(direction) {
            var objects = canvas.getObjects().filter(element => element.objectType == 'frame');
            $.each(objects, function(index, svg) {
                var svgRotate = svg.angle;
                var svgWidth = svg.width;
                var svgHeight = svg.height;
                var width = getScaledSize()[0];
                var height = getScaledSize()[1];
                if (svgRotate == 0 || svgRotate == 180 || svgRotate == -180) {
                    width = getScaledSize()[1];
                    height = getScaledSize()[0];
                }
                if (direction == 'right') {
                    if (svgRotate == 0) {
                        svgRotate = 90;
                    } else if (svgRotate == 90) {
                        svgRotate = 180;
                    } else if (svgRotate == 180) {
                        svgRotate = 270;
                    } else if (svgRotate == 270) {
                        svgRotate = 0;
                    } else if (svgRotate == -90) {
                        svgRotate = 0;
                    } else if (svgRotate == -180) {
                        svgRotate = -90;
                    } else if (svgRotate == -270) {
                        svgRotate = -180;
                    }
                } else if (direction == 'left') {
                    if (svgRotate == 0) {
                        svgRotate = -90;
                    } else if (svgRotate == -90) {
                        svgRotate = -180;
                    } else if (svgRotate == -180) {
                        svgRotate = -270;
                    } else if (svgRotate == -270) {
                        svgRotate = 0;
                    } else if (svgRotate == 90) {
                        svgRotate = 0;
                    } else if (svgRotate == 180) {
                        svgRotate = 90;
                    } else if (svgRotate == 270) {
                        svgRotate = 180;
                    }
                }
                svg.set('left', getScaledSize()[0] / 2);
                svg.set('top', getScaledSize()[1] / 2);
                svg.set('scaleX', width / svgWidth);
                svg.set('scaleY', height / svgHeight);
                svg.set('angle', svgRotate);
            });
            canvas.requestRenderAll();
            canvas.fire('palleon:history', { type: 'frame', text: palleonParams.edited});
        }

        /* Frame Rotate Right */
        selector.find('#palleon-rotate-right-frame').on('click', function() {
            rotateFrame('right');
        });

        /* Frame Rotate Left */
        selector.find('#palleon-rotate-left-frame').on('click', function() {
            rotateFrame('left');
        });

        /* Frame Flip X */
        selector.find('#palleon-flip-horizontal-frame').on('click', function() {
            var objects = canvas.getObjects().filter(element => element.objectType == 'frame');
            $.each(objects, function(index, value) {
                value.toggle('flipX');
            });
            canvas.requestRenderAll();
            canvas.fire('palleon:history', { type: 'frame', text: palleonParams.edited});
        });

        /* Frame Flip Y */
        selector.find('#palleon-flip-vertical-frame').on('click', function() {
            var objects = canvas.getObjects().filter(element => element.objectType == 'frame');
            $.each(objects, function(index, value) {
                value.toggle('flipY');
            });
            canvas.requestRenderAll();
            canvas.fire('palleon:history', { type: 'frame', text: palleonParams.edited});
        });

        /* ELEMENTS */

        /* Filter elements */
        var filterElements = function(searchTerm) {
            selector.find('#palleon-elements-wrap li').hide().filter('[data-keyword*="'+ searchTerm +'"]').show();
        };

        /* Search Element Input */
        selector.find('#palleon-element-search').on('keyup input', function () {
            selector.find("#palleon-noelements").hide();
            var searchTerm = $(this).val().toLowerCase().replace(/\s/g,' ');
            if ((searchTerm == '') || (searchTerm.length < 1)) {
                selector.find('#palleon-elements-wrap li').show();
                selector.find('#palleon-element-search-icon').html('search');
                selector.find('#palleon-element-search-icon').removeClass('cancel');
            } else {
                selector.find('#palleon-element-search-icon').html('clear');
                selector.find('#palleon-element-search-icon').addClass('cancel');
                filterElements(searchTerm);
                if (selector.find('#palleon-elements-wrap li:visible').length === 0) {
                    selector.find("#palleon-noelements").show();
                }
            }
        });

        /* Search Element Icon */
        selector.find('#palleon-element-search-icon').on('click', function () {
            if ($(this).hasClass('cancel')) {
                $(this).removeClass('cancel');
                $(this).html('search');
                selector.find('#palleon-element-search').val('');
                selector.find('#palleon-elements-wrap li').show();
                selector.find("#palleon-noelements").hide();
            }
        });

        /* Add Element */
        selector.find('#palleon-icons-grid').on('click','.palleon-element',function(){
            let element = $(this);
            insertSVGEl(element);
        });
        selector.find('#palleon-all-elements').on('click','.palleon-element > *:first-child',function(){
            let element = $(this).parent();
            insertSVGEl(element);
        });
        selector.find('#palleon-all-elements').on('click','.palleon-element',function(e){
            let parentEl = $(e.target).hasClass('palleon-element');
            if( !parentEl ) return;
            insertSVGEl( $(this) );
        });
        function insertSVGEl(element) {
            var svgUrl = element.data('elsource');
            if (element.parent().attr('id') == 'palleon-icons-grid') {
                var iconStyle = selector.find('#palleon-icon-style').val();
                svgUrl = element.data('elsource') + '/' + iconStyle + '/24px.svg';
                console.log(svgUrl);
            }
            var loader = element.data('loader');
            if (loader == 'yes') {
                canvasLoad('loading');
            }
            selector.find('.palleon-elements-grid .palleon-element').removeClass('active');
            element.addClass('active');

            fabric.loadSVGFromURL(svgUrl,function(objects, options){
                var svg = fabric.util.groupSVGElements(objects, options);
                svg.set('originX', 'center');
                svg.set('originY', 'center');
                svg.set('left', getScaledSize()[0] / 2);
                svg.set('top', getScaledSize()[1] / 2);
                svg.set('objectType', 'element');
                svg.set('gradientFill', 'none');
                svg.perPixelTargetFind = true;
                canvas.add(svg);
                svg.scaleToWidth(getScaledSize()[0] / 8);
                if (svg.isPartiallyOnScreen()) {
                    svg.scaleToHeight(getScaledSize()[1] / 8);
                }
                canvas.setActiveObject(svg);
                canvas.requestRenderAll();
                if (loader == 'yes') {
                    canvasLoad('complete');
                }
            }, function() {}, {
                crossOrigin: 'anonymous'
            });
            canvas.fire('palleon:history', { type: 'element', text: palleonParams.added });
        }

        /* Set Custom SVG Settings */
        function setElementSettings(obj) {
            if (obj.gradientFill == 'none') {
                selector.find('#palleon-element-gradient').val('none');
                selector.find('#palleon-element-color').spectrum("set", obj.fill);

            } else if (obj.gradientFill == 'vertical') {
                selector.find('#palleon-element-gradient').val('vertical');
                if (obj.fill.colorStops.length == 4) {
                    selector.find('#element-gradient-color-1').spectrum("set", obj.fill.colorStops[0].color);
                    selector.find('#element-gradient-color-2').spectrum("set", obj.fill.colorStops[1].color);
                    selector.find('#element-gradient-color-3').spectrum("set", obj.fill.colorStops[2].color);
                    selector.find('#element-gradient-color-4').spectrum("set", obj.fill.colorStops[3].color);
                } else if (obj.fill.colorStops.length == 3) {
                    selector.find('#element-gradient-color-1').spectrum("set", obj.fill.colorStops[0].color);
                    selector.find('#element-gradient-color-2').spectrum("set", obj.fill.colorStops[1].color);
                    selector.find('#element-gradient-color-3').spectrum("set", obj.fill.colorStops[2].color);
                    selector.find('#element-gradient-color-4').spectrum("set", '');
                } else if (obj.fill.colorStops.length == 2) {
                    selector.find('#element-gradient-color-1').spectrum("set", obj.fill.colorStops[0].color);
                    selector.find('#element-gradient-color-2').spectrum("set", obj.fill.colorStops[1].color);
                    selector.find('#element-gradient-color-3').spectrum("set", '');
                    selector.find('#element-gradient-color-4').spectrum("set", '');
                }
            } else if (obj.gradientFill == 'horizontal') {
                selector.find('#palleon-element-gradient').val('horizontal');
                if (obj.fill.colorStops.length == 4) {
                    selector.find('#element-gradient-color-1').spectrum("set", obj.fill.colorStops[0].color);
                    selector.find('#element-gradient-color-2').spectrum("set", obj.fill.colorStops[1].color);
                    selector.find('#element-gradient-color-3').spectrum("set", obj.fill.colorStops[2].color);
                    selector.find('#element-gradient-color-4').spectrum("set", obj.fill.colorStops[3].color);
                } else if (obj.fill.colorStops.length == 3) {
                    selector.find('#element-gradient-color-1').spectrum("set", obj.fill.colorStops[0].color);
                    selector.find('#element-gradient-color-2').spectrum("set", obj.fill.colorStops[1].color);
                    selector.find('#element-gradient-color-3').spectrum("set", obj.fill.colorStops[2].color);
                    selector.find('#element-gradient-color-4').spectrum("set", '');
                } else if (obj.fill.colorStops.length == 2) {
                    selector.find('#element-gradient-color-1').spectrum("set", obj.fill.colorStops[0].color);
                    selector.find('#element-gradient-color-2').spectrum("set", obj.fill.colorStops[1].color);
                    selector.find('#element-gradient-color-3').spectrum("set", '');
                    selector.find('#element-gradient-color-4').spectrum("set", '');
                }
            }
            selector.find('#palleon-element-gradient').trigger('change');
            selector.find('#element-opacity').val(obj.opacity);
            selector.find('#element-opacity').parent().parent().find('.slider-label span').html(obj.opacity);
            selector.find('#element-skew-x').val(obj.skewX);
            selector.find('#element-skew-x').parent().parent().find('.slider-label span').html(obj.skewX);
            selector.find('#element-skew-y').val(obj.skewX);
            selector.find('#element-skew-y').parent().parent().find('.slider-label span').html(obj.skewY);
            selector.find('#element-rotate').val(parseInt(obj.angle));
            selector.find('#element-rotate').parent().parent().find('.slider-label span').html(parseInt(obj.angle));
            if (obj.shadow == null) {
                selector.find('#palleon-element-shadow').prop('checked', false);
            } else {
                selector.find('#palleon-element-shadow').prop('checked', true);
                selector.find('#element-shadow-color').spectrum("set", obj.shadow.color);
                selector.find('#element-shadow-blur').val(obj.shadow.blur);
                selector.find('#element-shadow-offset-x').val(obj.shadow.offsetX);
                selector.find('#element-shadow-offset-y').val(obj.shadow.offsetY);
            }
            selector.find('#palleon-element-shadow').trigger('change');
        }

        /* Upload Custom Element */
        selector.find('#palleon-element-upload').on('change', function (e) {
            var reader = new FileReader();
            var svgImg = '';
            reader.onload = function(ev) {
                svgImg = reader.result;
                fabric.loadSVGFromURL(svgImg,function(objects, options){
                    var svg = fabric.util.groupSVGElements(objects, options);
                    svg.set('originX', 'center');
                    svg.set('originY', 'center');
                    svg.set('left', getScaledSize()[0] / 2);
                    svg.set('top', getScaledSize()[1] / 2);
                    svg.set('objectType', 'customSVG');
                    svg.scaleToWidth(getScaledSize()[0] / 2);
                    svg.scaleToHeight(getScaledSize()[1] / 2);
                    svg.perPixelTargetFind = true;
                    canvas.add(svg);
                    canvas.setActiveObject(svg);
                    canvas.requestRenderAll();
                }, function() {}, {
                    crossOrigin: 'anonymous'
                });
            };
            reader.readAsDataURL(this.files[0]);
            canvas.fire('palleon:history', { type: 'element', text: palleonParams.added });
        });

        /* Custom element color */
        selector.find('#palleon-element-color').bind('change', function() {
            setElementColor(this, true);
        });
        function setElementColor(self, his) {
            var val = $(self).val();
            var obj = canvas.getActiveObject();
            if (obj.fill != '') {
                obj.set('fill', val);
            }
            if (obj._objects) {
                for (var i = 0; i < obj._objects.length; i++) {
                    if (obj._objects[i].fill != '') {
                        obj._objects[i].set({
                            fill: val
                        });
                    }
                }
            }
            canvas.requestRenderAll();
            if(his) canvas.fire('palleon:history', { type: 'element', text: palleonParams.edited });
        }

        /* Custom Element Flip X */
        selector.find('#element-flip-horizontal').on('click', function() {
            canvas.getActiveObject().toggle('flipX');
            canvas.requestRenderAll();
            canvas.fire('palleon:history', { type: 'element', text: palleonParams.edited });
        });

        /* Custom Element Flip Y */
        selector.find('#element-flip-vertical').on('click', function() {
            canvas.getActiveObject().toggle('flipY');
            canvas.requestRenderAll();
            canvas.fire('palleon:history', { type: 'element', text: palleonParams.edited });
        });

        /* Custom Element Skew, Rotate, Opacity */
        selector.find('#palleon-custom-element-options input[type=range]').bind('input click', function() {
            var val = $(this).val();
            if ($(this).attr('id') == 'element-skew-x') {
                setValueActiveObject("skewX", parseInt(val));
            } else if ($(this).attr('id') == 'element-skew-y') {
                setValueActiveObject("skewY", parseInt(val));
            } else if ($(this).attr('id') == 'element-rotate') {
                setValueActiveObject("angle", parseInt(val));
            } else if ($(this).attr('id') == 'element-opacity') {
                setValueActiveObject("opacity", parseFloat(val));
            }
            canvas.requestRenderAll();
        });

        selector.find('#palleon-custom-element-options input[type=range]').bind('change', function() {
            canvas.fire('palleon:history', { type: 'element', text: palleonParams.edited});
        });

        /* ICON LIBRARY */

        /* Filter icons */
        var filterIcons = function(searchTerm) {
            selector.find('#palleon-icons-grid .palleon-element').css('display', 'none').filter('[title*="'+ searchTerm +'"]').css('display', 'flex');
        };

        /* Search Icon Input */
        selector.find('#palleon-icon-search').on('keyup input', function () {
            selector.find("#palleon-noicons").hide();
            var searchTerm = $(this).val().toLowerCase().replace(/\s/g,' ');
            if ((searchTerm == '') || (searchTerm.length < 1)) {
                selector.find('#palleon-icons-grid .palleon-element').css('display', 'flex');
                selector.find('#palleon-icon-search-icon').html('search');
                selector.find('#palleon-icon-search-icon').removeClass('cancel');
            } else {
                selector.find('#palleon-icon-search-icon').html('clear');
                selector.find('#palleon-icon-search-icon').addClass('cancel');
                filterIcons(searchTerm);
                if (selector.find('#palleon-icons-grid .palleon-element:visible').length === 0) {
                    selector.find("#palleon-noicons").show();
                }
            }
        });

        /* Search Icon */
        selector.find('#palleon-icon-search-icon').on('click', function () {
            if ($(this).hasClass('cancel')) {
                $(this).removeClass('cancel');
                $(this).html('search');
                selector.find('#palleon-icon-search').val('');
                selector.find('#palleon-icons-grid .palleon-element').css('display', 'flex');
                selector.find("#palleon-noicons").hide();
            }
        });

        /* BRUSHES */

        /* Draw Cursor */
        function drawCursor(brushSize, brushColor){
            $('#tm-cursor-1').remove();
            canvasWrap.tmpointer({
                id: 1,
                native_cursor: 'enable',
                cursorSize: brushSize,
                cursorColor: brushColor
            });
        }

        // Draw Mode Button
        selector.find('#palleon-draw-btn').on('click', function () {
            if ($(this).hasClass('active')) {
                selector.find("#palleon-draw-undo").prop('disabled', true);
                selector.find(".disabe-event").removeClass('disabe-event');
                $('#tm-cursor-1').remove();
                selector.find('#palleon-draw-settings').hide();
                selector.find('#palleon-icon-menu').css('pointer-events', 'auto');
                $(this).removeClass('active');
                canvas.isDrawingMode = false;
                $(this).html('<span class="material-icons">edit</span>Bắt đầu vẽ');
            } else {
                selector.find("#palleon-draw-undo").prop('disabled', false);
                selector.find("#palleon-canvas-page > div:not([data-id='"+canvasId+"'])").addClass('disabe-event');
                selector.find('#palleon-draw-settings').show();
                selector.find('#palleon-icon-menu').css('pointer-events', 'none');
                $(this).addClass('active');
                selector.find('#palleon-brush-select').trigger('change');
                canvas.isDrawingMode = true;
                $(this).html('<span class="material-icons">close</span>Dừng vẽ');
            }
        });
        $('#palleon-btn-draw').on('click', function () {
            selector.find('#palleon-draw-btn:not(.active)').click();
        });

        // Brush Type Select
        selector.find('#palleon-brush-select').on('change', function () {
            var val = $(this).val();
            if (val == 'pencil') {
                var pencilBrush = new fabric.PencilBrush(canvas);
                canvas.freeDrawingBrush = pencilBrush;
            } else if (val == 'circle') {
                var circleBrush = new fabric.CircleBrush(canvas);
                canvas.freeDrawingBrush = circleBrush;
            } else if (val == 'spray') {
                var sprayBrush = new fabric.SprayBrush(canvas);
                canvas.freeDrawingBrush = sprayBrush;
            } else if (val == 'hline') {
                var hlineBrush = new fabric.PatternBrush(canvas);
                canvas.freeDrawingBrush = hlineBrush;
                hlineBrush.getPatternSrc = function() {
                    var patternWidth = parseInt(selector.find('#brush-pattern-width').val());
                    var patternWidth2 = patternWidth / 2;
                    var patternCanvas = fabric.document.createElement('canvas');
                    patternCanvas.width = patternCanvas.height = patternWidth;
                    var ctx = patternCanvas.getContext('2d');
                    ctx.strokeStyle = selector.find('#brush-color').val();
                    ctx.lineWidth = patternWidth2;
                    ctx.beginPath();
                    ctx.moveTo(patternWidth2, 0);
                    ctx.lineTo(patternWidth2, patternWidth);
                    ctx.closePath();
                    ctx.stroke();
                    return patternCanvas;
                };
            } else if (val == 'vline') {
                var vlineBrush = new fabric.PatternBrush(canvas);
                canvas.freeDrawingBrush = vlineBrush;
                vlineBrush.getPatternSrc = function() {
                    var patternWidth = parseInt(selector.find('#brush-pattern-width').val());
                    var patternWidth2 = patternWidth / 2;
                    var patternCanvas = fabric.document.createElement('canvas');
                    patternCanvas.width = patternCanvas.height = patternWidth;
                    var ctx = patternCanvas.getContext('2d');
                    ctx.strokeStyle = selector.find('#brush-color').val();
                    ctx.lineWidth = patternWidth2;
                    ctx.beginPath();
                    ctx.moveTo(0, patternWidth2);
                    ctx.lineTo(patternWidth, patternWidth2);
                    ctx.closePath();
                    ctx.stroke();
                    return patternCanvas;
                };
            } else if (val == 'square') {
                var squareBrush = new fabric.PatternBrush(canvas);
                canvas.freeDrawingBrush = squareBrush;
                squareBrush.getPatternSrc = function() {
                    var squareWidth = parseInt(selector.find('#brush-pattern-width').val()), squareDistance = parseInt(selector.find('#brush-pattern-distance').val());
                    var patternCanvas = fabric.document.createElement('canvas');
                    patternCanvas.width = patternCanvas.height = squareWidth + squareDistance;
                    var ctx = patternCanvas.getContext('2d');
                    ctx.fillStyle = selector.find('#brush-color').val();
                    ctx.fillRect(0, 0, squareWidth, squareWidth);
                    return patternCanvas;
                };
            } else if (val == 'erase') {
                var eraseBrush = new fabric.EraserBrush(canvas);
                canvas.freeDrawingBrush = eraseBrush;
            }
            brush = canvas.freeDrawingBrush;
            if (brush.getPatternSrc) {
                brush.source = brush.getPatternSrc.call(brush);
            }
            brush.width = parseInt(selector.find('#brush-width').val());
            if (val == 'erase') {
                selector.find('#not-erase-brush').hide();
                brush.shadow = null;
                brush.color = '#E91E63';
            } else {
                canvas.freeDrawingBrush.inverted = false;
                selector.find("#palleon-draw-undo").removeClass('active');
                selector.find('#not-erase-brush').show();
                brush.color = selector.find('#brush-color').val();
            }
            if (selector.find('#palleon-brush-shadow').is(":checked")) {
                brush.shadow = brushShadow;
            } else {
                brush.shadow = null;
            }
            drawCursor(brush.width * canvas.getZoom(), brush.color);

            if (val == 'hline' || val == 'vline' || val == 'square') {
                selector.find('#palleon-brush-pattern-width').css('display', 'flex');
            } else {
                selector.find('#palleon-brush-pattern-width').css('display', 'none');
            }

            if (val == 'square') {
                selector.find('#palleon-brush-pattern-distance').css('display', 'flex');
            } else {
                selector.find('#palleon-brush-pattern-distance').css('display', 'none');
            }
        });

        /* Draw Shadow */
        selector.find('#palleon-brush-shadow').on("change", function () {
            brushShadow = new fabric.Shadow({
                color: selector.find('#brush-shadow-color').val(),
                blur: selector.find('#brush-shadow-width').val(),
                offsetX: selector.find('#brush-shadow-shadow-offset-x').val(),
                offsetY: selector.find('#brush-shadow-shadow-offset-y').val(),
            });
            if ($(this).is(":checked")) {
                brush.shadow = brushShadow;
            } else {
                brush.shadow = null;
            }
        });

        /* Draw Numeric Fields */
        selector.find('#palleon-draw-settings input[type=number]').bind('input paste keyup keydown', function() {
            if ($(this).attr('id') == 'brush-width') {
                brush.width = parseInt($(this).val());
                drawCursor(brush.width * canvas.getZoom(), brush.color);
            } else if ($(this).attr('id') == 'brush-shadow-shadow-offset-x') {
                brushShadow.offsetX = parseInt($(this).val());
            } else if ($(this).attr('id') == 'brush-shadow-shadow-offset-y') {
                brushShadow.offsetY = parseInt($(this).val());
            } else if ($(this).attr('id') == 'brush-shadow-width') {
                brushShadow.blur = parseInt($(this).val());
            } else if ($(this).attr('id') == 'brush-pattern-width') {
                selector.find('#palleon-brush-select').trigger('change');
            } else if ($(this).attr('id') == 'brush-pattern-distance') {
                selector.find('#palleon-brush-select').trigger('change');
            }
        });

        /* Draw Color Fields */
        selector.find('#palleon-draw-settings .palleon-colorpicker').bind('change', function() {
            if ($(this).attr('id') == 'brush-color') {
                brush.color = $(this).val();
                drawCursor(brush.width * canvas.getZoom(), brush.color);
                selector.find('#palleon-brush-select').trigger('change');
            } else if ($(this).attr('id') == 'brush-shadow-color') {
                brushShadow.color = $(this).val();
            }
        });

        /* Undo Draw */
        selector.find("#palleon-draw-undo").on("click", function () {
            if (selector.find('#palleon-brush-select').val() == 'erase') {
                if (canvas.backgroundImage) {
                    if ($(this).hasClass('active')) {
                        $(this).removeClass('active');
                        canvas.freeDrawingBrush.inverted = false;
                    } else {
                        $(this).addClass('active');
                        canvas.freeDrawingBrush.inverted = true;
                    }
                }
            } else {
                let objects = canvas.getObjects();
                let drawings = objects.filter(element => element.objectType == 'drawing');
                let lastElement = drawings.slice(-1)[0];
                selector.find("#palleon-layers-"+canvasId+" #" + lastElement.id).find("a.delete-layer").click();
            }
        });

        /* KEYBOARD EVENTS */

        document.onkeydown = function(e) {
            switch (e.keyCode) {
              case 38:  /* Up arrow */
                  if(canvas.getActiveObject()){
                    canvas.getActiveObject().top -= 1;
                    canvas.requestRenderAll();
                  }
                break;
              case 40:  /* Down arrow  */
                  if(canvas.getActiveObject()){
                    canvas.getActiveObject().top += 1;
                    canvas.requestRenderAll();
                  }
                break;
              case 37:  /* Left arrow  */
                  if(canvas.getActiveObject()){
                    canvas.getActiveObject().left -= 1;
                    canvas.requestRenderAll();
                  }
                break;
              case 39:  /* Right arrow  */
                  if(canvas.getActiveObject()){
                    canvas.getActiveObject().left += 1;
                    canvas.requestRenderAll();
                  }
                break;
            }
        };

        /* SETTINGS */

        // CSS Theme Select
        selector.find('#custom-theme').on('change', function() {
            var val = $(this).val();
            var link = settings.baseURL + 'css/' + val + '.css';
            $("#palleon-theme-link").attr('href', link);
        });

        // Font Size
        selector.find("#custom-font-size").on("input", function () {
            $('html').css('font-size', $(this).val() + 'px');
        });

        // Canvas Background
        selector.find('#custom-background').on('change', function() {
            setContainerBG(this);
        });

        function setContainerBG(self) {
            let val = $(self).val();
            selector.find("#palleon-content").css('background-color', val);
        }

        // Image Background
        selector.find('#custom-image-background').on('change', function() {
            setCanvasBG(this);
        });
        function setCanvasBG(self) {
            var val = $(self).val();
            selector.find('#palleon-canvas-color').spectrum("set", val);
            if (val == '') {
                canvas.backgroundColor = 'transparent';
                canvas.requestRenderAll();
            } else {
                canvas.backgroundColor = val;
                canvas.requestRenderAll();
            }
        }

        // Change ruler option
        selector.find('#custom-ruler').on('change', function() {
            initRuler();
        });

        let ruleInited = {};
        function initRuler() {
            let canvasEl = $('#palleon-canvas-page > div');
            canvasEl.each(function(){
                let cvId = $(this).attr('data-id');
                let value = selector.find('#custom-ruler').val();
                if( !ruleInited[cvId] ) {
                    ruleInited[cvId] = new ruler({
                        container: $(this).find('.canvas-container')[0],// reference to DOM element to apply rulers on
                        rulerHeight: 15, // thickness of ruler
                        fontFamily: 'arial',// font for points
                        fontSize: '12px',
                        strokeStyle: 'black',
                        lineWidth: 1,
                        enableMouseTracking: true,
                        enableToolTip: true
                    })
                    ruleInited[cvId].api.setPos({x:0, y:0});
                    // ruleInited[cvId].api.clearGuides(true);
                    //ruleInited[cvId].api.toggleRulerVisibility(true);
                }
                if( value == 1 ) {
                    $(this).addClass('palleon-canvas-ruler-active');
                    ruleInited[cvId].api.toggleRulerVisibility(true);
                }else{
                    $(this).removeClass('palleon-canvas-ruler-active');
                    ruleInited[cvId].api.toggleRulerVisibility(false);
                }
            });
        }

        /* Resize Events */
        $(window).on('resize', function(){
            adjustZoom();
        });

        //////////////////////* CUSTOM FUNCTIONS *//////////////////////

        settings.customFunctions.call(this, selector, canvas, lazyLoadInstance);
        function setValueActiveObject(key, value) {
            let obj = canvas.getActiveObject();
            if( !obj ) {
                return setTimeout(function(){
                    setValueActiveObject(key, value);
                }, 100);
            }
            obj.set(key, value);
        }

        $(document).on('keydown', ({ keyCode, ctrlKey } = event) => {
            if( $(event.target).is('input') || $(event.target).is('textarea') ) return;
            let layerActive = $('#palleon-layers-wrap li.active');
            let useShortkey = false;
            // Delete active object
            if ( keyCode === 46 && layerActive.length ){
                useShortkey = true;
                layerActive.find('.delete-layer').click();
            }

            //console.log(keyCode);

            // ESC pressed
            if( keyCode == 27 ) {
                useShortkey = true;
                unSelectAllObject();
            }

            // Check Ctrl key is pressed.
            if (!ctrlKey)  return;

            // Check pressed button is Z - Ctrl+Z.
            if (keyCode === 90){
                useShortkey = true;
                $('#palleon-undo').click();
            }

            // Check pressed button is Y - Ctrl+Y.
            if (keyCode === 89){
                useShortkey = true;
                $('#palleon-redo').click();
            }

            // Check pressed button is D - Ctrl+D.
            if ( keyCode === 68 && layerActive.length ){
                useShortkey = true;
                layerActive.find('.duplicate-layer').click();
            }

            // Check pressed button is A - Ctrl+A.
            if ( keyCode === 65 ){
                useShortkey = true;
                selectAllObject();
            }
            if( useShortkey ) event.preventDefault();
        });

    };
    $(document).on('contextmenu', 'canvas', function (e) {
        e.preventDefault();
    });

})(jQuery);
