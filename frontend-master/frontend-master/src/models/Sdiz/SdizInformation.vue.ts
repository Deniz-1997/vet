import {constructByInterface} from "@/utils/construct-by-interface";

export interface SdizInformationVueDetailInterface {
    disabledRealization: Boolean;
    disabledInformation: Boolean;
    shipment: Boolean;
    shipping: Boolean;
    acceptance: Boolean;
    realization: Boolean;
}

export interface SdizInformationVueInterface {
    sdiz_type: number;
    types: Array<string>;
    inf: SdizInformationVueDetailInterface;
}

export class SdizInformationVueModel implements SdizInformationVueInterface {
    sdiz_type: number = 0;
    types: Array<string> = [];
    inf: SdizInformationVueDetailInterface = {
        disabledRealization: true,
        disabledInformation: true,
        shipment: false,
        shipping: false,
        acceptance: false,
        realization: false
    };

    constructor(o?) {

        if (o !== undefined) {
            constructByInterface(o, this);
            let count_sdiz_type = this.sdiz_type;

            //Реализация
            if (count_sdiz_type >= 1000) {
                count_sdiz_type -= 1000;
                this.inf.disabledRealization = false;
                this.inf.realization = true;
                this.types.push('Реализация');
            }

            //Приемка
            if (count_sdiz_type >= 100) {
                count_sdiz_type -= 100;
                this.inf.disabledInformation = false;
                this.inf.acceptance = true;
                this.types.push('Приемка');
            }

            //Перевозка
            if (count_sdiz_type >= 10) {
                count_sdiz_type -= 10;
                this.inf.disabledInformation = false;
                this.inf.shipping = true;
                this.types.push('Перевозка');
            }

            //Отгрузка
            if (count_sdiz_type >= 1) {
                this.inf.disabledInformation = false;
                this.inf.shipment = true;
                this.types.push('Отгрузка');
            }
        }
    }
}