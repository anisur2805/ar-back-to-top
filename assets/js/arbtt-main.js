jQuery(document).ready(function ($) {
    "use strict";

    // Select2 for pages multiselect
    if ($.fn.select2 && $('#arbtt_display_pages').length) {
        $('#arbtt_display_pages').select2({
            placeholder: 'Search and select pages/posts...',
            allowClear: true,
            width: '100%'
        });
    }

    // SVG icon data passed from PHP via wp_localize_script.
    var svgIcons = (typeof arbtt_svg_icons !== 'undefined') ? arbtt_svg_icons : [];

    // Live preview update
    function updatePreview() {
        var $btn = $('#arbtt-preview-btn');
        if (!$btn.length) return;

        $btn.css({
            'background-color': $('#arbtt_bgc').val() || '#000',
            'color': ($('#arbtt_clr').val() || '#fff'),
            'border-radius': (function() {
                var shape = $('#arbtt_btn_shape').val();
                if (shape === 'circle') return '50%';
                if (shape === 'square') return '0';
                if (shape === 'rounded') return '8px';
                return ($('#arbtt_bdrd').val() || '5') + 'px';
            })(),
            'border': ($('#arbtt_bdr').val() || '2') + 'px solid ' + ($('#arbtt_bdr_color').val() || '#fff'),
            'opacity': $('#arbtt_btnoc').val() || '0.5',
            'width': ($('.arbtt_btndm').first().val() || '40') + 'px',
            'height': ($('.arbtt_btndm').last().val() || '40') + 'px',
        });

        // Position
        var pos = $('#arbtt_btnps').val() || 'right';
        $btn.css({ 'left': '', 'right': '', 'transform': '' });
        if (pos === 'center') {
            $btn.css({ 'left': '50%', 'transform': 'translateX(-50%)' });
        } else if (pos === 'left') {
            $btn.css('left', '8px');
        } else {
            $btn.css('right', '8px');
        }

        // Icon
        var style = $('#arbtt_btnst').val();
        var $icon = $btn.find('.arbtt-preview-btn-icon');
        var iconSize = ($('#arbtt_fz').val() || '20') + 'px';
        $icon.attr('class', 'arbtt-preview-btn-icon').empty().css({ 'background': '', 'border-radius': '' });

        if (style === 'fa') {
            var iconId = $('#arbtt_fi_picker').val() || 'angle-up';
            var iconSvg = '';
            for (var i = 0; i < svgIcons.length; i++) {
                if (svgIcons[i].id === iconId) { iconSvg = svgIcons[i].svg; break; }
            }
            if (iconSvg) {
                $icon.html(iconSvg);
                $icon.find('svg').css({ 'width': iconSize, 'height': iconSize, 'fill': ($('#arbtt_clr').val() || '#fff') });
            }
        } else if (style === 'txt') {
            $icon.text($('#arbtt_btntx').val() || 'Top');
            $icon.css({ 'color': $('#arbtt_clr').val() || '#fff', 'font-size': iconSize });
        } else if (style === 'upload' || style === 'img' || style === 'external') {
            var imgUrl = '';
            if (style === 'upload') {
                imgUrl = $('#arbtt_custom_icon_url').val();
            } else if (style === 'external') {
                imgUrl = $('#arbtt_btn_ext_img_url').val();
            } else if (style === 'img') {
                imgUrl = $('input[name="arbtt_btn_img"]:checked').closest('label').find('img').attr('src') || '';
            }
            if (imgUrl) {
                $icon.html('<img src="' + imgUrl + '" class="arbtt-preview-img" alt="" />');
            }
        } else {
            $icon.attr('class', 'arbtt-preview-btn-icon fa-solid fa-angle-up');
            $icon.css({ 'color': $('#arbtt_clr').val() || '#fff', 'font-size': iconSize });
        }
    }

    // Bind preview updates to all relevant inputs
    $('#arbtt input, #arbtt select, #arbtt textarea').on('change input', function() {
        updatePreview();
    });
    setTimeout(updatePreview, 100);

    const $progressBar         = $("input[name='arbtt_enable_scroll_progress']");
    const $progressBarFields   = $("input[name='arbtt_enable_scroll_progress_size']").closest("tr");
    const $progressBarColorRow = $("input[name='arbtt_progress_color']").closest("tr");
    const $buttonStyleSelect   = $('#arbtt_btnst');
    const $imgPositionRow      = $('#arbtt_btn_img_position').closest('tr');

    $(".arbtt_fi, .arbtt_btn_img, #arbtt_btntx, .arbtt_btn_ext_img_url")
        .parents("tr")
        .hide();
    function handleButtonPosition(val) {
        const $left = $("#arbtt_btn_offset_left").parents("tr");
        const $right = $("#arbtt_btn_offset_right").parents("tr");
        const $bottom = $("#arbtt_btn_offset_bottom").parents("tr");
        $bottom.show();
        if (val === "left") {
            $left.show();
            $right.hide();
        } else if (val === "right") {
            $right.show();
            $left.hide();
        } else {
            $left.hide();
            $right.hide();
        }
    }
    handleButtonStyle($("#arbtt_btnst").val());
    handleButtonPosition($("#arbtt_btnps").val());
    $("#arbtt_bgc, #arbtt_clr, .ar-btt-color").minicolors();
    $("#arbtt_btnst").on("change", function () {
        handleButtonStyle($(this).val());
        toggleImagePositionRow();

        handleProgressBarToggle();
    });
    $("#arbtt_btnps").on("change", function () {
        handleButtonPosition($(this).val());
    });
    $(".arbtt-toggle-next").each(function () {
        const $checkbox = $(this);
        const rowsToToggle = parseInt($checkbox.data("toggle-next"), 10) || 1;
        function toggleRows() {
            const $currentRow = $checkbox.closest("tr");
            let $targetRow = $currentRow;
            for (let i = 0; i < rowsToToggle; i++) {
                $targetRow = $targetRow.next("tr");
            }
            $targetRow.toggle($checkbox.is(":checked"));
        }
        toggleRows();
        $checkbox.on("change", toggleRows);
    });
    function renderIconList(filter) {
        filter = filter || '';
        var $container = $(".arbtt-fa-icon-list").empty();
        svgIcons.forEach(function(icon) {
            if (!filter || icon.id.includes(filter.toLowerCase()) || icon.label.toLowerCase().includes(filter.toLowerCase())) {
                $container.append(
                    '<div class="arbtt-icon-item" data-icon="' + icon.id + '" title="' + icon.label + '">' + icon.svg + '</div>'
                );
            }
        });
    }
    $(".arbtt-open-icon-picker").on("click", function () {
        renderIconList();
        $("#arbtt-fa-icon-modal").fadeIn();
        $("body").addClass("arbtt-modal-open");
    });
    $("#arbtt-fa-search").on("input", function () {
        renderIconList($(this).val());
    });
    $(document).on("click", ".arbtt-fa-icon-list .arbtt-icon-item", function () {
        var selectedId = $(this).data("icon");
        var selectedSvg = $(this).html();
        $("#arbtt_fi_picker").val(selectedId);
        $(".arbtt-preview-icon").html(selectedSvg);
        $("#arbtt-fa-icon-modal").fadeOut();
        $("body").removeClass("arbtt-modal-open");
        updatePreview();
    });
    $(document).on("keydown", function (e) {
        if (e.key === "Escape") {
            $("#arbtt-fa-icon-modal").fadeOut();
            $("body").removeClass("arbtt-modal-open");
        }
    });
    $(document).on("click", ".arbtt-fa-modal-header button", function () {
        $("#arbtt-fa-icon-modal").fadeOut();
        $("body").removeClass("arbtt-modal-open");
    });
    $("#arbtt_btn_ext_img_url").on("blur", function () {
        $(".arbtt-preview-img").attr("src", $(this).val());
    });
    function handleButtonStyle(val) {
        const $fiRow = $("#arbtt_fi_picker").closest("tr");
        const $txtRow = $("#arbtt_btntx").closest("tr");
        const $imgRow = $(".arbtt-image").closest("tr");
        const $imgUrlRow = $(".arbtt_btn_ext_img_url").closest("tr");
        const $extRow = $(".arbtt_btn_ext_img_url").closest("tr");
        const $uploadRow = $("#arbtt_custom_icon_url").closest("tr");
        const displayIfBoth = $(".shown-if-both");
        const dimension = $(".arbtt_btndm").closest("tr");
        $fiRow.add($txtRow).add($imgRow).add($imgUrlRow).add($uploadRow).hide();
        switch (val) {
            case "fa":
                $fiRow.show("blind");
                displayIfBoth.hide();
                break;
            case "txt":
                $txtRow.show("blind");
                displayIfBoth.hide();
                $fiRow.hide();
                break;
            case "img":
                $imgRow.show("blind");
                displayIfBoth.hide();
                break;
            case "external":
                $extRow.show("blind");
                displayIfBoth.hide();
                break;
            case "upload":
                $uploadRow.show("blind");
                displayIfBoth.hide();
                break;
            case "both":
                $txtRow.show("blind");
                $imgRow.show("blind");
                displayIfBoth.show();
                dimension.hide();
                $("input[name='arbtt_enable_scroll_progress']").prop("checked", false);
                handleProgressBarToggle();
                break;
        }
    }
    // Button Shape → Border Radius dependency (must be before handleProgressBarToggle)
    const $btnShape = $('#arbtt_btn_shape');
    const $bdrdRow  = $('#arbtt_bdrd').closest('tr');

    function toggleBorderRadiusRow() {
        $bdrdRow.toggle($btnShape.val() === 'custom' && !$btnShape.prop('disabled'));
    }

    function handleProgressBarToggle() {
        const isEnabled = $progressBar.is(":checked") && $("#arbtt_btnst").val() !== 'both';

        $progressBarFields.toggle(isEnabled);
        $progressBarColorRow.toggle(isEnabled);

        // Progress requires circle — lock the shape dropdown.
        var $shapeSelect = $('#arbtt_btn_shape');
        if (isEnabled) {
            $shapeSelect.val('circle').prop('disabled', true);
            $shapeSelect.closest('tr').find('.arbtt-shape-notice').remove();
            $shapeSelect.after('<span class="arbtt-shape-notice description" style="margin-left:8px;color:#d63638;">Scroll progress requires circle shape</span>');
            // Disabled fields don't submit — add a hidden input.
            $shapeSelect.closest('td').find('.arbtt-shape-hidden').remove();
            $shapeSelect.closest('td').append('<input type="hidden" name="arbtt_btn_shape" value="circle" class="arbtt-shape-hidden" />');
        } else {
            $shapeSelect.prop('disabled', false);
            $shapeSelect.closest('tr').find('.arbtt-shape-notice').remove();
            $shapeSelect.closest('td').find('.arbtt-shape-hidden').remove();
        }

        // Also update border radius row visibility.
        if (typeof toggleBorderRadiusRow === 'function') {
            toggleBorderRadiusRow();
        }

        // Update live preview to reflect shape change.
        updatePreview();
    }

    // Initial state on page load
    handleProgressBarToggle();

    // Bind change event
    $progressBar.on("change", handleProgressBarToggle);

    function toggleImagePositionRow() {
        const selectedStyle = $buttonStyleSelect.val();
        $imgPositionRow.toggle(selectedStyle == 'both');
    }

    // Initial check
    toggleImagePositionRow();

    // On change
    $buttonStyleSelect.on('change', toggleImagePositionRow);

    // Display Mode → Select Pages dependency
    const $displayMode     = $('#arbtt_display_mode');
    const $displayPagesRow = $('#arbtt_display_pages').closest('tr');

    function toggleDisplayPagesRow() {
        const mode = $displayMode.val();
        $displayPagesRow.toggle(mode === 'include' || mode === 'exclude');
    }

    toggleDisplayPagesRow();
    $displayMode.on('change', toggleDisplayPagesRow);

    // Custom Icon Upload via wp.media
    $('#arbtt_upload_icon_btn').on('click', function(e) {
        e.preventDefault();
        var frame = wp.media({
            title: 'Select Custom Icon',
            button: { text: 'Use this icon' },
            multiple: false,
            library: { type: ['image', 'image/svg+xml'] }
        });
        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            $('#arbtt_custom_icon_url').val(attachment.url);
            $('.arbtt-custom-icon-preview').show().find('img').attr('src', attachment.url);
            $('#arbtt_remove_icon_btn').show();
        });
        frame.open();
    });

    $('#arbtt_remove_icon_btn').on('click', function(e) {
        e.preventDefault();
        $('#arbtt_custom_icon_url').val('');
        $('.arbtt-custom-icon-preview').hide().find('img').attr('src', '');
        $(this).hide();
    });

    // Mobile Offset fields: show when mobile is NOT hidden
    const $hideOnPhone        = $('#arbtt_hide_on_phone');
    const $mobileOffsetBottom = $('#arbtt_mobile_offset_bottom').closest('tr');
    const $mobileOffsetSide   = $('#arbtt_mobile_offset_side').closest('tr');

    function toggleMobileOffsets() {
        var show = !$hideOnPhone.is(':checked');
        $mobileOffsetBottom.toggle(show);
        $mobileOffsetSide.toggle(show);
    }

    toggleMobileOffsets();
    $hideOnPhone.on('change', toggleMobileOffsets);

    toggleBorderRadiusRow();
    $btnShape.on('change', toggleBorderRadiusRow);

    // === Tab navigation ===
    $('.arbtt-tabs .nav-tab').on('click', function(e) {
        e.preventDefault();
        var tabId = $(this).data('tab');

        $('.arbtt-tabs .nav-tab').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');

        $('.arbtt-tab-content').removeClass('arbtt-tab-active');
        $('#' + tabId).addClass('arbtt-tab-active');

        // Save active tab to localStorage
        if (window.localStorage) {
            localStorage.setItem('arbtt_active_tab', tabId);
        }
    });

    // Restore last active tab
    if (window.localStorage) {
        var savedTab = localStorage.getItem('arbtt_active_tab');
        if (savedTab && $('#' + savedTab).length) {
            $('.arbtt-tabs .nav-tab').removeClass('nav-tab-active');
            $('.arbtt-tabs .nav-tab[data-tab="' + savedTab + '"]').addClass('nav-tab-active');
            $('.arbtt-tab-content').removeClass('arbtt-tab-active');
            $('#' + savedTab).addClass('arbtt-tab-active');
        }
    }
});