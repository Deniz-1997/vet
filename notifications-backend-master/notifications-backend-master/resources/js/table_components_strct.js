class TableComponentsStrct {
    constructor() {
    }

    call(api) {
        this.api_ = api;
        return {
            dictionary: {
                groupUser: {
                    name: "Группы пользователей",
                    api: this.api_.dictionary.groupUsers,
                    url: '/api/v2/dictionary/group-users',
                    templateItem: {
                        name: '',
                        responsible_notifications: false,
                        filter_notifications: false,
                    },
                    headers: [
                        {text: 'ID', value: 'id'},
                        {text: "Имя", value: "name"},
                        {text: "Фильтровать оповещения", value: "filter_notifications"},
                        {text: "Ответственный", value: "responsible_notifications"},
                        {text: "Создано", value: "created_at"},
                        {text: 'События', value: 'action', sortable: false},
                    ],
                    templates: [
                        {
                            name: 'item.filter_notifications', value: (e) => {
                                let status = e.filter_notifications ? 'On' : 'Off';
                                let color = e.filter_notifications ? 'green' : 'orange';

                                return '<span class="v-chip theme--dark v-size--default ' + color + '">' +
                                    '<span class="v-chip__content">' + status + '</span>' +
                                    '</span>';
                            },
                        },
                        {
                            name: 'item.responsible_notifications', value: (e) => {
                                let status = e.responsible_notifications ? 'On' : 'Off';
                                let color = e.responsible_notifications ? 'green' : 'orange';

                                return '<span class="v-chip theme--dark v-size--default ' + color + '">' +
                                    '<span class="v-chip__content">' + status + '</span>' +
                                    '</span>';
                            },
                        }
                    ],
                    api_data: {
                        count: 10000,
                        order: JSON.stringify(['id', 'ASC'])
                    },
                }
            },
            events: {
                list: {
                    api: this.api_.events.list,
                    url: '/api/v2/events/list',
                    name: "События",
                    templateItem: {
                        name: '',
                        hierarchy: false,
                        file: '',
                    },
                    headers: [
                        {text: 'ID', value: 'id'},
                        {text: "Имя", value: "name"},
                        {text: "Соблюдать иерархию", value: "hierarchy"},
                        {text: "Иконка", value: "path_internal"},
                        {text: "Создан", value: "created_at"},
                        {text: "Последнее обновление", value: "updated_at"},
                        {text: 'События', value: 'action', sortable: false},
                    ],
                    templates: [
                        {
                            name: 'item.hierarchy', value: (e) => {
                                let status = e.hierarchy ? 'On' : 'Off';
                                let color = e.hierarchy ? 'green' : 'orange';
                                return '<span class="v-chip theme--dark v-size--default ' + color + '">' +
                                    '<span class="v-chip__content">' + status + '</span>' +
                                    '</span>';
                            },
                        },
                        {
                            name: 'item.path_internal', value: e => {
                                return e.file !== null ? '<img style=" width: 25%; margin-top: 1em; " src="' + e.path_internal +'">' : 'Иконка не найдена';
                            }
                        }
                    ],
                    api_data: {
                        count: 10000,
                        with: JSON.stringify(['file']),
                        order: JSON.stringify(['id', 'ASC'])
                    },
                },
                template: {
                    url: '/api/v2/events/template',
                    name: "Список событий с шаблонами",
                    api: this.api_.events.template,
                    templateItem: {
                        event_id: 0,
                        template_id: 0,
                    },
                    headers: [
                        {text: 'ID', value: 'id'},
                        {text: "События", value: "event_id"},
                        {text: "Шаблон", value: "template_id"},
                        {text: "Создан", value: "created_at"},
                        {text: "Последнее обновление", value: "updated_at"},
                        {text: 'События', value: 'action', sortable: false},
                    ],
                    templates: [
                        {
                            name: 'item.event_id', value: (e) => {
                                return e.event.name;
                            }
                        },
                        {
                            name: 'item.template_id', value: (e) => {
                                return e.original_template.name;
                            }
                        },
                    ],
                    api_data: {
                        count: 10000,
                        with: JSON.stringify(['originalTemplate', 'event']),
                        order: JSON.stringify(['id', 'ASC'])
                    },
                }
            },
            channel: {
                administrators: {
                    name: "Доступ к каналам для администраторов",
                    api: this.api_.channels.administrators,
                    url: '/api/v2/channels/administrators',
                    templateItem: {
                        channel_id: 0,
                        user_id: 0,
                    },
                    headers: [
                        {text: 'ID', value: 'id'},
                        {text: "Канал", value: "channel_id"},
                        {text: "Имя пользователя", value: "user_id"},
                        {text: "Создан", value: "created_at"},
                        {text: "Последнее обновление", value: "updated_at"},
                        {text: 'События', value: 'action', sortable: false},
                    ],
                    templates: [
                        {
                            name: 'item.user_id', value: (e) => {
                                return (e.user === null) ? '<b style="font-weight: 600 !important;">Пользователь удален</b>' : e.user.name;
                            }
                        },
                        {
                            name: 'item.channel_id', value: (e) => {
                                return e.channel.name;
                            }
                        },
                    ],
                    api_data: {
                        count: 10000,
                        with: JSON.stringify(['user', 'channel']),
                        order: JSON.stringify(['id', 'ASC'])
                    },
                },
                list: {
                    name: 'Список каналов',
                    api: this.api_.channels.list,
                    url: 'api/v2/channels/list',
                    templateItem: {
                        sms_limit: 0,
                        email_limit: 0,
                        name: '',
                        send_sms: false,
                        send_email: false,
                    },
                    headers: [
                        {text: 'ID', value: 'id'},
                        {text: "Имя", value: "name"},
                        {text: "Отправка смс", value: "send_sms"},
                        {text: "Лимит смс", value: "sms_limit"},
                        {text: "Отправка на почту", value: "send_email"},
                        {text: "Лимимт писем", value: "email_limit"},
                        {text: "Создан", value: "created_at"},
                        {text: "Последнее обновление", value: "updated_at"},
                        {text: 'События', value: 'action', sortable: false},
                    ],
                    templates: [
                        {
                            name: 'item.send_sms', value: (e) => {
                                let status = e.send_sms ? 'On' : 'Off';
                                let color = e.send_sms ? 'green' : 'orange';

                                return '<span class="v-chip theme--dark v-size--default ' + color + '">' +
                                    '<span class="v-chip__content">' + status + '</span>' +
                                    '</span>';
                            },
                        },
                        {
                            name: 'item.send_email', value: (e) => {
                                let status = e.send_email ? 'On' : 'Off';
                                let color = e.send_email ? 'green' : 'orange';

                                return '<span class="v-chip theme--dark v-size--default ' + color + '">' +
                                    '<span class="v-chip__content">' + status + '</span>' +
                                    '</span>';
                            }
                        }
                    ],
                    api_data: {
                        count: 10000,
                        with: JSON.stringify([
                            'user'
                        ]),
                        order: JSON.stringify(['id', 'ASC'])
                    },
                },
                apis: {
                    name: "API ключи для каналов",
                    api: this.api_.channels.apis,
                    url: '/api/v2/channels/apis',
                    templateItem: {
                        channel_id: 0,
                        name: '',
                    },
                    headers: [
                        {text: 'ID', value: 'id'},
                        {text: "Канал", value: "channel_id"},
                        {text: "Ключ", value: "api_token"},
                        {text: "Имя", value: "name"},
                        {text: "Создан", value: "created_at"},
                        {text: "Последнее обновление", value: "updated_at"},
                        {text: 'События', value: 'action', sortable: false},
                    ],
                    templates: [
                        {
                            name: 'item.channel_id', value: (e) => {
                                return e.channel.name;
                            },
                        },
                    ],
                    api_data: {
                        count: 10000,
                        with: JSON.stringify(['channel']),
                        order: JSON.stringify(['id', 'ASC'])
                    },
                },
                event: {
                    url: '/api/v2/channels/event',
                    name: "События привязанные к каналу",
                    api: this.api_.channels.event,
                    templateItem: {
                        channel_id: 0,
                        event_id: 0,
                    },
                    headers: [
                        {text: 'ID', value: 'id'},
                        {text: "Канал", value: "channel_id"},
                        {text: "Событие", value: "event_id"},
                        {text: "Создан", value: "created_at"},
                        {text: "Последнее обновление", value: "updated_at"},
                        {text: 'События', value: 'action', sortable: false},
                    ],
                    templates: [
                        {
                            name: 'item.channel_id', value: (e) => {
                                return e.channel.name;
                            }
                        },
                        {
                            name: 'item.event_id', value: (e) => {
                                return e.event.name;
                            }
                        },
                    ],
                    api_data: {
                        count: 10000,
                        with: JSON.stringify(['channel', 'event']),
                        order: JSON.stringify(['id', 'ASC'])
                    },
                },
                users: {
                    name: "Доступ к каналам для пользователей",
                    api: this.api_.channels.users,
                    url: '/api/v2/channels/users',
                    templateItem: {
                        channel_id: 0,
                        user_id: 0,
                    },
                    headers: [
                        {text: 'ID', value: 'id'},
                        {text: "Канал", value: "channel_id"},
                        {text: "Имя пользователя", value: "user_id"},
                        {text: "Создан", value: "created_at"},
                        {text: "Последнее обновление", value: "updated_at"},
                        {text: 'События', value: 'action', sortable: false},
                    ],
                    templates: [
                        {
                            name: 'item.user_id', value: (e) => {
                                return (e.user === null) ? '<b style="font-weight: 600 !important;">Пользователь удален</b>' : e.user.name;
                            }
                        },
                        {
                            name: 'item.channel_id', value: (e) => {
                                return (e.channel === null) ? '<b style="font-weight: 600 !important;">Канал удален</b>' : e.channel.name;
                            }
                        },
                    ],
                    api_data: {
                        count: 10000,
                        with: JSON.stringify(['user', 'channel']),
                        order: JSON.stringify(['id', 'ASC'])
                    },
                }
            },
            template: {
                groupUser: {
                    api: this.api_.template.groupUser,
                    url: '/api/v2/template/group-user',
                    name: "Группы пользователя для шаблонов",
                    templateItem: {
                        group_id: 0,
                        template_id: 0,
                        priority: 0,
                    },
                    headers: [
                        {text: 'ID', value: 'id'},
                        {text: "Название шаблона", value: "group_id"},
                        {text: "Название группы", value: "template_id"},
                        {text: "Приоритет", value: "priority"},
                        {text: "Задержка отправки уведомлений", value: "delay_send"},
                        {text: "Создан", value: "created_at"},
                        {text: "Последнее обновление", value: "updated_at"},
                        {text: 'События', value: 'action', sortable: false},
                    ],
                    templates: [
                        {
                            name: 'item.group_id', value: (e) => {
                                return e.dictionary_group.name;
                            }
                        },
                        {
                            name: 'item.template_id', value: (e) => {
                                return e.template.name;
                            }
                        },
                        {
                            name: 'item.delay_send', value: (e) => {
                                return e.delay_send + ' мин'
                            },
                        },
                    ],
                    api_data: {
                        count: 10000,
                        with: JSON.stringify(['dictionaryGroup', 'template']),
                        order: JSON.stringify(['id', 'ASC'])
                    },
                },
                list: {
                    api: this.api_.template.list,
                    url: '/api/v2/template/list',
                    name: "Шаблоны",
                    templateItem: {
                        name: '',
                        text: 'null',
                        color: '',
                        format_date: 'YYYY-MM-DD H:i:s',
                        show_date: false,
                        show_time: false,
                        show_status_notify: false,
                    },
                    headers: [
                        {text: 'ID', value: 'id'},
                        {text: "Имя", value: "name"},
                        {text: "Цвет", value: "color"},
                        {text: "Показывать статус", value: "show_status_notify"},
                        {text: "Показывать дату", value: "show_date"},
                        {text: "Показывать время", value: "show_time"},
                        {text: "Формат даты", value: "format_date"},
                        {text: "Создан", value: "created_at"},
                        {text: "Последнее обновление", value: "updated_at"},
                        {text: 'События', value: 'action', sortable: false},
                    ],
                    templates: [
                        {
                            name: 'item.color', value: (e) => {
                                return '<span class="v-chip theme--dark v-size--default " style="background-color: ' + e.color + '">' +
                                    '<span class="v-chip__content">' + e.color + '</span>' +
                                    '</span>';
                            },
                        },
                        {
                            name: 'item.show_status_notify', value: (e) => {
                                let status = e.show_status_notify ? 'On' : 'Off';
                                let color = e.show_status_notify ? 'green' : 'orange';

                                return '<span class="v-chip theme--dark v-size--default ' + color + '">' +
                                    '<span class="v-chip__content">' + status + '</span>' +
                                    '</span>';
                            },
                        },
                        {
                            name: 'item.show_date', value: (e) => {
                                let status = e.show_date ? 'On' : 'Off';
                                let color = e.show_date ? 'green' : 'orange';

                                return '<span class="v-chip theme--dark v-size--default ' + color + '">' +
                                    '<span class="v-chip__content">' + status + '</span>' +
                                    '</span>';
                            },
                        },
                        {
                            name: 'item.show_time', value: (e) => {
                                let status = e.show_time ? 'On' : 'Off';
                                let color = e.show_time ? 'green' : 'orange';

                                return '<span class="v-chip theme--dark v-size--default ' + color + '">' +
                                    '<span class="v-chip__content">' + status + '</span>' +
                                    '</span>';
                            },
                        }
                    ],
                    api_data: {
                        count: 10000,
                        order: JSON.stringify(['id', 'ASC'])
                    },
                }
            },
            notifications: {
                list: {
                    api: this.api_.notifications.list,
                    url: '/api/v2/notifications/list',
                    name: "Оповещения",
                    templateItem: {
                        name: '',
                        status: '',
                        channel_id: 0,
                    },
                    headers: [
                        {text: 'ID', value: 'id'},
                        {text: "Имя", value: "name"},
                        {text: "Канал", value: "channel_id"},
                        {text: "Статус", value: "status"},
                        {text: "Создан", value: "created_at"},
                        {text: "Последнее обновление", value: "updated_at"},
                        {text: 'События', value: 'action', sortable: false},
                    ],
                    templates: [
                        {
                            name: 'item.channel_id', value: (e) => {
                                return e.channel.name
                            },
                        }
                    ],
                    api_data: {
                        count: 10000,
                        with: JSON.stringify(['channel']),
                        order: JSON.stringify(['id', 'DESC'])
                    },
                },
                events: {
                    api: this.api_.notifications.events,
                    url: '/api/v2/notifications/events',
                    name: "События для оповещений",
                    templateItem: {
                        text: null,
                        event_id: 0,
                        notifications_id: 0,
                    },
                    headers: [
                        {text: 'ID', value: 'id'},
                        {text: "Текст", value: "text"},
                        {text: "Событие", value: "event_id"},
                        {text: "Оповещение", value: "notifications_id"},
                        {text: "Создан", value: "created_at"},
                        {text: "Последнее обновление", value: "updated_at"},
                        {text: 'События', value: 'action', sortable: false},
                    ],
                    templates: [
                        {
                            name: 'item.event_id', value: (e) => {
                                return e.event.name
                            },
                        },
                        {
                            name: 'item.notifications_id', value: (e) => {
                                return e.notification !== undefined && e.notification !== null ? e.notification.name : null
                            },
                        }
                    ],
                    api_data: {
                        count: 10000,
                        with: JSON.stringify(['event', 'notification']),
                        order: JSON.stringify(['id', 'DESC'])
                    },
                },
                sends: {
                    api: this.api_.notifications.sends,
                    url: '/api/v2/notifications/sends',
                    name: "Список на отправку или отправленные",
                    templateItem: {
                        user_id: 0,
                        notify_event_id: 0,
                        sended_date: '',
                    },
                    headers: [
                        {text: 'ID', value: 'id'},
                        {text: "Имя пользователя", value: "user_id"},
                        {text: "Оповещение", value: "notify_event_id"},

                        {text: "Отправка на почту", value: "send_email"},
                        {text: "Отправка на телефон", value: "send_sms"},
                        {text: "Отправка на девайс", value: "send_device"},
                        {text: "Отправлено", value: "send"},
                        {text: "Просмотренно", value: "viewed"},

                        {text: "Дата отправки", value: "sended_date"},
                        {text: "Создан", value: "created_at"},
                        {text: "Последнее обновление", value: "updated_at"},
                        {text: 'События', value: 'action', sortable: false},
                    ],
                    templates: [
                        {
                            name: 'item.notify_event_id', value: (e) => {
                                return e.notify_event.event.name + ' - ' + e.notify_event.notification.name;
                            },
                        },
                        {
                            name: 'item.user_id', value: (e) => {
                                return (e.user === null) ? '<b style="font-weight: 600 !important;">Пользователь удален</b>' : e.user.name;
                            },
                        },
                        {
                            name: 'item.send_email', value: (e) => {
                                let status = e.send_email ? 'On' : 'Off';
                                let color = e.send_email ? 'green' : 'orange';

                                return '<span class="v-chip theme--dark v-size--default ' + color + '">' +
                                    '<span class="v-chip__content">' + status + '</span>' +
                                    '</span>';
                            },
                        },
                        {
                            name: 'item.send_sms', value: (e) => {
                                let status = e.send_sms ? 'On' : 'Off';
                                let color = e.send_sms ? 'green' : 'orange';

                                return '<span class="v-chip theme--dark v-size--default ' + color + '">' +
                                    '<span class="v-chip__content">' + status + '</span>' +
                                    '</span>';
                            },
                        },
                        {
                            name: 'item.send_device', value: (e) => {
                                let status = e.send_device ? 'On' : 'Off';
                                let color = e.send_device ? 'green' : 'orange';

                                return '<span class="v-chip theme--dark v-size--default ' + color + '">' +
                                    '<span class="v-chip__content">' + status + '</span>' +
                                    '</span>';
                            },
                        },
                        {
                            name: 'item.send', value: (e) => {
                                let status = e.send ? 'On' : 'Off';
                                let color = e.send ? 'green' : 'orange';

                                return '<span class="v-chip theme--dark v-size--default ' + color + '">' +
                                    '<span class="v-chip__content">' + status + '</span>' +
                                    '</span>';
                            },
                        },
                        {
                            name: 'item.viewed', value: (e) => {
                                let status = e.viewed ? 'On' : 'Off';
                                let color = e.viewed ? 'green' : 'orange';

                                return '<span class="v-chip theme--dark v-size--default ' + color + '">' +
                                    '<span class="v-chip__content">' + status + '</span>' +
                                    '</span>';
                            },
                        }
                    ],
                    api_data: {
                        count: 10000,
                        with: JSON.stringify(['notifyEvent', 'user']),
                        order: JSON.stringify(['id', 'DESC'])
                    },
                },
            },
            user: {
                devices: {
                    name: "Пользователи",
                    api: this.api_.user.devices,
                    url: '/api/v2/user/devices',
                    templateItem: {
                        user: '',
                        device: '',
                        pass: '',
                        reg_id: '',
                        token: '',
                        access_key: '',
                    },
                    headers: [
                        {text: 'ID', value: 'id'},
                        {text: "Имя", value: "user"},
                        {text: "Устройство", value: "device"},
                        {text: "Пароль", value: "pass"},
                        {text: "Reg ID", value: "reg_id"},
                        {text: "Знак", value: "token"},
                        {text: "Ключ доступа", value: "access_key"},
                        {text: 'События', value: 'action', sortable: false},
                    ],
                    templates: [
                        {
                            name: 'item.user', value: (e) => {
                                return e.user !== null && typeof e.user !== "undefined" ? e.user.name : 'Неизвестно';
                            },
                        },
                    ],
                    api_data: {
                        count: 10000,
                        with: JSON.stringify([
                            'user'
                        ]),
                        order: JSON.stringify(['id', 'ASC'])
                    },
                },
                list: {
                    name: "Пользователи",
                    api: this.api_.user.list,
                    url: '/api/v2/user/list',
                    templateItem: {
                        name: '',
                        email: '',
                        phone: '',
                        groups: [],
                        roles: [],
                        organization_id: 0
                    },
                    headers: [
                        {text: 'События', value: 'action', sortable: false},
                        {text: 'ID', value: 'id'},
                        {text: "Имя", value: "name"},
                        {text: "Почта", value: "email"},
                        {text: "Телефон", value: "phone"},
                        {text: "Группы", value: "groups"},
                        {text: "Роль", value: "roles"},
                        {text: "Каналы", value: "channels"},
                        {text: "Организации/Подорганизация", value: "organization_id"},
                        // {text: "Ключ", value: "api_token"},
                        {text: "Последнее обновление", value: "updated_at"},
                    ],
                    templates: [
                        {
                            name: 'item.roles', value: (e) => {
                                let r = '-';

                                e.roles.map((v, i) => {
                                    if (i === 0) r = '';
                                    r += v.name + '(' + v.guard_name + ')';
                                    if (i < (e.roles.length - 1)) {
                                        r += ' <br>';
                                    }
                                });

                                return r;
                            },
                        },
                        {
                            name: 'item.organization_id', value: (e) => {
                                let s = '-';

                                if (typeof e.organization !== "undefined" && e.organization !== null) {
                                    s = e.organization.name;
                                }
                                return s;
                            },
                        },
                        {
                            name: 'item.groups', value: (e) => {
                                let r = '-';

                                e.groups.map((v, i) => {
                                    if (i === 0) r = '';

                                    if (typeof v.dictionary_group !== "undefined" && v.dictionary_group !== null) {
                                        r += v.dictionary_group.name;
                                        if (i < (e.groups.length - 1)) {
                                            r += ' <br>';
                                        }
                                    }
                                });

                                return r;
                            },
                        },
                        {
                            name: 'item.channels', value: (e) => {
                                let r = '-';

                                e.channels.map((v, i) => {
                                    if (i === 0) r = '';

                                    if (typeof v.channel !== "undefined" && v.channel !== null) {
                                        r += v.channel.name;
                                        if (i < (e.groups.length - 1)) {
                                            r += ' <br>';
                                        }
                                    }
                                });

                                return r;
                            },
                        }
                    ],
                    api_data: {
                        count: 10000,
                        with: JSON.stringify([
                            'organization',
                            'roles',
                            'channels',
                            'groups'
                        ]),
                        order: JSON.stringify(['id', 'ASC'])
                    },
                }
            }
        }
    }
}

export default new TableComponentsStrct();
