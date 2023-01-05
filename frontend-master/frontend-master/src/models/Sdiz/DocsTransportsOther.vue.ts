import {constructByInterface} from "@/utils/construct-by-interface";

export interface DocsTransportsOtherVueInterface {
    type: string;
    number: string;
    date: string;
}

export class DocsTransportsOtherVueModel implements DocsTransportsOtherVueInterface {
    date: string = '-';
    type: string = '-';
    number: string = '-';

    constructor(o?: DocsTransportsOtherVueModel) {
        constructByInterface(o, this);
    }
}
