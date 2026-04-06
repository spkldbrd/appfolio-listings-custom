jQuery(document).ready(function ($) {
    /* -------------------- Common Utility -------------------- */
    function fetchData(url) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.open("GET", url, true);

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        resolve(xhr.responseText);
                    } else {
                        reject(
                            new Error("Error fetching data:", xhr.statusText)
                        );
                    }
                }
            };
            xhr.onerror = function () {
                reject(new Error("Request failed"));
            };
            xhr.send();
        });
    }

    function loadScript(src) {
        return new Promise(function (resolve, reject) {
            const script = document.createElement("script");
            script.src = src;
            script.type = "text/javascript";
            script.onload = () => resolve();
            script.onerror = () =>
                reject(new Error(`Failed to load script ${src}`));
            document.head.appendChild(script);
        });
    }

    /* -------------------- Classic Design -------------------- */
    async function loadAssets(data) {
        const { apfl_plugin_url } = data;

        const fontLink = document.createElement("link");
        fontLink.href =
            "https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300italic,regular,italic,700,700italic&subset=latin-ext,greek-ext,cyrillic-ext,greek,vietnamese,latin,cyrillic";
        fontLink.rel = "stylesheet";
        document.head.appendChild(fontLink);

        const styleTag = document.createElement("style");
        styleTag.textContent = `
            /*jssor slider loading skin spin css*/
            .jssorl-009-spin img {
                animation-name: jssorl-009-spin;
                animation-duration: 1.6s;
                animation-iteration-count: infinite;
                animation-timing-function: linear;
            }
            @keyframes jssorl-009-spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
            /*jssor slider bullet skin 057 css*/
            .jssorb057 .i {position:absolute;cursor:pointer;}
            .jssorb057 .i .b {fill:none;stroke:#fff;stroke-width:2000;stroke-miterlimit:10;stroke-opacity:0.4;}
            .jssorb057 .i:hover .b {stroke-opacity:.7;}
            .jssorb057 .iav .b {stroke-opacity: 1;}
            .jssorb057 .i.idn {opacity:.3;}

            /*jssor slider arrow skin 073 css*/
            .jssora073 {display:block;position:absolute;cursor:pointer;}
            .jssora073 .a {fill:#ddd;fill-opacity:.7;stroke:#000;stroke-width:160;stroke-miterlimit:10;stroke-opacity:.7;}
            .jssora073:hover {opacity:.8;}
            .jssora073.jssora073dn {opacity:.4;}
            .jssora073.jssora073ds {opacity:.3;pointer-events:none;}
        `;
        document.head.appendChild(styleTag);

        await loadScript(
            apfl_plugin_url + "slider/js/jssor.slider-28.1.0.min.js"
        );
    }

    async function loadSlides(data, $root) {
        const {
            client_listings_url,
            apfl_plugin_url,
            apfl_crsl_cnt,
            apfl_crsl_recent,
            apfl_crsl_slides,
            lstng_dtl_page,
        } = data;

        let carouselHtml = "";

        carouselHtml += `<div class="crsl_1" style="position:relative;margin:0 auto;top:0px;left:0px;width:1600px;height:560px;overflow:hidden;visibility:hidden;">
					<div data-u="loading" class="jssorl-009-spin" style="position:absolute;top:0px;left:0px;width:100%;height:100%;text-align:center;background-color:rgba(0,0,0,0.7);">
						<img style="margin-top:-19px;position:relative;top:50%;width:38px;height:38px;" src="${apfl_plugin_url}slider/img/spin.svg" />
					</div>`;
        carouselHtml += `<div class="actual_slides" data-u="slides" style="cursor:default;position:relative;top:0px;left:0px;width:1600px;height:560px;overflow:hidden;">`;

        const appfolioHtml = await fetchData(client_listings_url + "/listings");
        const parser = new DOMParser();
        const tempDoc = parser.parseFromString(appfolioHtml, "text/html");
        let listingItems = tempDoc.querySelectorAll(
            "#result_container .listing-item"
        );
        listingItems = Array.from(listingItems);

        let displayListings = null;
        if (apfl_crsl_recent == 1) {
            displayListings = listingItems.slice(0, apfl_crsl_cnt);
        } else {
            displayListings = listingItems.filter((item) => {
                const detailsBtn = item.querySelector("a.js-link-to-detail");
                if (detailsBtn && detailsBtn.href) {
                    const idMatch = detailsBtn.href.match(
                        /\/listings\/detail\/([^\/?#]+)/
                    );
                    if (idMatch) {
                        return apfl_crsl_slides.includes(idMatch[1]);
                    }
                }
                return false;
            });
        }

        if (displayListings.length > 0) {
            displayListings.forEach((listing, index) => {
                let data = [];
                data["img"] = "";
                data["baths"] = "";
                data["beds"] = "";
                data["ttl"] = "";
                data["Square Feet"] = "";
                data["Available"] = "";
                data["RENT"] = "";
                data["adrs"] = "";

                const listingItemBody = listing.querySelector(
                    ".listing-item__body"
                );
                const listingItemAction = listing.querySelector(
                    ".listing-item__actions"
                );
                const listingImgObj = listing.querySelector(
                    "img.listing-item__image"
                );

                if (listingImgObj) {
                    data["img"] = listingImgObj.getAttribute("data-original");
                }

                if (listingItemBody) {
                    const detailItems =
                        listingItemBody.querySelectorAll(".detail-box__item");
                    detailItems.forEach((dbItem) => {
                        const labelEl =
                            dbItem.querySelector(".detail-box__label");
                        const valueEl =
                            dbItem.querySelector(".detail-box__value");
                        if (!labelEl || !valueEl) return;

                        const label = labelEl.innerText.trim();
                        const val = valueEl.innerText.trim();

                        if (label === "Bed / Bath") {
                            if (val.includes("bd")) {
                                const beds = val.split(" bd / ");
                                data["beds"] = beds[0] + " Beds";
                                if (beds[1] && beds[1].includes("ba")) {
                                    const baths = beds[1].split(" ba");
                                    data["baths"] = baths[0] + " Baths";
                                }
                            } else if (val.includes("Studio")) {
                                data["beds"] = "Studio";
                                const baths = val
                                    .split("Studio / ")[1]
                                    ?.split(" ba");
                                if (baths && baths[0]) {
                                    data["baths"] = baths[0] + " Baths";
                                }
                            }
                        } else {
                            data[label] = val;
                        }
                    });

                    const titleEl = listingItemBody.querySelector(
                        ".js-listing-title a"
                    );
                    if (titleEl) data["ttl"] = titleEl.innerText.trim();

                    const addressEl = listingItemBody.querySelector(
                        ".js-listing-address"
                    );
                    if (addressEl) data["adrs"] = addressEl.innerText.trim();

                    const descriptionEl = listingItemBody.querySelector(
                        ".js-listing-description"
                    );
                    if (descriptionEl)
                        data["desc"] = descriptionEl.innerText.trim();
                }

                let listing_ID = "";
                if (listingItemAction) {
                    const detailsLinkElement =
                        listingItemAction.querySelector(".js-link-to-detail");
                    if (detailsLinkElement) {
                        const detailsHref =
                            detailsLinkElement.getAttribute("href");
                        listing_ID = detailsHref.split("/").pop();
                    }
                }

                let areaHtml = "";
                if (data["Square Feet"]) {
                    areaHtml = `<span class="plc_area"><img src="${apfl_plugin_url}images/size.png">${data["Square Feet"]} Sq. Ft.</span>`;
                }

                carouselHtml += `<div class="sngl-lstng-crsl apfl-crsl-classic-slide">
                    <a href="${lstng_dtl_page}?lid=${listing_ID}">
                        <img data-u="image" src="${data["img"]}" />
                        <div class="slide-txt">
                            <p class="lstng_price">${data["RENT"]}</p>
                            <p class="plc_avl">${data["Available"]}</p>
                            <p class="mini-dtl">${areaHtml}<span class="bed_std"><img src="${apfl_plugin_url}images/bed.png">${data["beds"]}</span><span class="bath"><img src="${apfl_plugin_url}images/bath.png">${data["baths"]}</span></p>
                            <p class="lstng-adrs">${data["adrs"]}</p>
                        </div>
                    </a></div>`;
            });
        }

        carouselHtml += "</div>";

        carouselHtml += `<!-- Arrow Navigator -->
					<div data-u="arrowleft" class="jssora073" style="width:50px;height:50px;top:0px;left:30px;" data-autocenter="2" data-scale="0.75" data-scale-left="0.75">
						<svg viewbox="0 0 16000 16000" style="position:absolute;top:0;left:0;width:100%;height:100%;">
							<path class="a" d="M4037.7,8357.3l5891.8,5891.8c100.6,100.6,219.7,150.9,357.3,150.9s256.7-50.3,357.3-150.9 l1318.1-1318.1c100.6-100.6,150.9-219.7,150.9-357.3c0-137.6-50.3-256.7-150.9-357.3L7745.9,8000l4216.4-4216.4 c100.6-100.6,150.9-219.7,150.9-357.3c0-137.6-50.3-256.7-150.9-357.3l-1318.1-1318.1c-100.6-100.6-219.7-150.9-357.3-150.9 s-256.7,50.3-357.3,150.9L4037.7,7642.7c-100.6,100.6-150.9,219.7-150.9,357.3C3886.8,8137.6,3937.1,8256.7,4037.7,8357.3 L4037.7,8357.3z"></path>
						</svg>
					</div>
					<div data-u="arrowright" class="jssora073" style="width:50px;height:50px;top:0px;right:30px;" data-autocenter="2" data-scale="0.75" data-scale-right="0.75">
						<svg viewbox="0 0 16000 16000" style="position:absolute;top:0;left:0;width:100%;height:100%;">
							<path class="a" d="M11962.3,8357.3l-5891.8,5891.8c-100.6,100.6-219.7,150.9-357.3,150.9s-256.7-50.3-357.3-150.9 L4037.7,12931c-100.6-100.6-150.9-219.7-150.9-357.3c0-137.6,50.3-256.7,150.9-357.3L8254.1,8000L4037.7,3783.6 c-100.6-100.6-150.9-219.7-150.9-357.3c0-137.6,50.3-256.7,150.9-357.3l1318.1-1318.1c100.6-100.6,219.7-150.9,357.3-150.9 s256.7,50.3,357.3,150.9l5891.8,5891.8c100.6,100.6,150.9,219.7,150.9,357.3C12113.2,8137.6,12062.9,8256.7,11962.3,8357.3 L11962.3,8357.3z"></path>
						</svg>
					</div>
				  </div>`;

        $root.html(carouselHtml);
    }

    function crsl_init() {
        if (jQuery(window).width() < 768) {
            jQuery(".crsl_1, .crsl_1 .actual_slides").css("width", "700px");
        }
        if (jQuery(window).width() < 500) {
            jQuery(".crsl_1, .crsl_1 .actual_slides").css("width", "450px");
            var crsl_options = {
                $SlideWidth: 420,
                $AutoPlay: 1,
                $AutoPlaySteps: 1,
                $SlideDuration: 160,
                $SlideSpacing: 10,
                $Loop: 1,
                $ArrowNavigatorOptions: {
                    $Class: $JssorArrowNavigator$,
                    $Steps: 1,
                },
                $BulletNavigatorOptions: {
                    $Class: $JssorBulletNavigator$,
                    $SpacingX: 16,
                    $SpacingY: 16,
                },
            };
        } else {
            var crsl_options = {
                $SlideWidth: 500,
                $AutoPlay: 1,
                $AutoPlaySteps: 1,
                $SlideDuration: 160,
                $SlideSpacing: 10,
                $Loop: 1,
                $ArrowNavigatorOptions: {
                    $Class: $JssorArrowNavigator$,
                    $Steps: 1,
                },
                $BulletNavigatorOptions: {
                    $Class: $JssorBulletNavigator$,
                    $SpacingX: 16,
                    $SpacingY: 16,
                },
            };
        }

        var crsl_1_elements = document.querySelectorAll(".crsl_1");

        crsl_1_elements.forEach(function (element) {
            var crsl_1_slider = new $JssorSlider$(element, crsl_options);

            var MAX_WIDTH = 1366;
            function ScaleSlider() {
                var containerElement = crsl_1_slider.$Elmt.parentNode;
                var containerWidth = containerElement.clientWidth;
                if (containerWidth) {
                    var expectedWidth = Math.min(
                        MAX_WIDTH || containerWidth,
                        containerWidth
                    );
                    crsl_1_slider.$ScaleWidth(expectedWidth);
                } else {
                    window.setTimeout(ScaleSlider, 30);
                }
            }
            ScaleSlider();

            // Call ScaleSlider function on load, resize, and orientation change
            $Jssor$.$AddEvent(window, "load", ScaleSlider);
            $Jssor$.$AddEvent(window, "resize", ScaleSlider);
            $Jssor$.$AddEvent(window, "orientationchange", ScaleSlider);
        });
        /*#endregion responsive code end*/
    }

    /* -------------------- Modern Design -------------------- */
    async function loadSlidesModern(data, $root) {
        const {
            client_listings_url,
            apfl_crsl_cnt,
            apfl_crsl_recent,
            apfl_crsl_slides,
            lstng_dtl_page,
            autoplay,
            nav,
            interval,
            scroll_dir,
        } = data;

        const appfolioHtml = await fetchData(client_listings_url + "/listings");
        const parser = new DOMParser();
        const tempDoc = parser.parseFromString(appfolioHtml, "text/html");
        let listingItems = tempDoc.querySelectorAll(
            "#result_container .listing-item"
        );
        listingItems = Array.from(listingItems);

        let displayListings = null;
        if (apfl_crsl_recent == 1) {
            displayListings = listingItems.slice(0, apfl_crsl_cnt);
        } else {
            displayListings = listingItems.filter((item) => {
                const detailsBtn = item.querySelector("a.js-link-to-detail");
                if (detailsBtn && detailsBtn.href) {
                    const idMatch = detailsBtn.href.match(
                        /\/listings\/detail\/([^\/?#]+)/
                    );
                    if (idMatch) {
                        return apfl_crsl_slides.includes(idMatch[1]);
                    }
                }
                return false;
            });
        }

        let carouselHtml = "";

        carouselHtml += `
        <div class="apfl-cm-carousel-outer">
            ${
                nav === "yes"
                    ? `
                <div class="apfl-cm-nav-wrapper">
                    <button
                        class="apfl-cm-carousel-btn"
                        id="apfl-cm-carousel-left"
                    >
                        <svg
                            width="28"
                            height="28"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="#2e5aac"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <polyline points="15 18 9 12 15 6" />
                        </svg>
                    </button>
                    <button
                        class="apfl-cm-carousel-btn"
                        id="apfl-cm-carousel-right"
                    >
                        <svg
                            width="28"
                            height="28"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="#2e5aac"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <polyline points="9 6 15 12 9 18" />
                        </svg>
                    </button>
                </div>
            `
                    : ""
            }
            <div
                class="apfl-cm-carousel-track"
                id="apfl-cm-carousel-track"
            >
        `;

        if (displayListings.length > 0) {
            displayListings.forEach((listing, index) => {
                let data = [];
                data["img"] = "";
                data["baths"] = "";
                data["beds"] = "";
                data["ttl"] = "";
                data["Square Feet"] = "";
                data["Available"] = "";
                data["RENT"] = "";
                data["adrs"] = "";

                const listingItemBody = listing.querySelector(
                    ".listing-item__body"
                );
                const listingItemAction = listing.querySelector(
                    ".listing-item__actions"
                );
                const listingImgObj = listing.querySelector(
                    "img.listing-item__image"
                );

                if (listingImgObj) {
                    data["img"] = listingImgObj.getAttribute("data-original");
                }

                if (listingItemBody) {
                    const detailItems =
                        listingItemBody.querySelectorAll(".detail-box__item");
                    detailItems.forEach((dbItem) => {
                        const labelEl =
                            dbItem.querySelector(".detail-box__label");
                        const valueEl =
                            dbItem.querySelector(".detail-box__value");
                        if (!labelEl || !valueEl) return;

                        const label = labelEl.innerText.trim();
                        const val = valueEl.innerText.trim();

                        if (label === "Bed / Bath") {
                            if (val.includes("bd")) {
                                const beds = val.split(" bd / ");
                                data["beds"] = beds[0] + " Beds";
                                if (beds[1] && beds[1].includes("ba")) {
                                    const baths = beds[1].split(" ba");
                                    data["baths"] = baths[0] + " Baths";
                                }
                            } else if (val.includes("Studio")) {
                                data["beds"] = "Studio";
                                const baths = val
                                    .split("Studio / ")[1]
                                    ?.split(" ba");
                                if (baths && baths[0]) {
                                    data["baths"] = baths[0] + " Baths";
                                }
                            }
                        } else {
                            data[label] = val;
                        }
                    });

                    const titleEl = listingItemBody.querySelector(
                        ".js-listing-title a"
                    );
                    if (titleEl) data["ttl"] = titleEl.innerText.trim();

                    const addressEl = listingItemBody.querySelector(
                        ".js-listing-address"
                    );
                    if (addressEl) data["adrs"] = addressEl.innerText.trim();

                    const descriptionEl = listingItemBody.querySelector(
                        ".js-listing-description"
                    );
                    if (descriptionEl)
                        data["desc"] = descriptionEl.innerText.trim();
                }

                let listing_ID = "";
                if (listingItemAction) {
                    const detailsLinkElement =
                        listingItemAction.querySelector(".js-link-to-detail");
                    if (detailsLinkElement) {
                        const detailsHref =
                            detailsLinkElement.getAttribute("href");
                        listing_ID = detailsHref.split("/").pop();
                    }
                }

                carouselHtml += `
                    <div class="apfl-cm-item">
                    <a href="${lstng_dtl_page}?lid=${listing_ID}" target="_blank">
                        <div class="apfl-cm-card">
                            <div class="apfl-cm-card-main-wrapper">
                                <div class="apfl-cm-card-image-wrapper">
                                    <img
                                        src="${data["img"]}"
                                        alt="${data["ttl"]}"
                                        class="apfl-cm-card-image"
                                    />
                                    <span
                                        class="apfl-cm-badge apfl-cm-badge-price"
                                        >${data["RENT"]}</span
                                    >
                                    <span
                                        class="apfl-cm-badge apfl-cm-badge-date"
                                        >${data["Available"]}</span
                                    >
                                </div>
                                <div class="apfl-cm-card-content">
                                    <div class="apfl-cm-card-main">
                                        <div class="apfl-cm-card-title">
                                            ${data["ttl"]}
                                        </div>
                                        <div
                                            class="apfl-cm-card-address"
                                        >
                                            ${data["adrs"]}
                                        </div>
                                    </div>
                                    <div class="apfl-cm-card-features">
                                        <span
                                            class="apfl-cm-feature-icon apfl-cm-feature-bed-carousel"
                                            >
                                            <svg
                                                width="18"
                                                height="18"
                                                viewBox="0 0 512 512"
                                                fill="#2e5aac"
                                                xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <g>
                                                    <path
                                                        d="M439.616,151.846V81.819c-0.015-25.396-20.576-45.972-45.979-45.986H122.97h-4.603c-25.404,0.014-45.968,20.59-45.978,45.978v70.034L0,333.171V443.72c0.018,17.918,14.524,32.424,32.427,32.446h0.719h445.726h0.7c17.904-0.022,32.409-14.528,32.428-32.431V333.171L439.616,151.846z M109.943,81.827c0.008-4.648,3.791-8.435,8.428-8.435h275.258c4.64,0,8.424,3.787,8.431,8.428v69.997h-22.682v-23.742c-0.004-20.171-16.422-36.593-36.597-36.6h-44.227c-20.172,0.008-36.586,16.429-36.593,36.6v23.742h-11.923v-23.742c-0.008-20.171-16.422-36.593-36.593-36.6h-44.227c-20.176,0.008-36.593,16.429-36.596,36.6v23.742h-22.679V81.827z M357.92,128.074v23.742H283.42v-23.734c0.015-8.338,6.807-15.134,15.138-15.142h44.22C351.112,112.948,357.904,119.744,357.92,128.074z M228.58,128.074v23.742H154.08v-23.734c0.015-8.338,6.808-15.134,15.142-15.142h44.219C221.772,112.948,228.564,119.744,228.58,128.074z M104.266,173.283h303.472l5.876,14.715H98.39L104.266,173.283z M89.824,209.464h332.355l46.866,117.398H42.954L89.824,209.464z M474.446,438.608H37.555v-90.281h436.891V438.608z"
                                                    />
                                                </g>
                                            </svg
                                            >
                                            <span>${data["beds"]}</span>
                                        </span>
                                        <span
                                            class="apfl-cm-feature-icon apfl-cm-feature-bath-carousel"
                                        >
                                            <svg
                                                width="18"
                                                height="18"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="#2e5aac"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            >
                                                <rect
                                                    x="2"
                                                    y="13"
                                                    width="20"
                                                    height="6"
                                                    rx="3"
                                                />
                                                <path
                                                    d="M4 13V7a4 4 0 0 1 8 0v6"
                                                />
                                                <path d="M16 7h.01" />
                                                <path d="M16 10h.01" />
                                                <path d="M16 13h.01" />
                                                <path d="M8 21v1" />
                                                <path d="M16 21v1" />
                                            </svg>
                                            ${data["baths"]}
                                        </span>
                                        <span class="apfl-cm-feature-icon"
                                            ><svg
                                                width="18"
                                                height="18"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="#2e5aac"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            >
                                                <rect
                                                    x="3"
                                                    y="3"
                                                    width="18"
                                                    height="18"
                                                    rx="2"
                                                />
                                                <path d="M3 9h18M9 21V9" />
                                            </svg>
                                            ${data["Square Feet"]} Sq. Ft.</span
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    </div>
                `;
            });
        }

        carouselHtml += `
            </div>
        </div>
        `;

        $root.html(carouselHtml);

        const getVis = () => (window.innerWidth <= 700 ? 1 : (window.innerWidth <= 900 ? 2 : 3));
        const track = document.getElementById("apfl-cm-carousel-track");
        const leftBtn = document.getElementById("apfl-cm-carousel-left");
        const rightBtn = document.getElementById("apfl-cm-carousel-right");
        if (track) {
            new Carousel({
                track,
                leftBtn,
                rightBtn,
                visible: getVis(),
                loop: true,
                gap: 0,
                autoplay: autoplay === "yes",
                direction: scroll_dir,
                interval: parseInt(interval),
            });
        }
    }

    /* -------------------- Load Carousel -------------------- */
    async function loadCarousel() {
        var $root = jQuery(".apfl-carousel-root").first();
        if (!$root.length) {
            $root = jQuery(".apfl-listings-crsl").first();
        }
        if (!$root.length) {
            return;
        }

        var fd = new FormData();
        fd.append("action", "apfl_get_carousel_options");
        fd.append("apfl_nonce", apfl_carousel_obj.nonce);
        jQuery
            .ajax({
                url: apfl_carousel_obj.ajaxurl,
                type: "POST",
                data: fd,
                contentType: false,
                processData: false,
                dataType: "JSON",
            })
            .done(async function (results) {
                if (results.error) {
                    console.log(results.error);
                } else {
                    var oc = $root.data("apflCrslCount");
                    if (oc) {
                        results.apfl_crsl_cnt = parseInt(oc, 10);
                    }
                    var tpl = $root.data("apflCrslTemplate");
                    if (tpl === "modern" || tpl === "classic") {
                        results.template = tpl;
                    }
                    if (results.template === "classic") {
                        await loadAssets(results);
                        await loadSlides(results, $root);
                        crsl_init();
                    } else {
                        await loadSlidesModern(results, $root);
                    }
                }
            })
            .fail(function (data) {
                console.log(data.responseText);
                console.log("Request Failed. Status - " + data.statusText);
            });
    }
    loadCarousel();
});
