import {constructByInterface} from "@/utils/construct-by-interface";

export interface DocsOtherVueInterface {
    type: string;
    number: string;
    date: string;
}

export class DocsOtherVueModel implements DocsOtherVueInterface {
    date: string = '-';
    number: string = '-';
    type: string = '-'

    constructor(o?: DocsOtherVueModel) {
        constructByInterface(o, this);
    }
}
