jQuery(document).ready(function ($) {
    "use strict";

    // Hide all conditional rows initially
    $(".arbtt_fi, .arbtt_btn_img, #arbtt_btntx, .arbtt_btn_img_url").parents("tr").hide();

    // Handle button style switch
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

    // Handle button position offsets
    function handleButtonPosition(val) {
        const $left = $("#arbtt_btn_offset_left").parents("tr");
        const $right = $("#arbtt_btn_offset_right").parents("tr");
        const $bottom = $("#arbtt_btn_offset_bottom").parents("tr");

        // Always show bottom offset
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

    // Init handlers
    handleButtonStyle($("#arbtt_btnst").val());
    handleButtonPosition($("#arbtt_btnps").val());

    // Init color pickers
    $("#arbtt_bgc, #arbtt_clr, #arbtt_bdclr, .ar-btt-color").minicolors();

    // Events
    $("#arbtt_btnst").on("change", function () {
        handleButtonStyle($(this).val());
    });

    $("#arbtt_btnps").on("change", function () {
        handleButtonPosition($(this).val());
    });

    // Generalized logic for checkboxes toggling the next N rows
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

        toggleRows(); // On load
        $checkbox.on("change", toggleRows); // On toggle
    });
});


jQuery(document).ready(function ($) {
    "use strict";

    // Basic FA v4 list (can be replaced with full list if needed)
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
        "fa fa-toggle-up", // alias for fa-caret-up
        "fa fa-step-backward", // upward metaphor in some contexts
        "fa fa-eject",
        "fa fa-fast-backward", // stylized triangle-like icon
        "fa fa-angle-double-left", // if vertical used as upward
        "fa fa-hand-rock-o", // open upward pointing hand
        "fa fa-hand-pointer-o", // can look upward depending on style
    ];    

    function renderIconList(filter = "") {
        const $container = $(".arbtt-fa-icon-list").empty();
        faIcons.forEach(icon => {
            if (!filter || icon.includes(filter.toLowerCase())) {
                $container.append(`<i class="fa ${icon}" data-icon="${icon}" title="${icon}"></i>`);
            }
        });
    }

    $(".arbtt-open-icon-picker").on("click", function () {
        renderIconList();
        $("#arbtt-fa-icon-modal").fadeIn();
        $('body').addClass('arbtt-modal-open');
    });

    $("#arbtt-fa-search").on("input", function () {
        const keyword = $(this).val();
        renderIconList(keyword);
    });

    $(document).on("click", ".arbtt-fa-icon-list i", function () {
        const selectedIcon = $(this).data("icon");
        $("#arbtt_fi_picker").val(selectedIcon);
        $(".arbtt-preview-icon").attr("class", `fa ${selectedIcon} arbtt-preview-icon`);
        $("#arbtt-fa-icon-modal").fadeOut();
        $('body').removeClass('arbtt-modal-open');
    });

    // ESC to close modal
    $(document).on("keydown", function (e) {
        if (e.key === "Escape") {
            $("#arbtt-fa-icon-modal").fadeOut();
            $('body').removeClass('arbtt-modal-open');
        }
    });

    // Close by click 
    $(document).on('click', '.arbtt-fa-modal-header button', function () {
        $("#arbtt-fa-icon-modal").fadeOut();
        $('body').removeClass('arbtt-modal-open');
    });

    // function handleButtonStyle(val) {
    //     const $fiRow = $("#arbtt_fi_picker").closest("tr");
    //     const $txtRow = $("#arbtt_btntx").closest("tr");
    //     const $imgRow = $(".arbtt_btnimg").closest("tr");
    //     const $imgUrlRow = $(".arbtt_btnimg_url").closest("tr");

    //     // Hide all relevant rows
    //     $fiRow.add($txtRow).add($imgRow).add($imgUrlRow).hide();

    //     // Show based on selected value
    //     switch (val) {
    //         case "fa":
    //             $fiRow.show("blind");
    //             break;
    //         case "txt":
    //             $txtRow.show("blind");
    //             break;
    //         case "img":
    //             $imgRow.show("blind");
    //             break;
    //         case "external":
    //             $imgUrlRow.show("blind");
    //             break;
    //         case "both":
    //             $txtRow.show("blind");
    //             $imgRow.show("blind");
    //             break;
    //     }
    // }

    // // Initial call
    // handleButtonStyle($("#arbtt_btnst").val());

    // // Change listener
    // $("#arbtt_btnst").on("change", function () {
    //     handleButtonStyle($(this).val());
    // });
});
