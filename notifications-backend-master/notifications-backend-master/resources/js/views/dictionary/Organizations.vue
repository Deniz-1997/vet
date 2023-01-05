<template>
    <v-app class="body-column">
        <v-container>
            <v-row justify="center">
                <v-expansion-panels accordion>
                    <v-col cols="12">
                        <v-btn @click="add(0)" color="success" outlined tile>
                            <v-icon left>mdi-plus</v-icon>
                            Добавить организацию
                        </v-btn>
                    </v-col>

                    <v-expansion-panel :key="i" class="no-padding-left" v-bind:id="setId(item.data.id)"
                                       v-for="(item,i) in accordions">
                        <v-expansion-panel-header v-if="item.childs.length > 0"><b>№{{ item.data.id}}</b> {{ item.data.name }}
                            <v-col cols="3">
                                <v-btn @click="add(item.data.id)" color="success" dark fab outlined x-small>
                                    <v-icon>mdi-plus</v-icon>
                                </v-btn>
                                <v-btn @click="edit(item.data.id)" color="warning" dark fab outlined x-small>
                                    <v-icon>mdi-border-color</v-icon>
                                </v-btn>
                                <v-btn @click="delete_(item.data.id)" color="error" dark fab outlined x-small>
                                    <v-icon>mdi-delete</v-icon>
                                </v-btn>
                            </v-col>
                        </v-expansion-panel-header>

                        <button class="v-expansion-panel-header v-expansion-panel-header--mousedown" type="button"
                                v-if="item.childs.length === 0">
                            <b>№{{ item.data.id}}</b> {{ item.data.name }}
                            <v-col cols="3">
                                <v-btn @click="add(item.data.id)" color="success" dark fab outlined x-small>
                                    <v-icon>mdi-plus</v-icon>
                                </v-btn>
                                <v-btn @click="edit(item.data.id)" color="warning" dark fab outlined x-small>
                                    <v-icon>mdi-border-color</v-icon>
                                </v-btn>
                                <v-btn @click="delete_(item.data.id)" color="error" dark fab outlined x-small>
                                    <v-icon>mdi-delete</v-icon>
                                </v-btn>
                            </v-col>
                        </button>

                        <v-expansion-panel-content v-if="item.childs.length > 0">

                            <v-expansion-panels accordion>

                                <v-expansion-panel :key="i" v-bind:id="setId(childs_o.data.id)"
                                                   v-for="(childs_o,i) in item.childs">

                                    <v-expansion-panel-header v-if="childs_o.childs.length > 0"><b>№{{ childs_o.data.id}}</b> {{ childs_o.data.name }}
                                        <v-col cols="3">
                                            <v-btn @click="add(childs_o.data.id)" color="success" dark fab outlined
                                                   x-small>
                                                <v-icon>mdi-plus</v-icon>
                                            </v-btn>
                                            <v-btn @click="edit(childs_o.data.id)" color="warning" dark fab outlined
                                                   x-small>
                                                <v-icon>mdi-border-color</v-icon>
                                            </v-btn>
                                            <v-btn @click="delete_(childs_o.data.id)" color="error" dark fab outlined
                                                   x-small>
                                                <v-icon>mdi-delete</v-icon>
                                            </v-btn>
                                        </v-col>
                                    </v-expansion-panel-header>

                                    <button class="v-expansion-panel-header v-expansion-panel-header--mousedown"
                                            type="button"
                                            v-if="childs_o.childs.length === 0">
                                        <b>№{{ childs_o.data.id}}</b> {{ childs_o.data.name }}
                                        <v-col cols="3">
                                            <v-btn @click="add(childs_o.data.id)" color="success" dark fab outlined
                                                   x-small>
                                                <v-icon>mdi-plus</v-icon>
                                            </v-btn>
                                            <v-btn @click="edit(childs_o.data.id)" color="warning" dark fab outlined
                                                   x-small>
                                                <v-icon>mdi-border-color</v-icon>
                                            </v-btn>
                                            <v-btn @click="delete_(childs_o.data.id)" color="error" dark fab outlined
                                                   x-small>
                                                <v-icon>mdi-delete</v-icon>
                                            </v-btn>
                                        </v-col>
                                    </button>

                                    <v-expansion-panel-content v-if="childs_o.childs.length > 0">
                                        <v-expansion-panels accordion>
                                            <v-expansion-panel :key="i" v-bind:id="setId(childs_t.data.id)"
                                                               v-for="(childs_t,i) in childs_o.childs">
                                                <v-expansion-panel-header v-if="childs_t.childs.length > 0"><b>№{{ childs_t.data.id}}</b> {{childs_t.data.name }}
                                                    <v-col cols="3">
                                                        <v-btn @click="add(childs_t.data.id)" color="success" dark
                                                               fab outlined x-small>
                                                            <v-icon>mdi-plus</v-icon>
                                                        </v-btn>
                                                        <v-btn @click="edit(childs_t.data.id)" color="warning" dark
                                                               fab outlined x-small>
                                                            <v-icon>mdi-border-color</v-icon>
                                                        </v-btn>
                                                        <v-btn @click="delete_(childs_t.data.id)" color="error" dark
                                                               fab outlined x-small>
                                                            <v-icon>mdi-delete</v-icon>
                                                        </v-btn>
                                                    </v-col>
                                                </v-expansion-panel-header>

                                                <button
                                                    class="v-expansion-panel-header v-expansion-panel-header--mousedown"
                                                    type="button"
                                                    v-if="childs_t.childs.length === 0">
                                                    <b>№{{ childs_t.data.id}}</b> {{ childs_t.data.name }}
                                                    <v-col cols="3">
                                                        <v-btn @click="add(childs_t.data.id)" color="success" dark
                                                               fab outlined x-small>
                                                            <v-icon>mdi-plus</v-icon>
                                                        </v-btn>
                                                        <v-btn @click="edit(childs_t.data.id)" color="warning" dark
                                                               fab outlined x-small>
                                                            <v-icon>mdi-border-color</v-icon>
                                                        </v-btn>
                                                        <v-btn @click="delete_(childs_t.data.id)" color="error" dark
                                                               fab outlined x-small>
                                                            <v-icon>mdi-delete</v-icon>
                                                        </v-btn>
                                                    </v-col>
                                                </button>

                                                <v-expansion-panel-content v-if="childs_t.childs.length > 0">
                                                    <v-expansion-panels accordion>
                                                        <v-expansion-panel :key="i"
                                                                           v-bind:id="setId(childs_th.data.id)"
                                                                           v-for="(childs_th,i) in childs_t.childs">
                                                            <v-expansion-panel-header
                                                                v-if="childs_th.childs.length > 0"><b>№{{ childs_th.data.id}}</b> {{childs_th.data.name }}
                                                                <v-col cols="3">
                                                                    <v-btn @click="add(childs_th.data.id)"
                                                                           color="success" dark fab
                                                                           outlined x-small>
                                                                        <v-icon>mdi-plus</v-icon>
                                                                    </v-btn>
                                                                    <v-btn @click="edit(childs_th.data.id)"
                                                                           color="warning" dark fab
                                                                           outlined x-small>
                                                                        <v-icon>mdi-border-color</v-icon>
                                                                    </v-btn>
                                                                    <v-btn @click="delete_(childs_th.data.id)"
                                                                           color="error" dark
                                                                           fab outlined x-small>
                                                                        <v-icon>mdi-delete</v-icon>
                                                                    </v-btn>
                                                                </v-col>
                                                            </v-expansion-panel-header>

                                                            <button
                                                                class="v-expansion-panel-header v-expansion-panel-header--mousedown"
                                                                type="button"
                                                                v-if="childs_th.childs.length === 0">
                                                                <b>№{{ childs_th.data.id}}</b> {{ childs_th.data.name }}
                                                                <v-col cols="3">
                                                                    <v-btn @click="add(childs_th.data.id)"
                                                                           color="success" dark fab
                                                                           outlined x-small>
                                                                        <v-icon>mdi-plus</v-icon>
                                                                    </v-btn>
                                                                    <v-btn @click="edit(childs_th.data.id)"
                                                                           color="warning" dark fab
                                                                           outlined x-small>
                                                                        <v-icon>mdi-border-color</v-icon>
                                                                    </v-btn>
                                                                    <v-btn @click="delete_(childs_th.data.id)"
                                                                           color="error" dark
                                                                           fab outlined x-small>
                                                                        <v-icon>mdi-delete</v-icon>
                                                                    </v-btn>
                                                                </v-col>
                                                            </button>
                                                            <v-expansion-panel-content
                                                                v-if="childs_th.childs.length > 0">
                                                                <v-expansion-panels accordion>
                                                                    <v-expansion-panel
                                                                        :key="i"
                                                                        v-bind:id="setId(childs_to.data.id)"
                                                                        v-for="(childs_to,i) in childs_th.childs">

                                                                        <button
                                                                            class="v-expansion-panel-header v-expansion-panel-header--mousedown"
                                                                            type="button">
                                                                            <b>№{{ childs_to.data.id}}</b> {{ childs_to.data.name }}
                                                                            <v-col cols="3">
                                                                                <v-btn @click="edit(childs_to.data.id)"
                                                                                       color="warning"
                                                                                       dark fab outlined x-small>
                                                                                    <v-icon>mdi-border-color</v-icon>
                                                                                </v-btn>
                                                                                <v-btn
                                                                                    @click="delete_(childs_to.data.id)"
                                                                                    color="error"
                                                                                    dark fab outlined x-small>
                                                                                    <v-icon>mdi-delete</v-icon>
                                                                                </v-btn>
                                                                            </v-col>
                                                                        </button>
                                                                    </v-expansion-panel>
                                                                </v-expansion-panels>
                                                            </v-expansion-panel-content>
                                                        </v-expansion-panel>
                                                    </v-expansion-panels>
                                                </v-expansion-panel-content>
                                            </v-expansion-panel>
                                        </v-expansion-panels>
                                    </v-expansion-panel-content>
                                </v-expansion-panel>
                            </v-expansion-panels>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-expansion-panels>
            </v-row>
        </v-container>

        <v-dialog max-width="600px" v-model="dialog">
            <v-card>
                <v-card-title>
                    <span class="headline">Создание записи</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12">
                                <v-text-field label="Имя*" required v-model="name_organization"></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-select
                                    :items="nameOrgs"
                                    :readonly="true"
                                    label="Организация*"
                                    v-model="organizations_select"
                                ></v-select>
                            </v-col>
                        </v-row>
                    </v-container>
                    <small>*обязательные поля</small>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn @click="dialog = false" color="blue darken-1" text>Закрыть</v-btn>
                    <v-btn @click="save" color="blue darken-1" text>Сохранить</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

    </v-app>
</template>

<script>
    export default {
        data() {
            return {
                data: [],
                nameOrgs: [],
                name_organization: "",
                organizations_select: "",
                dialog: false,
                editId: 0,
                accordions: []
            }
        },
        methods: {
            save() {
                let d = {name: this.name_organization};

                if (this.editId === 0) {
                    let parent_id = 0;

                    $.each(this.data, (i, v) => {
                        if (v.name === this.organizations_select) {
                            parent_id = v.id;
                        }
                    });

                    if (parent_id !== 0) {
                        d.parent_id = parent_id;
                    }

                    api.dictionary.organizations().post(d).then(response => {
                        location.reload();
                    }).catch(error => {

                    });
                } else {
                    api.dictionary.organizations(this.editId).put(d).then(response => {
                        this.loadOrganizations();
                        this.editId = 0;
                        this.name_organization = '';
                        this.organizations_select = '';
                        this.dialog = false;
                        if (response.status) {
                            window.Vue.notify({
                                group: 'success',
                                title: 'Запись успешно обновлена',
                            });
                        } else {
                            window.Vue.notify({
                                group: 'warning',
                                title: 'Ошибка при обновлении записи',
                            });
                        }
                    }).catch(error => {
                        window.Vue.notify({
                            group: 'error',
                            title: 'Ошибка при обновлении записи',
                        });
                    });
                }
            },
            setId(id) {
                return 'organization-' + id;
            },
            edit(id) {
                let loader = this.$loading.show({
                    color: '#0c51b6'
                });

                let t = this;
                this.editId = id;

                setTimeout(function () {
                    api.dictionary.organizations(id).get().then(response => {
                        loader.hide();
                        if (response.status) {
                            t.name_organization = response.data.name;
                            t.add(response.data.parent_id === null ? 0 : response.data.parent_id);
                        } else {
                            window.Vue.notify({
                                group: 'warning',
                                title: 'Ошибка при возвращении записи',
                            });
                        }
                    }).catch(error => {
                        loader.hide();
                        console.error(error);
                        window.Vue.notify({
                            group: 'error',
                            title: 'Ошибка при возвращении записи',
                        });
                    });
                }, 500);
            },
            delete_(id) {
                if (confirm('Вы хотите удалить эту организацию ?')) {

                    api.dictionary.organizations(id).delete().then(response => {
                        this.loadOrganizations();
                        if (response.status) {
                            window.Vue.notify({
                                group: 'success',
                                title: 'Запись успешно удалена',
                            });
                        } else {
                            window.Vue.notify({
                                group: 'warning',
                                title: 'Ошибка при удалении записи',
                            });
                        }
                    }).catch(error => {
                        console.error(error);
                        window.Vue.notify({
                            group: 'error',
                            title: 'Ошибка при удалении записи',
                        });
                    });
                }
            },
            add(id) {
                this.organizations_select = id === 0 ? '' : this.data[id].name;
                this.dialog = true;
            },
            getSequenceIds(v, data) {
                let structure_id = 0;
                let start_parent_id = v.parent_id;
                let array_sequence = [];
                do {
                    array_sequence.push(start_parent_id);
                    if (data[start_parent_id].parent_id === null) {
                        structure_id = data[start_parent_id].id;
                    } else {
                        start_parent_id = data[start_parent_id].parent_id;
                    }

                } while (structure_id === 0);

                return array_sequence.reverse();
            },

            loadOrganizations() {
                let loader = this.$loading.show({
                    color: '#0c51b6'
                });
                api.dictionary.organizations().get({
                    limit: 10000,
                    paginator: false,
                    order: JSON.stringify(['id', 'ASC'])
                }).then(response => {
                    if (response.data.length > 0) {
                        let structure = {};

                        let data = {};
                        let nameOrgs = [];

                        response.data.map(v => {
                            data[v.id] = v;
                            nameOrgs.push(v.name);
                        });

                        response.data.map(v => v.parent_id === null ? structure[v.id] = {} : '');

                        response.data.map(v => {
                            if (v.parent_id !== null) {
                                if (typeof structure[v.parent_id] !== "undefined") {
                                    structure[v.parent_id][v.id] = {};
                                } else {
                                    let array__ = this.getSequenceIds(v, data);
                                    let s = true;
                                    let i = 0;
                                    let str = structure[array__[0]];
                                    do {
                                        i++;
                                        if (i === array__.length - 1) s = false;
                                        str = str[array__[i]];
                                        if (str !== undefined) {
                                            if (array__[i] === v.parent_id) {
                                                str[v.id] = {};
                                            }
                                        }
                                    } while (s);
                                }
                            }
                        });
                        let array = [];
                        $.each(structure, (i, v) => {
                            let ikey = array.push({data: data[i], childs: []});
                            $.each(v, (a, d) => {
                                let lvl_o = {data: data[a], childs: []};
                                $.each(d, (f, c) => {
                                    let fkey = lvl_o.childs.push({data: data[f], childs: []});
                                    $.each(c, (g, e) => {
                                        let gkey = lvl_o.childs[fkey - 1].childs.push({data: data[g], childs: []});
                                        $.each(e, (l, o) => {
                                            lvl_o.childs[fkey - 1].childs[gkey - 1].childs.push({
                                                data: data[l],
                                                childs: []
                                            });
                                        });
                                    });
                                });

                                array[ikey - 1].childs.push(lvl_o)
                            });
                        });

                        this.accordions = array;

                        this.data = data;

                        this.nameOrgs = nameOrgs;
                    }

                    loader.hide();
                }).catch(error => {
                    console.error(error);
                    window.Vue.notify({
                        group: 'error',
                        title: 'Ошибка загрузки структуры организации',
                    });
                    loader.hide();
                });
            }
        },

        created() {
            this.loadOrganizations();
        }
    }
</script>

<style scoped>
    b {
        font-weight: 900;
        margin-right: 15px;
    }
</style>
