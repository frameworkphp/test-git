$(function () {

    $(".chzn-select").chosen();

    //Preloading
    paceOptions = {
        startOnPageLoad: true,
        ajax: false, // disabled
        document: false, // disabled
        eventLag: false, // disabled
        elements: false
    };

    //
    $('.login-link').click(function (e) {
        e.preventDefault();
        href = $(this).attr('href');

        $('.login-wrapper').addClass('fadeOutUp');

        setTimeout(function () {
            window.location = href;
        }, 900);

        return false;
    });

    //scroll to top of the page
    $("#scroll-to-top").click(function () {
        $("html, body").animate({scrollTop: 0}, 600);
        return false;
    });

    //scrollable sidebar
    $('.scrollable-sidebar').slimScroll({
        height: '100%',
        size: '0px'
    });

    //Sidebar menu dropdown
    $('aside li').hover(
        function () {
            $(this).addClass('open')
        },
        function () {
            $(this).removeClass('open')
        }
    )

    //Collapsible Sidebar Menu
    $('.openable > a').click(function () {
        if (!$('#wrapper').hasClass('sidebar-mini')) {
            if ($(this).parent().children('.submenu').is(':hidden')) {
                $(this).parent().siblings().removeClass('open').children('.submenu').slideUp();
                $(this).parent().addClass('open').children('.submenu').slideDown();
            }
            else {
                $(this).parent().removeClass('open').children('.submenu').slideUp();
            }
        }

        return false;
    });

    //Toggle Menu
    $('#sidebarToggle').click(function () {
        $('#wrapper').toggleClass('sidebar-display');
        $('.main-menu').find('.openable').removeClass('open');
        $('.main-menu').find('.submenu').removeAttr('style');
    });

    $('#sizeToggle').click(function () {

        $('#wrapper').off("resize");

        $('#wrapper').toggleClass('sidebar-mini');
        $('.main-menu').find('.openable').removeClass('open');
        $('.main-menu').find('.submenu').removeAttr('style');
    });

    if (!$('#wrapper').hasClass('sidebar-mini')) {
        if (Modernizr.mq('(min-width: 768px)') && Modernizr.mq('(max-width: 868px)')) {
            $('#wrapper').addClass('sidebar-mini');
        }
        else if (Modernizr.mq('(min-width: 869px)')) {
            if (!$('#wrapper').hasClass('sidebar-mini')) {
            }
        }
    }

    //show/hide menu
    $('#menuToggle').click(function () {
        $('#wrapper').toggleClass('sidebar-hide');
        $('.main-menu').find('.openable').removeClass('open');
        $('.main-menu').find('.submenu').removeAttr('style');
    });

    $(window).resize(function () {
        if (Modernizr.mq('(min-width: 768px)') && Modernizr.mq('(max-width: 868px)')) {
            $('#wrapper').addClass('sidebar-mini').addClass('window-resize');
            $('.main-menu').find('.openable').removeClass('open');
            $('.main-menu').find('.submenu').removeAttr('style');
        }
        else if (Modernizr.mq('(min-width: 869px)')) {
            if ($('#wrapper').hasClass('window-resize')) {
                $('#wrapper').removeClass('sidebar-mini window-resize');
                $('.main-menu').find('.openable').removeClass('open');
                $('.main-menu').find('.submenu').removeAttr('style');
            }
        }
        else {
            $('#wrapper').removeClass('sidebar-mini window-resize');
            $('.main-menu').find('.openable').removeClass('open');
            $('.main-menu').find('.submenu').removeAttr('style');
        }
    });

    //fixed Sidebar
    $('#fixedSidebar').click(function () {
        if ($(this).prop('checked')) {
            $('aside').addClass('fixed');
        }
        else {
            $('aside').removeClass('fixed');
        }
    });

    //Inbox sidebar (inbox.html)
    $('#inboxMenuToggle').click(function () {
        $('#inboxMenu').toggleClass('menu-display');
    });

    //Collapse panel
    $('.collapse-toggle').click(function () {

        $(this).parent().toggleClass('active');

        var parentElm = $(this).parent().parent().parent().parent();

        var targetElm = parentElm.find('.panel-body');

        targetElm.toggleClass('collapse');
    });

    //Number Animation
    var currentVisitor = $('#currentVisitor').text();

    $({numberValue: 0}).animate({numberValue: currentVisitor}, {
        duration: 2500,
        easing: 'linear',
        step: function () {
            $('#currentVisitor').text(Math.ceil(this.numberValue));
        }
    });

    var currentBalance = $('#currentBalance').text();

    $({numberValue: 0}).animate({numberValue: currentBalance}, {
        duration: 2500,
        easing: 'linear',
        step: function () {
            $('#currentBalance').text(Math.ceil(this.numberValue));
        }
    });

    //Refresh Widget
    $('.refresh-widget').click(function () {
        var _overlayDiv = $(this).parent().parent().parent().parent().find('.loading-overlay');
        _overlayDiv.addClass('active');

        setTimeout(function () {
            _overlayDiv.removeClass('active');
        }, 2000);

        return false;
    });

    //Check all	checkboxes

    $('.chk-item').click(function () {
        if ($(this).is(':checked')) {
            $(this).parent().parent().addClass('selected');
        }
        else {
            $(this).parent().parent().removeClass('selected');
        }
    });


    //Hover effect on touch device
    $('.image-wrapper').bind('touchstart', function (e) {
        $('.image-wrapper').removeClass('active');
        $(this).addClass('active');
    });

    //Dropdown menu with hover
    $('.hover-dropdown').hover(
        function () {
            $(this).addClass('open')
        },
        function () {
            $(this).removeClass('open')
        }
    )

    //upload file
    $('.upload').change(function () {
        var filename = $(this).val().split('\\').pop();
        $(this).parent().find('span').attr('data-title', filename);
        $(this).parent().find('label').attr('data-title', 'Change file');
        $(this).parent().find('label').addClass('selected');
    });

    $('.remove-file').click(function () {
        $(this).parent().find('span').attr('data-title', 'No file...');
        $(this).parent().find('label').attr('data-title', 'Select file');
        $(this).parent().find('label').removeClass('selected');

        return false;
    });

    // Popover
    $("[data-toggle=popover]").popover();

    // Tooltip
    $("[data-toggle=tooltip]").tooltip();


    var checked = true;

    $('#chk-all').click(function () {
        if ($(this).is(':checked')) {
            $('#responsiveTable').find('.chk-row').each(function () {
                $(this).prop('checked', true);
                $(this).parent().parent().parent().addClass('selected');
            });
        } else {
            $('#responsiveTable').find('.chk-row').each(function () {
                $(this).prop('checked', false);
                $(this).parent().parent().parent().removeClass('selected');
            });
        }

        var theForm = document.appForm;
        var countCheckBox = 0;
        var i;
        for (i = 0; i < theForm.elements.length; i++) {
            if (theForm.elements[i].name == 'cid[]') {
                countCheckBox++;
            }
        }

        if (checked == true) {
            theForm.boxChecked.value = countCheckBox;
            checked = false;
        } else {
            theForm.boxChecked.value = 0;
            checked = true;
        }
    });

    $('.chk-row').click(function () {
        if ($(this).is(':checked')) {
            $(this).parent().parent().parent().addClass('selected');
            document.appForm.boxChecked.value++;
        }
        else {
            $(this).parent().parent().parent().removeClass('selected');
            $('#chk-all').prop('checked', false);
            checked = true;
            document.appForm.boxChecked.value--;
        }
    });

    $('#bulk-action').click(function () {
        var action = $('select[name="selectBulkAction"] option:selected').val();
        if (action != 0) {
            if (document.appForm.boxChecked.value > 0) {
                submitForm("/" + action);
            } else {
                toastr.error("Let's select a item to implement. Please!");
            }
        } else {
            toastr.error("Let's select a action. Please!");
        }
    });

    $('#form-filter').click(function () {
        var queryUrl = {};
        var keyword = $('input[name^="q"]').val();
        if (keyword != '') {
            queryUrl['q'] = keyword;
        }

        // Get param filter
        $('select[name^="filter"]').each(function() {
            var type = $(this).attr('data-type');
            if ($(this).val() != 'all') {
                queryUrl[type] = $(this).val();
            }
        });

        // Get param sort
        var sort = $('input[name^="sort"]').val();
        var dir = $('input[name^="dir"]').val();
        if (sort != '') {
            queryUrl['sort'] = sort;
        }

        if (dir != '') {
            queryUrl['dir'] = dir;
        }

        var str = jQuery.param( queryUrl );
        if (str != '') {
            window.location.href = '?' + str;
        } else {
            var url = window.location.pathname;
            console.log(url);
        }
    });

});

$(window).load(function () {
    //Stop preloading animation
    Pace.stop();

    // Fade out the overlay div
    $('#overlay').fadeOut(800);

    $('body').removeAttr('class');

    //Enable animation
    $('#wrapper').removeClass('preload');

    //Collapsible Active Menu
    if (!$('#wrapper').hasClass('sidebar-mini')) {
        $('aside').find('.active.openable').children('.submenu').slideDown();
    }
});

$(window).scroll(function () {

    var position = $(window).scrollTop();

    //Display a scroll to top button
    if (position >= 200) {
        $('#scroll-to-top').attr('style', 'bottom:8px;');
    }
    else {
        $('#scroll-to-top').removeAttr('style');
    }
});

/**
 * Submit the admin form
 */
function submitForm(action) {
    if (action) {
        document.appForm.action = action;
    }
    if (typeof document.appForm.onsubmit == "function") {
        document.appForm.onsubmit();
    }
    document.appForm.submit();
}