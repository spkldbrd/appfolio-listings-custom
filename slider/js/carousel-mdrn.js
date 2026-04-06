class Carousel {
    constructor(opts) {
        Object.assign(this, {
            track: opts.track,
            leftBtn: opts.leftBtn,
            rightBtn: opts.rightBtn,
            cardSel: opts.cardSel || ".apfl-cm-item",
            visible: opts.visible || 1,
            loop: opts.loop || false,
            gap: opts.gap || 0,
            idx: 0,
            autoplay: opts.autoplay || false,
            direction: opts.direction || "right",
            interval: opts.interval || 2000,
            timer: null,
        });
        this.cards = [...this.track.querySelectorAll(this.cardSel)];
        this.init();
    }

    init() {
        this.clone();
        this.bind();
        this.go(false);
        this.start();
    }

    clone() {
        if (!this.loop) return;
        if (this.cards.length <= this.getVisibleCount()) return;

        this.track.querySelectorAll(".clone").forEach((c) => c.remove());
        this.cards = [...this.track.querySelectorAll(this.cardSel)];
        const n = this.getVisibleCount();
        const cardCount = this.cards.length;

        for (let i = 0; i < n; i++) {
            
            const left = this.cards[cardCount - 1 - i].cloneNode(true);
            left.classList.add("clone");
            this.track.prepend(left);

            const right = this.cards[i].cloneNode(true);
            right.classList.add("clone");
            this.track.append(right);
        }
        this.cards = [...this.track.querySelectorAll(this.cardSel)];
        this.idx = n;
    }

    bind() {
        [this.leftBtn, this.rightBtn].forEach((b) =>
            b?.addEventListener("click", () => {
                this.idx += b === this.leftBtn ? -1 : 1;
                this.go(true);
            })
        );

        this.track.addEventListener("mouseenter", () => this.stop());
        this.track.addEventListener("mouseleave", () => this.start());

        this.track.addEventListener("transitionend", () => {
            if (!this.loop) return;
            if (this.cards.length <= this.getVisibleCount()) return;

            const total = this.cards.length - this.getVisibleCount() * 2;
            if (this.idx < 1) {
                this.idx = total;
                this.go(false);
            } else if (this.idx >= total + this.getVisibleCount()) {
                this.idx = this.getVisibleCount();
                this.go(false);
            }
        })

        window.addEventListener("resize", () => {
            this.clone();
            this.go(false);
        });
    }

    go(animate = true) {
        this.track.style.transition = animate ? "transform .5s" : "none";
        this.track.style.transform = `translateX(${
            -this.idx * (100 / this.getVisibleCount())
        }%)`;
        this.btns();
    }

    btns() {
        const hide = this.cards.length <= this.getVisibleCount();
        [this.leftBtn, this.rightBtn].forEach((b) => {
            if (!b) return;
            b.style.display = hide ? "none" : "";
            if (!this.loop) {
                b.disabled =
                    b === this.leftBtn
                        ? this.idx === 0
                        : this.idx >=
                          this.cards.length - this.getVisibleCount();
            }
        });
    }

    getVisibleCount() {
        if (window.innerWidth <= 700) return 1;
        if (window.innerWidth <= 900) return 2;
        return 3;
    }

    start() {
        if (!this.loop) return;
        if (!this.autoplay) return;
        if (this.cards.length <= this.getVisibleCount()) return;

        this.stop();
        this.timer = setInterval(() => {
            this.idx += this.direction === "right" ? 1 : -1;
            this.go(true);
        }, this.interval);
    }

    stop() {
        clearInterval(this.timer);
    }
}
