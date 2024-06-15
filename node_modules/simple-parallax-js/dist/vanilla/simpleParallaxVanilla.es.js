const h = (s) => NodeList.prototype.isPrototypeOf(s) || HTMLCollection.prototype.isPrototypeOf(s) ? Array.from(s) : typeof s == "string" || s instanceof String ? document.querySelectorAll(s) : [s], p = () => Element.prototype.closest && "IntersectionObserver" in window;
class u {
  constructor() {
    this.positions = {
      top: 0,
      bottom: 0,
      height: 0
    };
  }
  setViewportTop(t) {
    return this.positions.top = t ? t.scrollTop : window.pageYOffset, this.positions;
  }
  setViewportBottom() {
    return this.positions.bottom = this.positions.top + this.positions.height, this.positions;
  }
  setViewportAll(t) {
    return this.positions.top = t ? t.scrollTop : window.pageYOffset, this.positions.height = t ? t.clientHeight : document.documentElement.clientHeight, this.positions.bottom = this.positions.top + this.positions.height, this.positions;
  }
}
const i = new u(), c = () => {
  const s = "transform webkitTransform mozTransform oTransform msTransform".split(
    " "
  );
  let t, e = 0;
  for (; t === void 0; )
    t = document.createElement("div").style[s[e]] !== void 0 ? s[e] : void 0, e += 1;
  return t;
}, r = c(), f = (s) => s.tagName.toLowerCase() !== "img" && s.tagName.toLowerCase() !== "picture" ? !0 : !(!s || !s.complete || typeof s.naturalWidth < "u" && s.naturalWidth === 0);
class d {
  constructor(t, e) {
    this.element = t, this.elementContainer = t, this.settings = e, this.isVisible = !0, this.isInit = !1, this.oldTranslateValue = -1, this.init = this.init.bind(this), this.customWrapper = this.settings.customWrapper && this.element.closest(this.settings.customWrapper) ? this.element.closest(this.settings.customWrapper) : null, f(t) ? this.init() : this.element.addEventListener("load", () => {
      setTimeout(() => {
        this.init(!0);
      }, 50);
    });
  }
  init(t) {
    this.isInit || (t && (this.rangeMax = null), !this.element.closest(".simpleParallax") && (this.settings.overflow === !1 && this.wrapElement(this.element), this.setTransformCSS(), this.getElementOffset(), this.intersectionObserver(), this.getTranslateValue(), this.animate(), this.settings.delay > 0 ? setTimeout(() => {
      this.setTransitionCSS(), this.elementContainer.classList.add(
        "simple-parallax-initialized"
      );
    }, 10) : this.elementContainer.classList.add("simple-parallax-initialized"), this.isInit = !0));
  }
  // if overflow option is set to false
  // wrap the element into a .simpleParallax div and apply overflow hidden to hide the image excedant (result of the scale)
  wrapElement() {
    const t = this.element.closest("picture") || this.element;
    let e = this.customWrapper || document.createElement("div");
    e.classList.add("simpleParallax"), e.style.overflow = "hidden", this.customWrapper || (t.parentNode.insertBefore(e, t), e.appendChild(t)), this.elementContainer = e;
  }
  // unwrap the element from .simpleParallax wrapper container
  unWrapElement() {
    const t = this.elementContainer;
    this.customWrapper ? (t.classList.remove("simpleParallax"), t.style.overflow = "") : t.replaceWith(...t.childNodes);
  }
  // apply default style on element
  setTransformCSS() {
    this.settings.overflow === !1 && (this.element.style[r] = `scale(${this.settings.scale})`), this.element.style.willChange = "transform";
  }
  // apply the transition effet
  setTransitionCSS() {
    this.element.style.transition = `transform ${this.settings.delay}s ${this.settings.transition}`;
  }
  // remove style of the element
  unSetStyle() {
    this.element.style.willChange = "", this.element.style[r] = "", this.element.style.transition = "";
  }
  // get the current element offset
  getElementOffset() {
    const t = this.elementContainer.getBoundingClientRect();
    if (this.elementHeight = t.height, this.elementTop = t.top + i.positions.top, this.settings.customContainer) {
      const e = this.settings.customContainer.getBoundingClientRect();
      this.elementTop = t.top - e.top + i.positions.top;
    }
    this.elementBottom = this.elementHeight + this.elementTop;
  }
  // build the Threshold array to cater change for every pixel scrolled
  buildThresholdList() {
    const t = [];
    for (let e = 1; e <= this.elementHeight; e++) {
      const o = e / this.elementHeight;
      t.push(o);
    }
    return t;
  }
  // create the Intersection Observer
  intersectionObserver() {
    const t = {
      root: null,
      threshold: this.buildThresholdList()
    };
    this.observer = new IntersectionObserver(
      this.intersectionObserverCallback.bind(this),
      t
    ), this.observer.observe(this.element);
  }
  // Intersection Observer Callback to set the element at visible state or not
  intersectionObserverCallback(t) {
    t.forEach((e) => {
      e.isIntersecting ? this.isVisible = !0 : this.isVisible = !1;
    });
  }
  // check if the current element is visible in the Viewport
  // for browser that not support Intersection Observer API
  checkIfVisible() {
    return this.elementBottom > i.positions.top && this.elementTop < i.positions.bottom;
  }
  // calculate the range between image will be translated
  getRangeMax() {
    const t = this.element.clientHeight;
    this.rangeMax = t * this.settings.scale - t;
  }
  // get the percentage and the translate value to apply on the element
  getTranslateValue() {
    let t = ((i.positions.bottom - this.elementTop) / ((i.positions.height + this.elementHeight) / 100)).toFixed(1);
    return t = Math.min(100, Math.max(0, t)), this.settings.maxTransition !== 0 && t > this.settings.maxTransition && (t = this.settings.maxTransition), this.oldPercentage === t || (this.rangeMax || this.getRangeMax(), this.translateValue = (t / 100 * this.rangeMax - this.rangeMax / 2).toFixed(0), this.oldTranslateValue === this.translateValue) ? !1 : (this.oldPercentage = t, this.oldTranslateValue = this.translateValue, !0);
  }
  // animate the image
  animate() {
    let t = 0, e = 0, o;
    (this.settings.orientation.includes("left") || this.settings.orientation.includes("right")) && (e = `${this.settings.orientation.includes("left") ? this.translateValue * -1 : this.translateValue}px`), (this.settings.orientation.includes("up") || this.settings.orientation.includes("down")) && (t = `${this.settings.orientation.includes("up") ? this.translateValue * -1 : this.translateValue}px`), this.settings.overflow === !1 ? o = `translate3d(${e}, ${t}, 0) scale(${this.settings.scale})` : o = `translate3d(${e}, ${t}, 0)`, this.element.style[r] = o;
  }
}
let l = !1, n = [], a, m;
class g {
  constructor(t, e) {
    t && p() && (this.elements = h(t), this.defaults = {
      delay: 0,
      orientation: "up",
      scale: 1.3,
      overflow: !1,
      transition: "cubic-bezier(0,0,0,1)",
      customContainer: "",
      customWrapper: "",
      maxTransition: 0
    }, this.settings = Object.assign(this.defaults, e), this.settings.customContainer && ([this.customContainer] = h(
      this.settings.customContainer
    )), this.lastPosition = -1, this.resizeIsDone = this.resizeIsDone.bind(this), this.refresh = this.refresh.bind(this), this.proceedRequestAnimationFrame = this.proceedRequestAnimationFrame.bind(this), this.init());
  }
  init() {
    i.setViewportAll(this.customContainer), n = [
      ...this.elements.map(
        (t) => new d(t, this.settings)
      ),
      ...n
    ], l || (this.proceedRequestAnimationFrame(), window.addEventListener("resize", this.resizeIsDone), l = !0);
  }
  // wait for resize to be completely done
  resizeIsDone() {
    clearTimeout(m), m = setTimeout(this.refresh, 200);
  }
  // animation frame
  proceedRequestAnimationFrame() {
    if (i.setViewportTop(this.customContainer), this.lastPosition === i.positions.top) {
      a = window.requestAnimationFrame(
        this.proceedRequestAnimationFrame
      );
      return;
    }
    i.setViewportBottom(), n.forEach((t) => {
      this.proceedElement(t);
    }), a = window.requestAnimationFrame(
      this.proceedRequestAnimationFrame
    ), this.lastPosition = i.positions.top;
  }
  // proceed the element
  proceedElement(t) {
    let e = !1;
    this.customContainer ? e = t.checkIfVisible() : e = t.isVisible, e && t.getTranslateValue() && t.animate();
  }
  refresh() {
    i.setViewportAll(this.customContainer), n.forEach((t) => {
      t.getElementOffset(), t.getRangeMax();
    }), this.lastPosition = -1;
  }
  destroy() {
    const t = [];
    n = n.filter((e) => this.elements.includes(e.element) ? (t.push(e), !1) : e), t.forEach((e) => {
      e.unSetStyle(), this.settings.overflow === !1 && e.unWrapElement();
    }), n.length || (window.cancelAnimationFrame(a), window.removeEventListener("resize", this.refresh), l = !1);
  }
}
export {
  g as default
};
