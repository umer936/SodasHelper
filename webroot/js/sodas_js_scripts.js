export let bootstrapVersion;
bootstrap_load_or_detect();

export const dayOfYear = (date) => Math.round((date - new Date(date.getFullYear(), 0, 0)) / (1000 * 60 * 60 * 24));

const ready = (callback) => {
    if (document.readyState !== "loading") callback();
    else document.addEventListener("DOMContentLoaded", callback);
};

function parseVersionString (str) {
    if (typeof(str) != 'string') { return false; }
    const x = str.split('.');
    // parse from string or default to 0 if can't parse
    const maj = parseInt(x[0]) || 0;
    const min = parseInt(x[1]) || 0;
    const pat = parseInt(x[2]) || 0;
    return {
        major: maj,
        minor: min,
        patch: pat
    }
}

function bootstrap_load_or_detect() {
    if (typeof bootstrap === 'undefined') {
        // load bootstrap
        document.querySelector('head').innerHTML +=
            '<link ' +
            'href="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/css/bootstrap.min.css" ' +
            'rel="stylesheet" ' +
            'crossorigin="anonymous">';

        const script = document.createElement("script");
        script.type = "text/javascript";
        script.src = "https://cdn.jsdelivr.net/npm/bootstrap@5/dist/js/bootstrap.bundle.min.js";
        script.crossOrigin = "anonymous";
        document.body.appendChild(script);
        script.addEventListener("load", () => {
            console.log("Script added successfully");
        });
    }
    bootstrapVersion = bootstrap.Tooltip.VERSION;
    bootstrapVersion = parseVersionString(bootstrapVersion);
}
