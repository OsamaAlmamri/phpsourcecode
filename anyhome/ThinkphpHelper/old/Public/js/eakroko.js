// FLAT Theme v2.0
(function($) {
    $.fn.retina = function(retina_part) {
        // Set default retina file part to '-2x'
        // Eg. some_image.jpg will become some_image-2x.jpg
        var settings = {
            'retina_part': '-2x'
        };
        if (retina_part) jQuery.extend(settings, {
            'retina_part': retina_part
        });
        if (window.devicePixelRatio >= 2) {
            this.each(function(index, element) {
                if (!$(element).attr('src')) return;

                var checkForRetina = new RegExp("(.+)(" + settings['retina_part'] + "\\.\\w{3,4})");
                if (checkForRetina.test($(element).attr('src'))) return;

                var new_image_src = $(element).attr('src').replace(/(.+)(\.\w{3,4})$/, "$1" + settings['retina_part'] + "$2");
                $.ajax({
                    url: new_image_src,
                    type: "HEAD",
                    success: function() {
                        $(element).attr('src', new_image_src);
                    }
                });
            });
        }
        return this;
    }
})(jQuery);

function icheck() {
    if ($(".icheck-me").length > 0) {
        $(".icheck-me").each(function() {
            var $el = $(this);
            var skin = ($el.attr('data-skin') !== undefined) ? "_" + $el.attr('data-skin') : "",
                color = ($el.attr('data-color') !== undefined) ? "-" + $el.attr('data-color') : "";

            var opt = {
                checkboxClass: 'icheckbox' + skin + color,
                radioClass: 'iradio' + skin + color,
                increaseArea: "10%"
            }

            $el.iCheck(opt);
        });
    }
}
$(document).ready(function() {
    var mobile = false,
        tooltipOnlyForDesktop = true,
        notifyActivatedSelector = 'button-active';

    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
        mobile = true;
    }

    icheck();

    if ($(".complexify-me").length > 0) {
        $(".complexify-me").complexify(function(valid, complexity) {
            if (complexity < 40) {
                $(this).parent().find(".progress .bar").removeClass("bar-green").addClass("bar-red");
            } else {
                $(this).parent().find(".progress .bar").addClass("bar-green").removeClass("bar-red");
            }

            $(this).parent().find(".progress .bar").width(Math.floor(complexity) + "%").html(Math.floor(complexity) + "%");
        });
    }

    // Round charts (easypie)
    if ($(".chart").length > 0) {
        $(".chart").each(function() {
            var color = "#881302",
                $el = $(this);
            var trackColor = $el.attr("data-trackcolor");
            if ($el.attr('data-color')) {
                color = $el.attr('data-color');
            } else {
                if (parseInt($el.attr("data-percent")) <= 25) {
                    color = "#046114";
                } else if (parseInt($el.attr("data-percent")) > 25 && parseInt($el.attr("data-percent")) < 75) {
                    color = "#dfc864";
                }
            }
            $el.easyPieChart({
                animate: 1000,
                barColor: color,
                lineWidth: 5,
                size: 80,
                lineCap: 'square',
                trackColor: trackColor
            });
        });
    }

    // Calendar (fullcalendar)
    if ($('.calendar').length > 0) {
        $('.calendar').fullCalendar({
            header: {
                left: '',
                center: 'prev,title,next',
                right: 'month,agendaWeek,agendaDay,today'
            },
            buttonText: {
                today: 'Today'
            },
            editable: true
        });
        $(".fc-button-effect").remove();
        $(".fc-button-next .fc-button-content").html("<i class='fa fa-chevron-right'></i>");
        $(".fc-button-prev .fc-button-content").html("<i class='fa fa-chevron-left'></i>");
        $(".fc-button-today").addClass('fc-corner-right');
        $(".fc-button-prev").addClass('fc-corner-left');
    }

    // Tooltips (only for desktop) (bootstrap tooltips)
    if (tooltipOnlyForDesktop) {
        if (!mobile) {
            $('[rel=tooltip]').tooltip();
        }
    }


    // Notifications
    $(".notify").click(function() {
        var $el = $(this);
        var title = $el.attr('data-notify-title'),
            message = $el.attr('data-notify-message'),
            time = $el.attr('data-notify-time'),
            sticky = $el.attr('data-notify-sticky'),
            overlay = $el.attr('data-notify-overlay');

        $.gritter.add({
            title: (typeof title !== 'undefined') ? title : 'Message - Head',
            text: (typeof message !== 'undefined') ? message : 'Body',
            image: (typeof image !== 'undefined') ? image : null,
            sticky: (typeof sticky !== 'undefined') ? sticky : false,
            time: (typeof time !== 'undefined') ? time : 3000
        });
    });

    // masked input
    if ($('.mask_date').length > 0) {
        $(".mask_date").mask("9999/99/99");
    }
    if ($('.mask_phone').length > 0) {
        $(".mask_phone").mask("(999) 999-9999");
    }
    if ($('.mask_serialNumber').length > 0) {
        $(".mask_serialNumber").mask("9999-9999-99");
    }
    if ($('.mask_productNumber').length > 0) {
        $(".mask_productNumber").mask("aaa-9999-a");
    }
    // tag-input
    if ($(".tagsinput").length > 0) {
        $('.tagsinput').each(function(e) {
            $(this).tagsInput({
                width: 'auto',
                height: 'auto'
            });
        });
    }

    // datepicker
    if ($('.datepick').length > 0) {
        $('.datepick').datepicker();
    }

    // daterangepicker
    if ($('.daterangepick').length > 0) {
        $('.daterangepick').daterangepicker();
    }

    // timepicker
    if ($('.timepick').length > 0) {
        $('.timepick').timepicker({
            defaultTime: 'current',
            minuteStep: 1,
            disableFocus: true,
            template: 'dropdown'
        });
    }
    // colorpicker
    if ($('.colorpick').length > 0) {
        $('.colorpick').colorpicker();
    }
    // uniform
    if ($('.uniform-me').length > 0) {
        $('.uniform-me').uniform({
            radioClass: 'uni-radio',
            buttonClass: 'uni-button'
        });
    }
    // Chosen (chosen)
    if ($('.chosen-select').length > 0) {
        $('.chosen-select').each(function() {
            var $el = $(this);
            var search = ($el.attr("data-nosearch") === "true") ? true : false,
                opt = {};
            if (search) opt.disable_search_threshold = 9999999;
            $el.chosen(opt);
        });
    }

    if ($(".select2-me").length > 0) {
        $(".select2-me").select2();
    }

    // multi-select
    if ($('.multiselect').length > 0) {
        $(".multiselect").each(function() {
            var $el = $(this);
            var selectableHeader = $el.attr('data-selectableheader'),
                selectionHeader = $el.attr('data-selectionheader');
            if (selectableHeader != undefined) {
                selectableHeader = "<div class='multi-custom-header'>" + selectableHeader + "</div>";
            }
            if (selectionHeader != undefined) {
                selectionHeader = "<div class='multi-custom-header'>" + selectionHeader + "</div>";
            }
            $el.multiSelect({
                selectionHeader: selectionHeader,
                selectableHeader: selectableHeader
            });
        });
    }

    // spinner
    if ($('.spinner').length > 0) {
        $('.spinner').spinner();
    }

    // dynatree
    if ($(".filetree").length > 0) {
        $(".filetree").each(function() {
            var $el = $(this),
                opt = {};
            opt.debugLevel = 0;
            if ($el.hasClass("filetree-callbacks")) {
                opt.onActivate = function(node) {
                    $(".activeFolder").text(node.data.title);
                    $(".additionalInformation").html("<ul style='margin-bottom:0;'><li>Key: " + node.data.key + "</li><li>is folder: " + node.data.isFolder + "</li></ul>");
                };
            }
            if ($el.hasClass("filetree-checkboxes")) {
                opt.checkbox = true;

                opt.onSelect = function(select, node) {
                    var selNodes = node.tree.getSelectedNodes();
                    var selKeys = $.map(selNodes, function(node) {
                        return "[" + node.data.key + "]: '" + node.data.title + "'";
                    });
                    $(".checkboxSelect").text(selKeys.join(", "));
                };
            }

            $el.dynatree(opt);
        });
    }

    if ($(".colorbox-image").length > 0) {
        $(".colorbox-image").colorbox({
            maxWidth: "90%",
            maxHeight: "90%",
            rel: $(this).attr("rel")
        });
    }

    // PlUpload
    if ($('.plupload').length > 0) {
        $(".plupload").each(function() {
            var $el = $(this);
            $el.pluploadQueue({
                runtimes: 'html5,gears,flash,silverlight,browserplus',
                url: 'js/plupload/upload.php',
                max_file_size: '10mb',
                chunk_size: '1mb',
                unique_names: true,
                resize: {
                    width: 320,
                    height: 240,
                    quality: 90
                },
                filters: [{
                    title: "Image files",
                    extensions: "jpg,gif,png"
                }, {
                    title: "Zip files",
                    extensions: "zip"
                }],
                flash_swf_url: 'js/plupload/plupload.flash.swf',
                silverlight_xap_url: 'js/plupload/plupload.silverlight.xap'
            });
            $(".plupload_header").remove();
            var upload = $el.pluploadQueue();
            if ($el.hasClass("pl-sidebar")) {
                $(".plupload_filelist_header,.plupload_progress_bar,.plupload_start").remove();
                $(".plupload_droptext").html("<span>Drop files to upload</span>");
                $(".plupload_progress").remove();
                $(".plupload_add").text("Or click here...");
                upload.bind('FilesAdded', function(up, files) {
                    setTimeout(function() {
                        up.start();
                    }, 500);
                });
                upload.bind("QueueChanged", function(up) {
                    $(".plupload_droptext").html("<span>Drop files to upload</span>");
                });
                upload.bind("StateChanged", function(up) {
                    $(".plupload_upload_status").remove();
                    $(".plupload_buttons").show();
                });
            } else {
                $(".plupload_progress_container").addClass("progress").addClass('progress-striped');
                $(".plupload_progress_bar").addClass("bar");
                $(".plupload_button").each(function() {
                    if ($(this).hasClass("plupload_add")) {
                        $(this).attr("class", 'btn pl_add btn-primary').html("<i class='fa fa-plus-circle'></i> " + $(this).html());
                    } else {
                        $(this).attr("class", 'btn pl_start btn-success').html("<i class='fa fa-cloud-upload'></i> " + $(this).html());
                    }
                });
            }
        });
    }

    // Wizard
    if ($(".form-wizard").length > 0) {
        $(".form-wizard").formwizard({
            formPluginEnabled: true,
            validationEnabled: true,
            focusFirstInput: false,
            disableUIStyles: true,
            validationOptions: {
                errorElement: 'span',
                errorClass: 'help-block has-error',
                errorPlacement: function(error, element) {
                    if (element.parents("label").length > 0) {
                        element.parents("label").after(error);
                    } else {
                        element.after(error);
                    }
                },
                highlight: function(label) {
                    $(label).closest('.form-group').removeClass('has-error has-success').addClass('has-error');
                    console.log('aaa');
                },
                success: function(label) {
                    label.addClass('valid').closest('.form-group').removeClass('has-error has-success').addClass('has-success');
                }
            },
            formOptions: {
                success: function(data) {
                    alert("Response: \n\n" + data.say);
                },
                dataType: 'json',
                resetForm: true
            }
        });
    }

    // Validation
    if ($('.form-validate').length > 0) {
        $('.form-validate').each(function() {
            var id = $(this).attr('id');
            $("#" + id).validate({
                errorElement: 'span',
                errorClass: 'help-block has-error',
                errorPlacement: function(error, element) {
                    if (element.parents("label").length > 0) {
                        element.parents("label").after(error);
                    } else {
                        element.after(error);
                    }
                },
                highlight: function(label) {
                    $(label).closest('.form-group').removeClass('has-error has-success').addClass('has-error');
                },
                success: function(label) {
                    label.addClass('valid').closest('.form-group').removeClass('has-error has-success').addClass('has-success');
                },
                onkeyup: function(element) {
                    $(element).valid();
                },
                onfocusout: function(element) {
                    $(element).valid();
                }
            });
        });
    }

    // dataTables
    if ($('.dataTable').length > 0) {
        $('.dataTable').each(function() {
            if (!$(this).hasClass("dataTable-custom")) {
                var opt = {
                    "sPaginationType": "full_numbers",
                    "oLanguage": {
                        "sProcessing": "正在加载中......",
                        "sInfoFiltered": "数据表中共为 _MAX_ 条记录",
                        "sZeroRecords": "对不起，查询不到相关数据！",
                        "sSearch": "<span>搜索:</span> ",
                        "sInfo": "当前显示 <span>_START_</span> 到 <span>_END_</span> 条，共 <span>_TOTAL_</span> 条记录",
                        // "sLengthMenu": " 每页显示 _MENU_ 条记录",
                        "sLengthMenu": "_MENU_",
                        "oPaginate": {
                            "sFirst": "首页",
                            "sPrevious": "上一页",
                            "sNext": "下一页",
                            "sLast": "末页"
                        }
                    },
                    'sDom': "lfrtip"
                };
                if ($(this).hasClass("dataTable-state")) {
                    opt.bStateSave = true;
                }
                if ($(this).hasClass("dataTable-noheader")) {
                    opt.bFilter = false;
                    opt.bLengthChange = false;
                }
                if ($(this).hasClass("dataTable-nofooter")) {
                    opt.bInfo = false;
                    opt.bPaginate = false;
                }
                if ($(this).hasClass("dataTable-nosort")) {
                    var column = $(this).attr('data-nosort');
                    column = column.split(',');
                    for (var i = 0; i < column.length; i++) {
                        column[i] = parseInt(column[i]);
                    };
                    opt.aoColumnDefs = [{
                        'bSortable': false,
                        'aTargets': column
                    }];
                }
                if ($(this).hasClass("dataTable-scroll-x")) {
                    opt.sScrollX = "100%";
                    opt.bScrollCollapse = true;
                    $(window).resize(function() {
                        oTable.fnAdjustColumnSizing();
                    });
                }
                if ($(this).hasClass("dataTable-scroll-y")) {
                    opt.sScrollY = "300px";
                    opt.bPaginate = false;
                    opt.bScrollCollapse = true;
                    $(window).resize(function() {
                        oTable.fnAdjustColumnSizing();
                    });
                }
                if ($(this).hasClass("dataTable-reorder")) {
                    opt.sDom = "R" + opt.sDom;
                }
                if ($(this).hasClass("dataTable-colvis")) {
                    opt.sDom = "C" + opt.sDom;
                    opt.oColVis = {
                        "buttonText": "Change columns <i class='fa fa-angle-down'></i>"
                    };
                }
                if ($(this).hasClass('dataTable-tools')) {
                    opt.sDom = "T" + opt.sDom;
                    opt.oTableTools = {
                        "sSwfPath": "js/plugins/datatable/swf/copy_csv_xls_pdf.swf"
                    };
                }
                if ($(this).hasClass("dataTable-scroller")) {
                    opt.sScrollY = "300px";
                    opt.bDeferRender = true;
                    if ($(this).hasClass("dataTable-tools")) {
                        opt.sDom = 'TfrtiS';
                    } else {
                        opt.sDom = 'frtiS';
                    }
                    opt.sAjaxSource = "js/plugins/datatable/demo.txt";
                }
                if ($(this).hasClass("dataTable-grouping") && $(this).attr("data-grouping") == "expandable") {
                    opt.bLengthChange = false;
                    opt.bPaginate = false;
                }

                var oTable = $(this).dataTable(opt);
                $(this).css("width", '100%');
                $('.dataTables_filter input').attr("placeholder", "Search here...").addClass("form-control input-medium");
                $(".dataTables_length select").wrap("<div class='input-mini'></div>").chosen({
                    disable_search_threshold: 9999999
                });
                $("#check_all").click(function(e) {
                    $('input', oTable.fnGetNodes()).prop('checked', this.checked);
                });
                if ($(this).hasClass("dataTable-fixedcolumn")) {
                    new FixedColumns(oTable);
                }
                if ($(this).hasClass("dataTable-columnfilter")) {
                    oTable.columnFilter({
                        "sPlaceHolder": "head:after"
                    });
                }
                if ($(this).hasClass("dataTable-grouping")) {
                    var rowOpt = {};

                    if ($(this).attr("data-grouping") == 'expandable') {
                        rowOpt.bExpandableGrouping = true;
                    }
                    oTable.rowGrouping(rowOpt);
                }

                if ($(this).hasClass("dataTable-editable")) {
                    var editurl = $(this).attr('edit-url');
                    var unique_id = null;
                    oTable.makeEditable({
                        sUpdateURL: editurl,
                        fnOnEditing: function(input)
                        {
                            unique_id = $.gritter.add({
                                title: '数据更新!',
                                text: '更新中，请稍后.',
                                sticky: false, 
                                time: 5000,
                            });
                            return true;
                        },
                        fnOnEdited: function(status)
                        {   
                            $.gritter.remove(unique_id, { 
                                speed: 'fast' // optional
                            });
                            return true;
                        },
                    });
                }

                oTable.fnDraw();
                oTable.fnAdjustColumnSizing();
            }
        });
    }

    // force correct width for chosen
    resize_chosen();

    // file_management
    if ($('.file-manager').length > 0) {
        $('.file-manager').elfinder({
            url: 'js/plugins/elfinder/php/connector.php'
        });
    }

    // slider
    if ($('.slider').length > 0) {
        $(".slider").each(function() {
            var $el = $(this);
            var min = parseInt($el.attr('data-min')),
                max = parseInt($el.attr('data-max')),
                step = parseInt($el.attr('data-step')),
                range = $el.attr('data-range'),
                rangestart = parseInt($el.attr('data-rangestart')),
                rangestop = parseInt($el.attr('data-rangestop'));

            var opt = {
                min: min,
                max: max,
                step: step,
                slide: function(event, ui) {
                    $el.find('.amount').html(ui.value);
                }
            };

            if (range !== undefined) {
                opt.range = true;
                opt.values = [rangestart, rangestop];
                opt.slide = function(event, ui) {
                    $el.find('.amount').html(ui.values[0] + " - " + ui.values[1]);
                    $el.find(".amount_min").html(ui.values[0] + "$");
                    $el.find(".amount_max").html(ui.values[1] + "$");
                };
            }

            $el.slider(opt);
            if (range !== undefined) {
                var val = $el.slider('values');
                $el.find('.amount').html(val[0] + ' - ' + val[1]);
                $el.find(".amount_min").html(val[0] + "$");
                $el.find(".amount_max").html(val[1] + "$");
            } else {
                $el.find('.amount').html($el.slider('value'));
            }
        });
    }

    if ($(".ckeditor").length > 0) {
        CKEDITOR.replace("ck");
    }

    $(".retina-ready").retina("@2x");

});

$(window).resize(function() {
    // chosen resize bug
    resize_chosen();
});

function resize_chosen() {
    $('.chzn-container').each(function() {
        var $el = $(this);
        $el.css('width', $el.parent().width() + 'px');
        $el.find(".chzn-drop").css('width', ($el.parent().width() - 2) + 'px');
        $el.find(".chzn-search input").css('width', ($el.parent().width() - 37) + 'px');
    });
}
