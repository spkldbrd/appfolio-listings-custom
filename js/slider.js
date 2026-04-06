jQuery(document).ready(function($) {

    function fetchData(url) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', url, true);

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        resolve(xhr.responseText);
                    } else {
                        reject(new Error('Error fetching data:', xhr.statusText));
                    }
                }
            };
            xhr.onerror = function () {
                reject(new Error('Request failed'));
            };
            xhr.send();
        });
    }

    function loadScript(src) {
        return new Promise(function(resolve, reject) {
            const script = document.createElement('script');
            script.src = src;
            script.type = 'text/javascript';
            script.onload = () => resolve();
            script.onerror = () => reject(new Error(`Failed to load script ${src}`));
            document.head.appendChild(script);
        });
    }

    function jssor_1_slider_init () {
        var jssor_1_SlideoTransitions = [
            [{b:-1,d:1,ls:0.5},{b:0,d:1000,y:5,e:{y:6}}],
            [{b:-1,d:1,ls:0.5},{b:200,d:1000,y:25,e:{y:6}}],
            [{b:-1,d:1,ls:0.5},{b:400,d:1000,y:45,e:{y:6}}],
            [{b:-1,d:1,ls:0.5},{b:600,d:1000,y:65,e:{y:6}}],
            [{b:-1,d:1,ls:0.5},{b:800,d:1000,y:85,e:{y:6}}],
            [{b:-1,d:1,ls:0.5},{b:500,d:1000,y:195,e:{y:6}}],
            [{b:0,d:2000,y:30,e:{y:3}}],
            [{b:-1,d:1,rY:-15,tZ:100},{b:0,d:1500,y:30,o:1,e:{y:3}}],
            [{b:-1,d:1,rY:-15,tZ:-100},{b:0,d:1500,y:100,o:0.8,e:{y:3}}],
            [{b:500,d:1500,o:1}],
            [{b:0,d:1000,y:380,e:{y:6}}],
            [{b:300,d:1000,x:80,e:{x:6}}],
            [{b:300,d:1000,x:330,e:{x:6}}],
            [{b:-1,d:1,r:-110,sX:5,sY:5},{b:0,d:2000,o:1,r:-20,sX:1,sY:1,e:{o:6,r:6,sX:6,sY:6}}],
            [{b:0,d:600,x:150,o:0.5,e:{x:6}}],
            [{b:0,d:600,x:1140,o:0.6,e:{x:6}}],
            [{b:-1,d:1,sX:5,sY:5},{b:600,d:600,o:1,sX:1,sY:1,e:{sX:3,sY:3}}]
        ];

        if (jQuery(window).width() < 768) {
            jQuery("#jssor_1, #jssor_1 .act_slides").css("width", jQuery(window).width() + "px");
        }
        if (jQuery(window).width() < 500) {
            jQuery("#jssor_1, #jssor_1 .act_slides").css("width", "500px");
        }

        const jssor_1_options = {
            $AutoPlay: 1,
            $LazyLoading: 1,
            $CaptionSliderOptions: {
                $Class: $JssorCaptionSlideo$,
                $Transitions: jssor_1_SlideoTransitions
            },
            $ArrowNavigatorOptions: {
                $Class: $JssorArrowNavigator$
            },
            $BulletNavigatorOptions: {
                $Class: $JssorBulletNavigator$,
                $SpacingX: 20,
                $SpacingY: 20
            }
        };

        const jssor_1_slider = new $JssorSlider$("jssor_1", jssor_1_options);

        // Responsive code
        const MAX_WIDTH = 1600;
        function ScaleSlider() {
            const containerWidth = jssor_1_slider.$Elmt.parentNode.clientWidth;
            if (containerWidth) {
                const expectedWidth = Math.min(MAX_WIDTH, containerWidth);
                jssor_1_slider.$ScaleWidth(expectedWidth);
            } else {
                window.setTimeout(ScaleSlider, 30);
            }
        }

        ScaleSlider();
        $Jssor$.$AddEvent(window, "load", ScaleSlider);
        $Jssor$.$AddEvent(window, "resize", ScaleSlider);
        $Jssor$.$AddEvent(window, "orientationchange", ScaleSlider);
    };

    async function loadAssets(data) {

        const {
            apfl_plugin_url,
        } = data;
        
        const fontLink = document.createElement("link");
        fontLink.href = "https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300italic,regular,italic,700,700italic&subset=latin-ext,greek-ext,cyrillic-ext,greek,vietnamese,latin,cyrillic";
        fontLink.rel = "stylesheet";
        document.head.appendChild(fontLink);

        const styleTag = document.createElement("style");
        styleTag.textContent = `
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
            .jssorb132 {position:absolute;}
            .jssorb132 .i {position:absolute;cursor:pointer;}
            .jssorb132 .i .b {fill:#fff;fill-opacity:0.8;stroke:#000;stroke-width:1600;stroke-miterlimit:10;stroke-opacity:0.7;}
            .jssorb132 .i:hover .b {fill:#000;fill-opacity:.7;stroke:#fff;stroke-width:2000;stroke-opacity:0.8;}
            .jssorb132 .iav .b {fill:#000;stroke:#fff;stroke-width:2400;fill-opacity:0.8;stroke-opacity:1;}
            .jssorb132 .i.idn {opacity:0.3;}
            .jssora051 {display:block;position:absolute;cursor:pointer;}
            .jssora051 .a {fill:none;stroke:#fff;stroke-width:360;stroke-miterlimit:10;}
            .jssora051:hover {opacity:.8;}
            .jssora051.jssora051dn {opacity:.5;}
            .jssora051.jssora051ds {opacity:.3;pointer-events:none;}
        `;
        document.head.appendChild(styleTag);

        const svgDefs = `
            <svg viewbox="0 0 0 0" width="0" height="0" style="display:block;position:relative;left:0px;top:0px;">
                <defs>
                    <filter id="jssor_1_flt_1" x="-50%" y="-50%" width="200%" height="200%">
                        <feGaussianBlur stdDeviation="4"></feGaussianBlur>
                    </filter>
                    <radialGradient id="jssor_1_grd_2">
                        <stop offset="0" stop-color="#fff"></stop>
                        <stop offset="1" stop-color="#000"></stop>
                    </radialGradient>
                    <mask id="jssor_1_msk_3">
                        <path fill="url(#jssor_1_grd_2)" d="M600,0L600,400L0,400L0,0Z" x="0" y="0" style="position:absolute;overflow:visible;"></path>
                    </mask>
                </defs>
            </svg>`;
        document.body.insertAdjacentHTML("beforeend", svgDefs);

        await loadScript(apfl_plugin_url + "slider/js/jssor.slider-28.1.0.min.js");

    }

    async function loadSlides(data) {

        const {
            client_listings_url,
            apfl_plugin_url,
            apfl_slider_cnt,
            apfl_slider_recent,
            apfl_slides,
            lstng_dtl_page,
            apfl_custom_apply_lnk
        } = data;

        let sliderHtml = '';
        
        sliderHtml += '<div id="jssor_1" style="position:relative;margin:0 auto;top:0px;left:0px;width:1600px;height:560px;overflow:hidden;visibility:hidden;">';

        sliderHtml += `<div data-u="loading" class="jssorl-009-spin" style="position:absolute;top:0px;left:0px;width:100%;height:100%;text-align:center;background-color:rgba(0,0,0,0.7);">
        <img style="margin-top:-19px;position:relative;top:50%;width:38px;height:38px;" src="${apfl_plugin_url}slider/img/spin.svg" />
        </div>`;

        sliderHtml += '<div class="act_slides" data-u="slides" style="cursor:default;position:relative;top:0px;left:0px;width:1600px;height:560px;overflow:hidden;">';

        const appfolioHtml = await fetchData(client_listings_url + '/listings');
        const parser = new DOMParser();
        const tempDoc = parser.parseFromString(appfolioHtml, 'text/html');
        let listingItems = tempDoc.querySelectorAll('#result_container .listing-item');
        listingItems = Array.from(listingItems);

        let displayListings = null;
        if(apfl_slider_recent == 1) {
            displayListings = listingItems.slice(0, apfl_slider_cnt);
        } else {
            displayListings = listingItems.filter(item => {
                const detailsBtn = item.querySelector('a.js-link-to-detail');
                if (detailsBtn && detailsBtn.href) {
                    const idMatch = detailsBtn.href.match(/\/listings\/detail\/([^\/?#]+)/);
                    if (idMatch) {
                        return apfl_slides.includes(idMatch[1]);
                    }
                }
                return false;
            });
        }

        if(displayListings.length > 0) {
            displayListings.forEach((listing, index) => {
                
                let data = [];
                data['img'] = '';
                data['baths'] = '';
                data['beds'] = '';
                data['ttl'] = '';
                data['Square Feet'] = '';
                data['Available'] = '';
                data['RENT'] = '';
                data['adrs'] = '';

                const listingItemBody = listing.querySelector('.listing-item__body');
                const listingItemAction = listing.querySelector('.listing-item__actions');
                const listingImgObj = listing.querySelector('img.listing-item__image');

                if (listingImgObj) {
                    data['img'] = listingImgObj.getAttribute('data-original');
                }

                if (listingItemBody) {
                    const detailItems = listingItemBody.querySelectorAll('.detail-box__item');
                    detailItems.forEach(dbItem => {
                        const labelEl = dbItem.querySelector('.detail-box__label');
                        const valueEl = dbItem.querySelector('.detail-box__value');
                        if (!labelEl || !valueEl) return;

                        const label = labelEl.innerText.trim();
                        const val = valueEl.innerText.trim();

                        if (label === 'Bed / Bath') {
                            if (val.includes('bd')) {
                                const beds = val.split(' bd / ');
                                data['beds'] = beds[0] + ' Beds';
                                if (beds[1] && beds[1].includes('ba')) {
                                    const baths = beds[1].split(' ba');
                                    data['baths'] = baths[0] + ' Baths';
                                }
                            } else if (val.includes('Studio')) {
                                data['beds'] = 'Studio';
                                const baths = val.split('Studio / ')[1]?.split(' ba');
                                if (baths && baths[0]) {
                                    data['baths'] = baths[0] + ' Baths';
                                }
                            }
                        } else {
                            data[label] = val;
                        }
                    });

                    const titleEl = listingItemBody.querySelector('.js-listing-title a');
                    if (titleEl) data['ttl'] = titleEl.innerText.trim();

                    const addressEl = listingItemBody.querySelector('.js-listing-address');
                    if (addressEl) data['adrs'] = addressEl.innerText.trim();

                    const descriptionEl = listingItemBody.querySelector('.js-listing-description');
                    if (descriptionEl) data['desc'] = descriptionEl.innerText.trim();

                }

                let listing_ID = '';
                let applyLink = '';
                if (listingItemAction) {
                    const detailsLinkElement = listingItemAction.querySelector('.js-link-to-detail');
                    if (detailsLinkElement) {
                        const detailsHref = detailsLinkElement.getAttribute('href');
                        listing_ID = detailsHref.split('/').pop(); 
                    }

                    const applyLinkElement = listingItemAction.querySelector('.js-listing-apply');
                    if (applyLinkElement) {
                        let applyHref = applyLinkElement.getAttribute('href');
                        if (applyHref && !applyHref.startsWith('http')) {
                            applyHref = client_listings_url + applyHref;
                        }
                        applyLink = applyHref;
                    }
                }

                let detailBtn = '';
                if(lstng_dtl_page && listing_ID){
                    detailBtn = `<a class="moreBtn" target="_blank" href="${lstng_dtl_page}?lid=${listing_ID}">More Details</a>`;
                }

                if(apfl_custom_apply_lnk) {
                    applyLink = apfl_custom_apply_lnk;
                }
                let applyBtn = `<a class="applyBtn" target="_blank" href="${applyLink}">Apply Now</a>`;

                let areaHtml = '';
                if(data['Square Feet']){
                    areaHtml = `<span class="plc_area"><img src="${apfl_plugin_url}images/size.png">${data['Square Feet']} Sq. Ft.</span>`;
                } 

                sliderHtml += `
                    <div class="sngl-lstng-slide" style="background-color:#5bafcb;">
                        <img data-u="image" class="slide-img" style="opacity:0.8;" data-src="${data['img']}" />
                        <div class="slide-txt">
                    `;

                if (data['RENT']) {
                    sliderHtml += `<p class="lstng_price">${data['RENT']}</p>`;
                }

                if (data['Available']) {
                    sliderHtml += `<p class="plc_avl">Available ${data['Available']}</p>`;
                }

                sliderHtml += `
                    <div class="slide-lstng-content">
                        <h4 class="ttl">${data['ttl']}</h4>
                        <p class="mini-dtl">
                            ${areaHtml}
                            <span class="bed_std"><img src="${apfl_plugin_url}images/bed.png">${data['beds']}</span>
                            <span class="bath"><img src="${apfl_plugin_url}images/bath.png">${data['baths']}</span>
                        </p>
                        <p class="lstng-adrs">${data['adrs']}</p>
                        <p class="apply_sec">${detailBtn}${applyBtn}</p>
                    </div>
                </div>
                </div>`;

            });
        }

        sliderHtml += '</div>';

        sliderHtml += `<div data-u="navigator" class="jssorb132" style="position:absolute;bottom:24px;right:16px;" data-autocenter="1" data-scale="0.5" data-scale-bottom="0.75">
                <div data-u="prototype" class="i" style="width:12px;height:12px;">
                    <svg viewbox="0 0 16000 16000" style="position:absolute;top:0;left:0;width:100%;height:100%;">
                        <circle class="b" cx="8000" cy="8000" r="5800"></circle>
                    </svg>
                </div>
            </div>
            <!-- Arrow Navigator -->
            <div data-u="arrowleft" class="jssora051" style="width:55px;height:55px;top:0px;left:25px;" data-autocenter="2" data-scale="0.75" data-scale-left="0.75">
                <svg viewbox="0 0 16000 16000" style="position:absolute;top:0;left:0;width:100%;height:100%;">
                    <polyline class="a" points="11040,1920 4960,8000 11040,14080 "></polyline>
                </svg>
            </div>
            <div data-u="arrowright" class="jssora051" style="width:55px;height:55px;top:0px;right:25px;" data-autocenter="2" data-scale="0.75" data-scale-right="0.75">
                <svg viewbox="0 0 16000 16000" style="position:absolute;top:0;left:0;width:100%;height:100%;">
                    <polyline class="a" points="4960,1920 11040,8000 4960,14080 "></polyline>
                </svg>
            </div>
        </div>`;

        $('.apfl-listings-slider').html(sliderHtml);

    }

    async function loadSlider() {
        var fd = new FormData();
        fd.append("action", "apfl_get_slider_options");
        fd.append("apfl_nonce", apfl_slider_obj.nonce);
        jQuery
            .ajax({
                url: apfl_slider_obj.ajaxurl,
                type: "POST",
                data: fd,
                contentType: false,
                processData: false,
                dataType: "JSON",
            })
            .done( async function (results) {
                if (results.error) {
                    console.log(results.error);
                } else {

                    await loadAssets(results);
                    await loadSlides(results);
                    jssor_1_slider_init();
                }
            })
            .fail(function (data) {
                console.log(data.responseText);
                console.log("Request Failed. Status - " + data.statusText);
            });
    }

    loadSlider();

});