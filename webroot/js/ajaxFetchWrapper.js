/**
 * @umer936, umer936/ajaxFetchWrapper.js
 * https://gist.github.com/umer936/c6bdbd80b8620dea5dfe7eac5e57b000
 * modified from: Billcountry/fetch_wrapper.js
 *
 * @param options Object of options:
 {
        beforeSend (function): null,
        url (string): "",
        type (string): if data => "POST", otherwise "GET",
        headers (object with key => value pairs): {}
        dataType (string): "text",
        data (object with key => value pairs OR a form element): null,
        success (function): null,
        error (function): null,
        complete (function): null,
        global (bool): true,
        debug (bool): false
     }
 */
const ajax = function (options) {
    if (options.beforeSend !== undefined && options.beforeSend instanceof Function) {
        options.beforeSend();
    }
    function setDefaultVal(value, defaultValue) {
        return (value === undefined) ? defaultValue : value;
    }

    let settings = {
        url: setDefaultVal(options.url, ""),
        type: setDefaultVal(options.type, "GET"),
        headers: setDefaultVal(options.headers, {}),
        data: setDefaultVal(options.data, null),
        dataType: setDefaultVal(options.dataType, "text"),
        global: setDefaultVal(options.global, true),
        success: setDefaultVal(options.success, function (response) {
            if (options.debug) console.log(response)
        }),
        error: setDefaultVal(options.error, function (error) {
            if (options.debug) console.log(error)
        })
    };
    let fetchOptions = {};

    if (options.data !== undefined) {
        let data;
        if (options.data.tagName && options.data.tagName.toLowerCase() === 'form') {
            data = new FormData(options.data)
        }
        else {
            data = new FormData();
            Object.keys(settings.data).forEach(function (key) {
                data.append(key, settings.data[key]);
            });
        }
        fetchOptions['body'] = data;
        settings.type = 'POST';
    }

    function checkStatus(response) {
        if (response.status >= 200 && response.status < 300) {
            return response
        } else {
            let error = new Error(response.statusText);
            error.response = response;
            throw error;
        }
    }

    function parseData(response) {
        let type = response.headers.get('Content-Type');
        if (settings.dataType === "json" || type.includes("json")) {
            return response.json();
        } else {
            return response.text()
        }
    }

    fetchOptions['method'] = settings.type;
    fetchOptions['headers'] = settings.headers;

    // Set global header settings here, eg. 'X-CSRF-Token'
    if (settings.global && typeof ajaxOptions !== 'undefined' && ajaxOptions instanceof Function) {
        ajaxOptions(settings);
    }


    fetch(settings.url, fetchOptions)
        .then(checkStatus)
        .then(parseData)
        .then(settings.success)
        .catch(settings.error);


    if (options.complete !== undefined && options.complete instanceof Function) {
        options.complete();
    }
};
