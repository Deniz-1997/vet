import Vue from 'vue'
import Component from 'vue-class-component'

@Component
export class ActionsMix extends Vue {

    createLink!: string;

    deleteLink!: string;

    updateLink!: string;

    afterDeletePush!: string;

    afterCreatePush!: string;

    afterUpdatePush!: string;

    loadingActionButton: boolean = false;

    /**
     * @param id
     */
    async deleteActionMix(id: number | null): Promise<void> {
        this.validation('delete');

        const {status} = await this.$store.dispatch(this.deleteLink, {id: id});

        if (!status) {
            this.$store.commit('errors/setErrorsList', "500: Ошибка загрузки данных, попробуйте позже.");
        }

        this.$router.push({name: this.afterDeletePush});
    }

    /**
     * @param id
     * @param data
     * @param callback
     */
    async updateActionMix(id: number | null, data: any, callback: any): Promise<void> {
        this.validation('update');
        const {response, status} = await this.$store.dispatch(this.updateLink, {data: data, id: id});

        if (!status) {
            this.$store.commit('errors/setErrorsList', "500: Ошибка загрузки данных, попробуйте позже.");
        }

        callback(response);
    }

    /**
     * @param data
     */
    async createActionMix(data): Promise<void> {
        this.validation('create');

        const {response, status} = await this.$store.dispatch(this.createLink, data);

        if (!status) {
            this.$store.commit('errors/setErrorsList', "500: Ошибка загрузки данных, попробуйте позже.");
        }

        this.$router.push({name: this.afterCreatePush, params: {id: response.id}});
    }

    /**
     * @param type
     */
    validation(type: string): void {
        const {deleteLink, updateLink, createLink, afterCreatePush, afterUpdatePush, afterDeletePush} = this;
        switch (type) {
            case 'delete':
                if (typeof deleteLink === 'undefined') {
                    this.$store.commit('errors/setErrorsList', "0001: Недостаточно данных.");
                    throw new Error('Init var "deleteLink"');
                }

                if (typeof afterDeletePush === 'undefined') {
                    this.$store.commit('errors/setErrorsList', "0001: Недостаточно данных.");
                    throw new Error('Init var "afterDeletePush"');
                }
                break;

            case 'update':
                if (typeof updateLink === 'undefined') {
                    this.$store.commit('errors/setErrorsList', "0001: Недостаточно данных.");
                    throw new Error('Init var "updateLink"');
                }

                if (typeof afterUpdatePush === 'undefined') {
                    this.$store.commit('errors/setErrorsList', "0001: Недостаточно данных.");
                    throw new Error('Init var "afterUpdatePush"');
                }
                break;

            case 'create':
                if (typeof createLink === 'undefined') {
                    this.$store.commit('errors/setErrorsList', "0001: Недостаточно данных.");
                    throw new Error('Init var "createLink"');
                }

                if (typeof afterCreatePush === 'undefined') {
                    this.$store.commit('errors/setErrorsList', "0001: Недостаточно данных.");
                    throw new Error('Init var "afterCreatePush"');
                }
                break;
        }
    }
}
