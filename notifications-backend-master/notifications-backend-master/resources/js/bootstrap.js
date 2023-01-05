window._ = require('lodash');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

window.Vue = require('vue');

window.Event = new Vue;

window.fetchAutocomplete = function (url, autocomplete, channel_id, callback) {
    if (typeof channel_id !== "undefined" && channel_id !== null) {
        url += '&channel_id=' + this.channelId;
    }

    fetch(url)
        .then(res => res.json())
        .then(res => {
            const {data} = res;
            autocomplete.entries = data;
        })
        .catch(err => {
            autocomplete.isLoading = false;
            autocomplete.entries = []
        })
        .finally(() => {
            if (typeof callback !== "undefined") {
                callback(autocomplete)
            }
            autocomplete.isLoading = false;
        })
};

window.completeMethod = function (autocomplete, callback, url) {
    if (typeof autocomplete.start_loading_entries !== "undefined" && !autocomplete.start_loading_entries) {
        autocomplete.start_loading_entries = true;
        window.fetchAutocomplete(url, autocomplete, this.channelId, (data) => {
            if (typeof callback !== "undefined" && callback !== null) {
                callback(data)
            } else {
                autocomplete.entries.map(e => {
                    const name = e.name;
                    return Object.assign({}, e, {name})
                });
            }
        })
    }

    return autocomplete.entries.map(e => {
        if (typeof callback !== "undefined" && callback !== null) {
            return callback(e);
        } else {
            const name = e.name;
            return Object.assign({}, e, {name})
        }
    });
};

window.methodSearch = function (val, complete, autocomplete, url, channel_id) {
    if (complete.length > 0 && autocomplete.isLoading && (val !== '')) return;
    autocomplete.isLoading = true;
    window.fetchAutocomplete(url, autocomplete, channel_id);
};

// window.showNotification = function(message, type = 'alert-primary') {
//     window.Event.$emit('showNotification', message, type);
// };
