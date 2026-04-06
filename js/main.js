jQuery(document).ready(function ($) {
  // Datepicker
  $(".datepicker-field").datepicker({ dateFormat: "yy-mm-dd" });
  $(".datepicker-icon").on("click", function () {
    $(this).closest(".datepicker").find(".datepicker-field").focus();
  });

  // Show state with city in filters
  function apflShowState() {
    const show_state = $(".apfl-city-fltr").attr("data-show-state");

    if (show_state === "show") {
      const cityStateMap = {};

      $(".listing-item .address").each(function () {
        const address = $(this).text().trim();
        const match = address.match(/,\s*([\w\s]+),\s*([A-Z]{2})\s*\d{5}/);

        if (match) {
          const city = match[1].trim();
          const state = match[2].trim();
          cityStateMap[city] = state;
        }
      });

      const hiddenInput = $('input[name="orig_cities"]');
      const updatedOptions = [];

      $(".apfl-city-fltr option").each(function () {
        const city = $(this).text().trim();
        const value = $(this).val();
        const $option = $(this);

        if (cityStateMap[city]) {
          const state = cityStateMap[city];
          const displayText = `${city}, ${state}`;
          $option.text(displayText);
          $option.val(value);
          updatedOptions.push(
            $("<option>").val(value).text(displayText).prop("outerHTML")
          );
        } else {
          updatedOptions.push(
            $("<option>").val(value).text(city).prop("outerHTML")
          );
        }
      });

      hiddenInput.val(updatedOptions.join(""));
    }
  }

  apflShowState();

  $(".apfl-gallery .mySlides img").each(function () {
    let str1 = $(this).attr("data-href");
    let data_id = $(this).attr("data-id");
    if (str1.indexOf("youtube") != -1) {
      $(this)
        .parent(".mySlides")
        .append(
          '<span class="gallery__video-label" data-href="' +
            str1 +
            '" data-id="' +
            data_id +
            '">&#9658;</span>'
        );

      // const videoId = getId(str1);
      // const iframeMarkup = '<iframe class="apfl-yt-frame" width="100%" height="100%" src="//www.youtube.com/embed/' + videoId + '?enablejsapi=1&version=3&playerapiid=ytplayer" frameborder="0" allowfullscreen></iframe>';
      // $(this).parent('.mySlides').append(iframeMarkup);
    }
  });

  // extract YT video ID
  function getId(url) {
    const regExp =
      /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
    const match = url.match(regExp);

    return match && match[2].length === 11 ? match[2] : null;
  }

  // pause YT video on slide change
  $(".apfl-gallery .next, .apfl-gallery .prev, .imgcolumn").on(
    "click",
    function () {
      $(".apfl-yt-frame").each(function () {
        $(this)[0].contentWindow.postMessage(
          '{"event":"command","func":"' + "pauseVideo" + '","args":""}',
          "*"
        );
      });
    }
  );

  // Gallery popup/lightbox
  $(
    ".apfl-gallery .mySlides img, .apfl-gallery .mySlides .gallery__video-label"
  ).on("click", function () {
    // let pp_html = '<div class="apfl_full_pp"><span class="close_apfl_pp">X</span>';
    let pp_html =
      '<div class="apfl_full_pp"><span class="close_apfl_pp">' +
      '<svg class="close-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">' +
      '<path d="M16 16L12 12M12 12L8 8M12 12L16 8M12 12L8 16" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>' +
      "</svg>" +
      "</span>";

    let pp_slide = "";
    const pp_curr_slide_id = $(this).attr("data-id");

    $(".apfl-gallery .mySlides img").each(function () {
      const full_src = $(this).attr("data-href");
      const pp_slide_id = $(this).attr("data-id");
      let current_slide = "";
      if (pp_curr_slide_id == pp_slide_id) {
        current_slide = "current";
      }
      if (full_src.indexOf("youtube") != -1) {
        const videoId = getId(full_src);
        pp_slide =
          '<div id="' +
          pp_slide_id +
          '" class="afpl_pp_slide apfl_vid_container ' +
          current_slide +
          '"><iframe class="apfl-yt-frame" width="560" height="330" src="//www.youtube.com/embed/' +
          videoId +
          '?enablejsapi=1&version=3&playerapiid=ytplayer" frameborder="0" allowfullscreen></iframe></div>';
      } else {
        pp_slide =
          '<div id="' +
          pp_slide_id +
          '" class="afpl_pp_slide ' +
          current_slide +
          '"><img src="' +
          full_src +
          '"></div>';
      }
      pp_html += pp_slide;
    });
    // pp_html += '<span id="pp_prev">&lsaquo;</span><span id="pp_next">&rsaquo;</span></div>';
    // $('body').append(pp_html);
    pp_html += `
	<span id="pp_prev">
		<svg viewBox="0 0 24 24" fill="none"
			xmlns="http://www.w3.org/2000/svg">
			<path d="M15 7L10 12L15 17" stroke="#ffffff" stroke-width="1.5"
				stroke-linecap="round" stroke-linejoin="round"/>
		</svg>
	</span>
	<span id="pp_next">
		<svg viewBox="0 0 24 24" fill="none"
			xmlns="http://www.w3.org/2000/svg">
			<path d="M10 7L15 12L10 17" stroke="#ffffff" stroke-width="1.5"
				stroke-linecap="round" stroke-linejoin="round"/>
		</svg>
	</span>
</div>`;

    $("body").append(pp_html);
  });

  $("body").on("click", ".close_apfl_pp", function () {
    $(".apfl_full_pp").remove();
  });

  $("body").on("click", "#pp_prev", function () {
    pp_slideIndex = $(".afpl_pp_slide.current").attr("id");
    pp_slideIndex = parseInt(pp_slideIndex.replace("apfl_gal_img_", ""));
    pp_showSlides((pp_slideIndex += -1));
  });
  $("body").on("click", "#pp_next", function () {
    pp_slideIndex = $(".afpl_pp_slide.current").attr("id");
    pp_slideIndex = parseInt(pp_slideIndex.replace("apfl_gal_img_", ""));
    pp_showSlides((pp_slideIndex += 1));
  });

  $(document).on("keydown", function (event) {
    if ($(".apfl_full_pp").is(":visible")) {
      switch (event.key) {
        case "ArrowLeft":
          pp_slideIndex = $(".afpl_pp_slide.current").attr("id");
          pp_slideIndex = parseInt(pp_slideIndex.replace("apfl_gal_img_", ""));
          pp_showSlides((pp_slideIndex += -1));
          break;
        case "ArrowRight":
          pp_slideIndex = $(".afpl_pp_slide.current").attr("id");
          pp_slideIndex = parseInt(pp_slideIndex.replace("apfl_gal_img_", ""));
          pp_showSlides((pp_slideIndex += 1));
          break;
      }
    }
  });

  // Gallery popup slider
  let pp_slideIndex = "";
  function pp_showSlides(n) {
    var i;
    var slides = document.getElementsByClassName("afpl_pp_slide");
    var dots = document.getElementsByClassName("demo");
    if (n > slides.length) {
      pp_slideIndex = 1;
    }
    if (n < 1) {
      pp_slideIndex = slides.length;
    }
    for (i = 0; i < slides.length; i++) {
      // slides[i].style.display = "none";
      slides[i].className = slides[i].className.replace(" current", "");
    }
    slides[pp_slideIndex - 1].className += " current";
  }

  // Pagination
  $(document).on("click", ".apfl-pagination a", function () {
    var nextPage = parseInt($(this).attr("apfl-page"));
    var total_pages = parseInt($(".apfl-pagination").attr("apfl-total-pages"));

    $(".apfl-pagination a").removeClass("apfl-current-page");
    $('.apfl-pagination a[apfl-page="' + nextPage + '"]')
      .not(".apfl-arrow")
      .addClass("apfl-current-page");

    // Left arrow
    if (nextPage > 1) {
      $(".apfl-left-arrow")
        .attr("apfl-page", nextPage - 1)
        .removeClass("apfl-no-visibility");
    } else {
      $(".apfl-left-arrow").addClass("apfl-no-visibility");
    }

    // Left double arrow
    if (nextPage > 5) {
      $(".apfl-left-double-arrow").removeClass("apfl-no-visibility");
    } else {
      $(".apfl-left-double-arrow").addClass("apfl-no-visibility");
    }

    // Right arrow
    if (nextPage < total_pages) {
      $(".apfl-right-arrow")
        .attr("apfl-page", nextPage + 1)
        .removeClass("apfl-no-visibility");
    } else {
      $(".apfl-right-arrow").addClass("apfl-no-visibility");
    }

    // Right double arrow
    if (nextPage < total_pages - 1) {
      $(".apfl-right-double-arrow").removeClass("apfl-no-visibility");
    } else {
      $(".apfl-right-double-arrow").addClass("apfl-no-visibility");
    }

    // Update page numbers

    // $('.apfl-pagination a').not('.apfl-arrow').addClass('apfl-hidden');

    for (i = 1; i <= total_pages; i++) {
      if (nextPage <= 5) {
        if (i <= 5) {
          $('.apfl-pagination a[apfl-page="' + i + '"]')
            .not(".apfl-arrow")
            .removeClass("apfl-hidden");
        } else {
          $('.apfl-pagination a[apfl-page="' + i + '"]')
            .not(".apfl-arrow")
            .addClass("apfl-hidden");
        }
      } else {
        $('.apfl-pagination a[apfl-page="' + nextPage + '"]')
          .not(".apfl-arrow")
          .removeClass("apfl-hidden");

        if (i <= nextPage - 5) {
          $('.apfl-pagination a[apfl-page="' + i + '"]')
            .not(".apfl-arrow")
            .addClass("apfl-hidden");
        } else if (i > nextPage) {
          $('.apfl-pagination a[apfl-page="' + i + '"]')
            .not(".apfl-arrow")
            .addClass("apfl-hidden");
        } else {
          $('.apfl-pagination a[apfl-page="' + i + '"]')
            .not(".apfl-arrow")
            .removeClass("apfl-hidden");
        }
      }
    }

    // if(nextPage < 5){
    // for (i = nextPage + 1; i <= total_pages - 5; i--) {
    // $('.apfl-pagination a[apfl-page="' + i + '"]').not('.apfl-arrow').removeClass('apfl-hidden');
    // }
    // }

    // Load listings for the clicked page
    loadListings(nextPage, "listings");
  });

  $(document).on("click", ".apfl-pagination-multiple a", function () {
    var nextPage = $(this).attr("apfl-page");

    // Load listings for the clicked page
    loadListings(nextPage, "multiple-listings");
  });

  function loadListings(page, type) {
    var apfl_per_page = parseInt($(".apfl-pagination").attr("apfl-per-page"));

    var previous_items = parseInt(apfl_per_page * (page - 1));

    $(".all-listings .listing-item").addClass("apfl-hidden");

    $(".all-listings .listing-item")
      .slice(previous_items, previous_items + apfl_per_page)
      .removeClass("apfl-hidden");
  }

  // Share-buttons on single listing
  $(".apfl-share-buttons").on("click", function (e) {
    e.preventDefault();
    const site = $(this).attr("data-site");
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent(document.title + "\n");
    let shareUrl = "";

    switch (site) {
      case "twitter":
        shareUrl = `https://twitter.com/share?url=${url}`;
        window.open(shareUrl, "_blank", "width=600,height=400");
        break;
      case "facebook":
        shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
        window.open(shareUrl, "_blank", "width=600,height=400");
        break;
      case "pinterest":
        shareUrl = `https://pinterest.com/pin/create/button/?url=${url}&description=${title}`;
        window.open(shareUrl, "_blank", "width=600,height=400");
        break;
      case "email":
        shareUrl = `mailto:?subject=${title}&body=${url}`;
        window.open(shareUrl, "_blank", "width=600,height=400");
        break;
      case "copy":
        navigator.clipboard
          .writeText(window.location.href)
          .then(() => {
            alert("Link copied to clipboard!");
          })
          .catch((err) => {
            console.error("Failed to copy text: ", err);
          });
    }
  });
});
