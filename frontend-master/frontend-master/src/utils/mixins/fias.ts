import Vue from 'vue'
import Component from 'vue-class-component'
import {AddressFiasVueModel} from "@/models/Gosmonitoring/AddressFias.vue";

@Component
export class FiasMix extends Vue {

    addressPlace: Array<object> = [];

    loadingAddressPlace: boolean = false;

    hideNoDataAddressPlace: boolean = true;

    hintOfAddress: string = 'Введите наименование от 2х символов';

    get getAddressListMix(): Array<any> {
        return this.$store.state.fias.addressObjects;
    }

    /**
     * @return void
     */
    async loadAddressMix(query: any = {name: '', guid: ''}): Promise<void> {
        const data: {countryId?: number, parentGuid?: string} = {countryId: 1, parentGuid: ""};

        if(query.guid !== ''){
            data.parentGuid = query.guid;
        }

        const address = await this.$store.dispatch('fias/findAddress', data);

        if(address !== null){
            this.hideNoDataAddressPlace = false;
            this.addressPlace = address.content;
            this.$store.commit('fias/changeAddress', this.addressPlace);
        }
    }

    /**
     * @return void
     */
    async getAddressByGuidMix(guid: any = {name: '', guid: ''}){
        const el =  this.getAddressListMix.filter(v => v.guid === guid.guid);

        if(el.length > 0){
            return el[0];
        } else{
            return await this.$store.dispatch('fias/getAddress', guid);
        }
    }

    /**
     * @param guid
     * @param arrayName
     * @return void
     */
    async searchChildrenForGuid(guid: string, arrayName: string | undefined = undefined): Promise<void> {
        const array: any = arrayName === undefined? this.getAddressListMix : this[arrayName];

        if(guid === null){
            await this.loadAddressMix();

            if(arrayName !== undefined){
                if(this[arrayName] !== undefined && this[arrayName].length > 0){
                    this[arrayName] = this.getAddressListMix;
                }
            }
        } else {
            const el = array.filter(v => v.guid === guid);
            if (el.length > 0) {
                const {type, guid} = el[0];
                if (type !== 'HOUSE') {
                    let {fiasObjects} = await this.$store.dispatch('fias/getChild', {parentGuid: guid});
                    if (typeof fiasObjects !== "undefined") {
                        fiasObjects.unshift(el[0]);
                        this.hideNoDataAddressPlace = false;
                        this.addressPlace = fiasObjects;

                        if(arrayName !== undefined){
                            if(this[arrayName] !== undefined && this[arrayName].length > 0){
                                this[arrayName] = fiasObjects;
                            }
                        }

                        this.$store.commit('fias/changeAddress', this.addressPlace);
                    }
                }
            }
        }
    }
}
