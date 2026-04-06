jQuery(document).ready(function ($) {

    $(".apfl-notice .apfl-notice-close").on("click", function() {
        $(this).closest(".apfl-notice").remove();
    })

    // slider/carousel toggle listings IDs fields
    $("#apfl_slider_recent, #apfl_crsl_recent").on("click", function () {
        if ($(this).is(":checked")) {
            $(".apfl_slide_tr").hide();
        } else {
            $(".apfl_slide_tr").show();
        }
    });

    // Carousel template change
    $("#apfl_pp_crsl input[name=apfl_carousel_template]").on(
        "change",
        function () {
            const val = $("input[name=apfl_carousel_template]:checked").val();
            if (val === "classic") {
                $(".apfl-crsl-modern-settings").addClass("apfl_hidden");
            } else {
                $(".apfl-crsl-modern-settings").removeClass("apfl_hidden");
            }
        }
    );

    // Carousel autoplay change
    $("#apfl_pp_crsl #apfl_crsl_autoplay").on("change", function () {
        const isAutoplay = $(this).is(":checked");
        if (isAutoplay) {
            $(".apfl-crsl-autoplay-settings").removeClass("apfl_hidden");
        } else {
            $(".apfl-crsl-autoplay-settings").addClass("apfl_hidden");
        }
    });

    // Customizer Template change
    $("#apfl_tmplt_frm").on("change", "input[name=apfl_template]", function () {
        $("#apfl_tmplt_frm").submit();
    });

    $('.apfl_inner-container input[type="checkbox"]').on("change", function () {
        if ($(this).is(":checked")) {
            $(this)
                .closest(".apfl_inner-container")
                .find(".apfl_conditional-options")
                .slideDown();
        } else {
            $(this)
                .closest(".apfl_inner-container")
                .find(".apfl_conditional-options")
                .slideUp();
        }
    });

    // Trigger popup
    $(".apfl_popup_trigger").on("click", function () {
        const container = $(this).closest(".apfl_col-1"); // Adjust scope if needed
        container.find(".apfl_popup_overlay, .apfl_popup_box").fadeIn(200);
    });

    // Close popup on overlay or close button
    $(document).on(
        "click",
        ".apfl_popup_close, .apfl_popup_overlay",
        function () {
            const container = $(this).closest(".apfl_col-1");
            container.find(".apfl_popup_overlay, .apfl_popup_box").fadeOut(200);
        }
    );

    // Display Filters
    $('.apfl_inner-container .apfl_custom input[type="checkbox"]').on(
        "change",
        function () {
            if ($(this).is(":checked")) {
                $(".apfl_admin-fltrs-options").slideDown();
            } else {
                $(".apfl_admin-fltrs-options").slideUp();
            }
        }
    );

    // Show/hide filter options based on checkbox state on page load
    if ($("#apfl_pro_enable_searching").is(":checked")) {
        $(".apfl_admin-fltrs-options").show();
    } else {
        $(".apfl_admin-fltrs-options").hide();
    }

    // color picker
    $(".apfl-listings-color").wpColorPicker();
    $(".apfl-details-color").wpColorPicker();

    $("#apfl-upload-banner-image").on("click", function (e) {
        e.preventDefault();
        var fileInput = $("#apfl_listings_banner_image_upload")[0];
        var template = $(this).attr("apfl-template");

        // Check if a file is selected
        if (fileInput.files.length > 0) {
            var uploaded_file = fileInput.files[0];
            var fd = new FormData();
            fd.append("uploaded_file", uploaded_file);
            fd.append("template", template);
            fd.append("action", "apfl_handle_banner_image_upload");
            fd.append("apfl_nonce", apfl_admin_obj.nonce);
            jQuery
                .ajax({
                    url: apfl_admin_obj.ajaxurl,
                    type: "POST",
                    data: fd,
                    contentType: false,
                    processData: false,
                    dataType: "JSON",
                })
                .done(function (results) {
                    if (results.url) {
                        $("#apfl-banner-image-preview").attr(
                            "src",
                            results.url
                        );
                        $("#apfl-banner-image-preview").css("display", "block");

                        $("#apfl-remove-banner-image").attr(
                            "file-src",
                            results.url
                        );
                        $("#apfl-remove-banner-image").css("display", "block");

                        $("#apfl-upload-msg").html(results.msg);
                        $("#apfl-upload-msg").css("color", "green");
                    } else if (results.error) {
                        $("#apfl-upload-msg").html(results.error);
                        $("#apfl-upload-msg").css("color", "red");
                    }
                })
                .fail(function (data) {
                    console.log(data.responseText);
                    console.log("Request Failed. Status - " + data.statusText);
                });
        } else {
            alert("Please select a file before uploading.");
        }
    });

    $("#apfl-remove-banner-image").on("click", function () {
        file_url = $(this).attr("file-src");
        template = $(this).attr("apfl-template");
        // Check if a file is selected
        if (file_url) {
            var fd = new FormData();
            fd.append("file_url", file_url);
            fd.append("template", template);
            fd.append("action", "apfl_handle_banner_image_remove");
            fd.append("apfl_nonce", apfl_admin_obj.nonce);
            jQuery
                .ajax({
                    url: apfl_admin_obj.ajaxurl,
                    type: "POST",
                    data: fd,
                    contentType: false,
                    processData: false,
                    dataType: "JSON",
                })
                .done(function (results) {
                    if (results.msg) {
                        $("#apfl-banner-image-preview").attr("src", "");
                        $("#apfl-banner-image-preview").css("display", "none");

                        $("#apfl-remove-banner-image").attr("file-src", "");
                        $("#apfl-remove-banner-image").css("display", "none");

                        $("#apfl-upload-msg").html(results.msg);
                        $("#apfl-upload-msg").css("color", "green");
                    } else if (results.error) {
                        $("#apfl-upload-msg").html(results.error);
                        $("#apfl-upload-msg").css("color", "red");
                    }
                })
                .fail(function (data) {
                    console.log(data.responseText);
                    console.log("Request Failed. Status - " + data.statusText);
                });
        }
    });

    $("#apfl_listings_banner_image_upload").change(function () {
        previewImage(this);
    });

    function previewImage(input) {
        var preview = $("#apfl-banner-image-preview");
        var file = input.files[0];
        var reader = new FileReader();

        reader.onload = function (e) {
            preview.attr("src", e.target.result);
            preview.css("display", "block");
        };

        if (file) {
            reader.readAsDataURL(file);
        }
    }

    $('#apfl_copy').click(function() {
    var text = $('#apfl_copy_carousel_sc').text();

    // Create a temporary input to copy text
    var tempInput = $('<input>');
    $('body').append(tempInput);
    tempInput.val(text).select();
    document.execCommand('copy');
    tempInput.remove();

    // Optional: change icon or show message
    $('#apfl_sc_copy_icon').hide();
    $('#apfl_sc_copied_icon').show();
	setTimeout(() => {
      $('#apfl_sc_copy_icon').show();
    $('#apfl_sc_copied_icon').hide();
    }, 1500);
  });
  
});
