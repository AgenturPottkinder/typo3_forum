import Tags from "./bootstrap-tags/tags.js";

// Method override to render tags as we need them.
// bootstrap-tags does not allow setting colors through any method other than classes,
// but our tag colors are not determined by CSS classes, they're fed from the database.
class CustomTags extends Tags {
    static init(selector = "select[multiple]", opts = {}) {
        /**
         * @type {NodeListOf<HTMLSelectElement>}
         */
        let list = document.querySelectorAll(selector);
        for (let i = 0; i < list.length; i++) {
          if (CustomTags.getInstance(list[i])) {
            continue;
          }
          new CustomTags(list[i], opts);
        }
      }

    _createBadge(text, value = null, data = {}) {
        const bver = this._getBootstrapVersion();
        const allowClear = this._config.allowClear && !data.disabled;

        // create span
        let html = text;
        let span = document.createElement("span");
        span.style.setProperty('--tx-typo3forum-background', data.background);
        span.style.setProperty('--tx-typo3forum-color', data.color);
        let classes = ["me-2"];
        let badgeStyle = this._config.badgeStyle;
        if (data.badgeStyle) {
            badgeStyle = data.badgeStyle;
        }
        if (data.badgeClass) {
            classes.push(...data.badgeClass.split(" "));
        }
        if (this._config.baseClass) {
            // custom style
            classes.push(...this._config.baseClass.split(" "));
        } else if (bver === 5) {
            // https://getbootstrap.com/docs/5.3/components/badge/
            // add extra classes to avoid any layout issues due to very large labels
            classes = [...classes, ...["bg-" + badgeStyle, "mw-100", "overflow-x-hidden"]];
        } else {
            // https://getbootstrap.com/docs/4.6/components/badge/
            classes = [...classes, ...["badge-" + badgeStyle]];
        }

        if (data.disabled) {
            classes.push(...["disabled", "opacity-50"]);
        }

        // We cannot really rely on classes to get a proper sizing
        span.style.margin = "2px 6px 2px 0px";
        // Use logical styles for RTL support
        span.style.marginBlock = "2px";
        span.style.marginInline = "0px 6px";
        span.classList.add(...classes);
        span.setAttribute("data-value", value);
        // Tooltips
        if (data.title) {
            span.setAttribute("title", data.title);
        }

        if (allowClear) {
            const btn =
                '<button type="button" class="btn p-0 pt-1 m-0 border-0 me-1 d-flex align-items-center" style="color: inherit !important;"><i style="font-size:0.85em" class="fas fa-xmark" aria-label="' +
                this._config.clearLabel +
                '"></i></button>'
            ;
            html = btn + html;
        }

        span.innerHTML = html;
        this._containerElement.insertBefore(span, this._searchInput);
        if (window.bootstrap && window.bootstrap.Tooltip) {
            window.bootstrap.Tooltip.getOrCreateInstance(span);
        }

        if (allowClear) {
            span.querySelector("button").addEventListener("click", (event) => {
                event.preventDefault();
                event.stopPropagation();
                if (!this.isDisabled()) {
                    this.removeItem(value);
                    //@ts-ignore
                    document.activeElement.blur();
                    this._adjustWidth();
                }
            });
        }
    }
}

CustomTags.init(
    "[data-bootstrap-tag-input]",
    {
        separator: [' ', ','],
        allowClear: true,
        allowSame: false,
    }
);
