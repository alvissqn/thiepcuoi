
(function($) {
    "use strict";

    $(document).ready(function () {
        /* Initialize palleon plugin */
        let arrcanvas = [];
        let canvas_noibo = '';
        var currentCanvasIndex = 0;
        var selector = $(this);
        $('#palleon').palleon({
            baseURL: "/assets/photo-editor/", // The url of the main directory. For example; "http://www.mysite.com/palleon-js/"

            //////////////////////* CANVAS SETTINGS *//////////////////////
            fontFamily: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif", // Should be a web safe font
            fontSize: 60, // Default font size
            fontWeight: 'normal', // e.g. bold, normal, 400, 600, 800
            fontStyle: 'normal', // Possible values: "", "normal", "italic" or "oblique".
            canvasColor: 'transparent', // Canvas background color
            fill: '#000', // Default text color
            stroke: '#fff', // Default stroke color
            strokeWidth: 0, // Default stroke width
            textBackgroundColor: 'rgba(255,255,255,0)', // Default text background color
            textAlign: 'left', // Possible values: "", "left", "center" or "right".
            lineHeight: 1.2, // Default line height.
            borderColor: '#8b3dff', // Color of controlling borders of an object (when it's active).
            borderDashArray: [4, 4], // Array specifying dash pattern of an object's borders (hasBorder must be true).
            borderOpacityWhenMoving: 0.5, // Opacity of object's controlling borders when object is active and moving.
            borderScaleFactor: 2, // Scale factor of object's controlling borders bigger number will make a thicker border border is 1, so this is basically a border thickness since there is no way to change the border itself.
            editingBorderColor: 'rgba(0,0,0,0.5)', // Editing object border color.
            cornerColor: '#fff', // Color of controlling corners of an object (when it's active).
            cornerSize: 12, // Size of object's controlling corners (in pixels).
            cornerStrokeColor: '#333', // Color of controlling corners of an object (when it's active and transparentCorners false).
            cornerStyle: 'circle', // Specify style of control, 'rect' or 'circle'.
            transparentCorners: false, // When true, object's controlling corners are rendered as transparent inside (i.e. stroke instead of fill).
            cursorColor: '#000', // Cursor color (Free drawing)
            cursorWidth: 2, // Cursor width (Free drawing)
            enableGLFiltering: true, // set false if you experience issues on image filters.
            textureSize: 4096, // Required for enableGLFiltering

            //////////////////////* CUSTOM FUNCTIONS *//////////////////////
            customFunctions: function(selector, canvas, lazyLoadInstance) {

                /**
                 * @see http://fabricjs.com/fabric-intro-part-1#canvas
                 * You may need to update "lazyLoadInstance" if you are going to populate items of a grid with ajax.
                 * lazyLoadInstance.update();
                 * @see https://github.com/verlok/vanilla-lazyload
                 */

                /* Template - Add to Favorite */
                selector.find('.template-grid').on('click','.template-favorite button.star',function(){
                    var button = $(this);
                    var templateid = button.data('templateid');
                    var status = button.hasClass('favorited') ? 'remove' : 'favorite';
                    var favorite = new FormData;

                    favorite.append('templateid',templateid);
                    favorite.append('status',status);
                    $.ajax({
                        url: palleonParams.favoriteurl,
                        headers: palleonParams.token,
                        data: favorite,
                        type: 'POST',
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        success: function(data){
                            toastr.success(palleonParams.tempsaved, palleonParams.success);
                            // selector.find(".palleon-modal").hide();
                        },
                        error: function(){
                            toastr.success("Để 'các chức năng lưu' hoạt động, bạn nên có một cơ sở dữ liệu trên máy chủ của mình và tích hợp nó vào palleon bằng ngôn ngữ phía máy chủ. Xem Tài liệu -> Tích hợp.", "Info");
                        }
                    });

                    /* Do what you want */


                    // toastr.error("Error!", "Lorem ipsum dolor");
                });

                /* Frame - Add to Favorite */
                selector.find('.palleon-frames-grid').on('click','.frame-favorite button.star',function(){
                    var button = $(this);
                    var frameid = button.data('frameid');

                    /* Do what you want */

                    toastr.success("To make 'saving functions' work, you should have a database on your server and integrate it to palleon using a server-side language. See Documentation -> Integration.", "Info");
                    // toastr.error("Error!", "Lorem ipsum dolor");
                });

                /* Element - Add to Favorite */
                selector.find('.palleon-grid').on('click','.element-favorite button.star',function(){
                    var button = $(this);
                    var elementid = button.data('elementid');

                    console.log(elementid);
                    /* Do what you want */

                    toastr.success("To make 'saving functions' work, you should have a database on your server and integrate it to palleon using a server-side language. See Documentation -> Integration.", "Info");
                    // toastr.error("Error!", "Lorem ipsum dolor");
                });

                /* Delete Template From Library */
                selector.find('.palleon-template-list').on('click','.palleon-template-delete',function(){
                    var answer = window.confirm("Are you sure you want to delete the template permanently?");
                    if (answer) {
                        var target = $(this).data('target');
                        $(this).parent().parent().remove();

                        /* Do what you want */

                        toastr.success("To make 'saving functions' work, you should have a database on your server and integrate it to palleon using a server-side language. See Documentation -> Integration.", "Info");
                        // toastr.error("Error!", "Lorem ipsum dolor");
                    }
                });

                /* Upload Image To Media Library */
                selector.find('#palleon-library-upload-img').on('change', function (e) {
                    var file_data = this.files[0];
                    var data_f = new FormData();
                    /* Do what you want */
                    data_f.append("file", file_data);
                    data_f.append("action", "uploadImgToLibrary");
                    $.ajax({
                        url: palleonParams.libraryurl,
                        type: "POST",
                        processData: false,
                        contentType: false,
                        headers: palleonParams.token,
                        data: data_f,
                        success: function(e) {
                            selector.find("#palleon-library-my-refresh").trigger("click")
                        },
                        error: function(e, a, t) {
                            e.status && 400 == e.status ? toastr.error(e.responseText, palleonParams.error) : toastr.error(palleonParams.wrong, palleonParams.error);
                            toastr.success("To make 'saving functions' work, you should have a database on your server and integrate it to palleon using a server-side language. See Documentation -> Integration.", "Info");
                        }
                    }).done(function(e) {
                        // !1 === e.success ? toastr.error(e.data, palleonParams.error) : toastr.success(palleonParams.uploaded, palleonParams.success)

                    })

                    // toastr.error("Error!", "Lorem ipsum dolor");
                });

                /* Delete Image From Media Library */
                selector.find('.media-library-grid').on('click','.palleon-library-delete',function(){
                    var answer = window.confirm("Are you sure you want to delete the image permanently?");
                    if (answer) {
                        var target = $(this).data('target');
                        $(this).parent().remove();

                        /* Do what you want */

                        toastr.success("Deleted!", "Lorem ipsum dolor");
                        // toastr.error("Error!", "Lorem ipsum dolor");
                    }
                });

                /* Upload SVG To Media Library */
                selector.find('#palleon-svg-library-upload-img').on('change', function (e) {
                    var file_data = this.files[0];

                    var data_f = new FormData();
                    /* Do what you want */
                    data_f.append("file", file_data);
                    data_f.append("action", "uploadSvgToLibrary");

                    $.ajax({
                        url: palleonParams.libraryurl,
                        type: "POST",
                        processData: false,
                        contentType: false,
                        headers: palleonParams.token,
                        data: data_f,
                        success: function(e) {
                            selector.find("#palleon-library-my-refresh").trigger("click")
                        },
                        error: function(e, a, t) {
                            e.status && 400 == e.status ? toastr.error(e.responseText, palleonParams.error) : toastr.error(palleonParams.wrong, palleonParams.error);
                            toastr.success("To make 'saving functions' work, you should have a database on your server and integrate it to palleon using a server-side language. See Documentation -> Integration.", "Info");
                        }
                    }).done(function(e) {
                        // !1 === e.success ? toastr.error(e.data, palleonParams.error) : toastr.success(palleonParams.uploaded, palleonParams.success)

                    })

                    // toastr.error("Error!", "Lorem ipsum dolor");
                });

                /* Delete SVG From Media Library */
                selector.find('.svg-library-grid').on('click','.palleon-svg-library-delete',function(){
                    var answer = window.confirm("Are you sure you want to delete the image permanently?");
                    if (answer) {
                        var target = $(this).data('target');
                        $(this).parent().remove();

                        /* Do what you want */

                        toastr.success("Deleted!", "Lorem ipsum dolor");
                        // toastr.error("Error!", "Lorem ipsum dolor");
                    }
                });

                // Save preferences
                selector.find('#palleon-preferences-save').on('click', function() {
                    var button = $(this);
                    var settings = {};
                    var keys = [];
                    var values = [];
                    selector.find('#palleon-preferences .preference').each(function(index, value) {
                        keys.push($(this).attr('id'));
                        values.push($(this).val());
                    });

                    for (let i = 0; i < keys.length; i++) {
                        settings[keys[i]] = values[i];
                    }

                    var preferences = JSON.stringify(settings);

                    /* Do what you want */

                    toastr.success("To make 'saving functions' work, you should have a database on your server and integrate it to palleon using a server-side language. See Documentation -> Integration.", "Info");
                    // toastr.error("Error!", "Lorem ipsum dolor");

                });

                selector.find('#modal-svg-library .palleon-tabs-menu').on('click','li',function(){
                    var idTarget = $(this).data('target');
                    //getLibrary();
                });
                function getLibrary(){
                    $.ajax({
                        type: 'GET',
                        crossDomain: true,
                        dataType: 'json',
                        url: 'http://thiepcuoi.com/admin/tools/get-library',
                        success: function(jsondata){
                            $.each(jsondata['result'], function(key,value) {
                                $('.palleon-grid').append("abc");

                            });
                        }
                    })

                }
            },



            //////////////////////* SAVE TEMPLATE *//////////////////////
            saveTemplate: function(template) {
                /**
                 * var template is JSON string
                 * @see http://fabricjs.com/docs/fabric.Canvas.html#toDataURL
                 */

                var name = selector.find('#palleon-json-save-name').val();

                // console.log(JSON.stringify(template));
                var date_time = (new Date).getTime();
                var dulieu = new FormData;

                dulieu.append('name',name);
                dulieu.append('filename',date_time);
                dulieu.append('json',template);
                dulieu.append('action','saveJson');
                $.ajax({
                    url: palleonParams.ajaxurl,
                    headers: palleonParams.token,
                    data: dulieu,
                    type: 'POST',
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(data){
                        console.log(data);
                        selector.find("#palleon-my-templates-refresh").trigger("click");
                        toastr.success(palleonParams.tempsaved, palleonParams.success);
                        selector.find(".palleon-modal").hide();
                    },
                    error: function(){
                        toastr.success("Để 'các chức năng lưu' hoạt động, bạn nên có một cơ sở dữ liệu trên máy chủ của mình và tích hợp nó vào palleon bằng ngôn ngữ phía máy chủ. Xem Tài liệu -> Tích hợp.", "Info");
                    }
                });

                /* Do what you want */


                // toastr.error("Error!", "Lorem ipsum dolor");
            },

            //////////////////////* SAVE IMAGE *//////////////////////
            saveImage: function(imgData) {
                /**
                 * var imgData is DataURL
                 * @see https://flaviocopes.com/data-urls/
                 * @see http://fabricjs.com/docs/fabric.Canvas.html#toDataURL
                 */

                // var name = selector.find('#palleon-save-img-name').val();
                // var quality = parseFloat(selector.find('#palleon-save-img-quality').val());
                // var format = selector.find('#palleon-save-img-format').val();

                console.log(this);
                // console.log(imgData);

                /* Do what you want */

                toastr.success("To make 'saving functions' work, you should have a database on your server and integrate it to palleon using a server-side language. See Documentation -> Integration.", "Info");
                // toastr.error("Error!", "Lorem ipsum dolor");
            }
        });


        /* add ruler */
    });

})(jQuery);
