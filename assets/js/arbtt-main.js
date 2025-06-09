jQuery(document).ready(function ($) {
    "use strict";

    const $progressBar         = $("input[name='arbtt_enable_scroll_progress']");
    const $progressBarFields   = $("input[name='arbtt_enable_scroll_progress_size']").closest("tr");
    const $progressBarColorRow = $("input[name='arbtt_progress_color']").closest("tr");
    const $buttonStyleSelect   = $('#arbtt_btnst');
    const $imgPositionRow      = $('#arbtt_btn_img_position').closest('tr');

    $(".arbtt_fi, .arbtt_btn_img, #arbtt_btntx, .arbtt_btn_ext_img_url")
        .parents("tr")
        .hide();
    function handleButtonStyle(val) {
        const $fi = $(".arbtt_fi").parents("tr");
        const $txt = $("#arbtt_btntx").parents("tr");
        const $img = $(".arbtt_btn_img").parents("tr");
        $fi.add($txt).add($img).hide();
        if (val === "fa") {
            $fi.show("blind");
        } else if (val === "txt") {
            $txt.show("blind");
        } else if (val === "img") {
            $img.show("blind");
        }
    }
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
    $("#arbtt_bgc, #arbtt_clr, #arbtt_bdclr, .ar-btt-color").minicolors();
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
    const faIcons = [
        "fa fa-angle-up",
        "fa fa-angle-double-up",
        "fa fa-arrow-up",
        "fa fa-arrow-circle-up",
        "fa fa-arrow-circle-o-up",
        "fa fa-chevron-up",
        "fa fa-hand-o-up",
        "fa fa-caret-up",
        "fa fa-long-arrow-up",
        "fa fa-level-up",
        "fa fa-sort-asc",
        "fa fa-upload",
        "fa fa-toggle-up",
        "fa fa-step-backward",
        "fa fa-eject",
        "fa fa-fast-backward",
        "fa fa-angle-double-left",
        "fa fa-hand-rock-o",
        "fa fa-hand-pointer-o",
    ];
    function renderIconList(filter = "") {
        const $container = $(".arbtt-fa-icon-list").empty();
        faIcons.forEach((icon) => {
            if (!filter || icon.includes(filter.toLowerCase())) {
                $container.append(
                    `<i class="fa ${icon}" data-icon="${icon}" title="${icon}"></i>`
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
        const keyword = $(this).val();
        renderIconList(keyword);
    });
    $(document).on("click", ".arbtt-fa-icon-list i", function () {
        const selectedIcon = $(this).data("icon");
        $("#arbtt_fi_picker").val(selectedIcon);
        $(".arbtt-preview-icon").attr(
            "class",
            `fa ${selectedIcon} arbtt-preview-icon`
        );
        $("#arbtt-fa-icon-modal").fadeOut();
        $("body").removeClass("arbtt-modal-open");
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
        const displayIfBoth = $(".shown-if-both");
        const dimension = $(".arbtt_btndm").closest("tr");
        $fiRow.add($txtRow).add($imgRow).add($imgUrlRow).hide();
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
    handleButtonStyle($("#arbtt_btnst").val());
    $("#arbtt_btnst").on("change", function () {
        handleButtonStyle($(this).val());
    });

    function handleProgressBarToggle() {
        const isEnabled = $progressBar.is(":checked") && $("#arbtt_btnst").val() !== 'both';

        $progressBarFields.toggle(isEnabled);
        $progressBarColorRow.toggle(isEnabled);
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
});