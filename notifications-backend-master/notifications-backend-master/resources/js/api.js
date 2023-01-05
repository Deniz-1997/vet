const resources = {
    user: ['get', 'devices', 'list'],
    events: ['list', 'template'],
    template: ['list', 'group-user'],
    notifications: ['list', 'events', 'sends'],
    dictionary: ['group-users', 'organizations'],
    channels: ['list', 'apis', 'users', 'administrators', 'event']
};

class Api {
    constructor() {
    }

    call(auth) {
        this.auth = auth;
        let t = this.auth.token;
        this.api = new RestClient('/api/v2', {
            shortcutRules:
                [
                    (resourceName) => resourceName.replace(/(-)(.)/g, (match, p1, p2) => p2.toUpperCase())
                ],
            contentType: "application/json",
            responseType: "json",
            apiToken: t
        });

        this.api.res(resources);

        this.api.on('request', function (xhr) {
            xhr.setRequestHeader('Content-type', ' charset=utf-8');
        });
        return this.api;
    }
}

export default new Api();
