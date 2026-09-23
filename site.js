/*
 * Ühine skript: tume režiim, animatsioonid, mobiilimenüü, blogi viimased
 * postitused ja tööde filter. Tume režiim kasutab sama localStorage võtit nagu
 * WordPressi blogi teema (hybridmagDarkMode), nii et valik kehtib mõlemal saidil.
 */
(function () {
    "use strict";

    var BLOG = "https://maksimtsikvasvili24.thkit.ee/wp/";
    var DARK_KEY = "hybridmagDarkMode";
    var html = document.documentElement;
    var reduceMotion = window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    function storageSet(key, value) {
        try {
            localStorage.setItem(key, value);
        } catch (e) {
            /* privaatne aken vms */
        }
    }

    // --- Tume režiim ---
    function syncDarkLabels() {
        var dark = html.classList.contains("hm-dark");
        document.querySelectorAll(".dark-toggle").forEach(function (btn) {
            btn.setAttribute("aria-pressed", dark ? "true" : "false");
            btn.setAttribute("aria-label", dark ? "Lülita hele režiim" : "Lülita tume režiim");
        });
    }

    document.querySelectorAll(".dark-toggle").forEach(function (btn) {
        btn.addEventListener("click", function () {
            // värvid vahetuvad sujuvalt ja ikoon keerleb
            html.classList.add("theme-anim");
            window.setTimeout(function () {
                html.classList.remove("theme-anim");
            }, 400);
            btn.classList.remove("spin");
            void btn.offsetWidth;
            btn.classList.add("spin");

            var dark = html.classList.toggle("hm-dark");
            storageSet(DARK_KEY, dark ? "enabled" : "disabled");
            syncDarkLabels();
        });
    });
    syncDarkLabels();

    // --- Päis saab kerimisel varju ---
    var header = document.querySelector(".site-header");
    if (header) {
        var onScroll = function () {
            header.classList.toggle("is-scrolled", window.scrollY > 8);
        };
        window.addEventListener("scroll", onScroll, { passive: true });
        onScroll();
    }

    // --- Kaardid ilmuvad kerimisel ---
    if (!reduceMotion && "IntersectionObserver" in window) {
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) {
                    return;
                }
                var el = entry.target;
                el.classList.add("is-visible");
                observer.unobserve(el);
                // pärast ilmumist eemaldame klassi, et hover-efektid oleksid jälle kiired
                el.addEventListener("transitionend", function done(event) {
                    if (event.propertyName === "transform") {
                        el.classList.remove("reveal", "is-visible");
                        el.style.removeProperty("--reveal-delay");
                        el.removeEventListener("transitionend", done);
                    }
                });
            });
        }, { rootMargin: "0px 0px -40px 0px", threshold: 0.1 });

        document.querySelectorAll(".site-main .card, .site-main .section-head, .site-main .filters").forEach(function (el) {
            var siblings = el.parentElement ? Array.prototype.indexOf.call(el.parentElement.children, el) : 0;
            el.style.setProperty("--reveal-delay", (siblings % 4) * 0.07 + "s");
            el.classList.add("reveal");
            observer.observe(el);
        });
    }

    // --- Mobiilimenüü ---
    var menuToggle = document.querySelector(".menu-toggle");

    if (menuToggle) {
        menuToggle.addEventListener("click", function () {
            var open = document.body.classList.toggle("menu-open");
            menuToggle.setAttribute("aria-expanded", open ? "true" : "false");
        });

        document.addEventListener("keydown", function (event) {
            if (event.key === "Escape" && document.body.classList.contains("menu-open")) {
                document.body.classList.remove("menu-open");
                menuToggle.setAttribute("aria-expanded", "false");
                menuToggle.focus();
            }
        });
    }

    // --- Blogi viimased postitused (WordPress REST API) ---
    // HTML-is on staatiline loend; kui päring õnnestub, asendatakse see värskega.
    document.querySelectorAll("[data-wp-latest]").forEach(function (list) {
        var count = parseInt(list.getAttribute("data-wp-latest"), 10) || 5;
        var url = BLOG + "wp-json/wp/v2/posts?per_page=" + count + "&_fields=title,link";

        fetch(url)
            .then(function (response) {
                if (!response.ok) {
                    throw new Error(response.status);
                }
                return response.json();
            })
            .then(function (posts) {
                if (!Array.isArray(posts) || posts.length === 0) {
                    return;
                }
                var items = posts.map(function (post) {
                    var li = document.createElement("li");
                    var a = document.createElement("a");
                    var tmp = document.createElement("textarea");
                    tmp.innerHTML = post.title.rendered; // dekodeerib HTML-olemid
                    a.textContent = tmp.value;
                    a.href = post.link;
                    li.appendChild(a);
                    return li;
                });
                list.replaceChildren.apply(list, items);
            })
            .catch(function () {
                /* jääb staatiline loend */
            });
    });

    // --- Tööde filter (Tehtud tööd) ---
    document.querySelectorAll("[data-filter-nav]").forEach(function (nav) {
        var grid = document.getElementById(nav.getAttribute("data-filter-nav"));
        if (!grid) {
            return;
        }
        var buttons = nav.querySelectorAll("[data-filter]");

        buttons.forEach(function (btn) {
            btn.addEventListener("click", function () {
                var filter = btn.getAttribute("data-filter");
                buttons.forEach(function (other) {
                    other.setAttribute("aria-pressed", other === btn ? "true" : "false");
                });
                grid.querySelectorAll("[data-cat]").forEach(function (card) {
                    var cats = card.getAttribute("data-cat").split(" ");
                    var hide = filter !== "all" && cats.indexOf(filter) === -1;
                    card.hidden = hide;
                    card.classList.remove("reveal", "is-visible", "pop");
                    if (!hide && !reduceMotion) {
                        void card.offsetWidth;
                        card.classList.add("pop");
                    }
                });
            });
        });
    });
})();
