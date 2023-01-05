import {constructByInterface} from "@/utils/construct-by-interface";

export interface BuyerVueInterface {
    inn: number | null;
    kpp: number | null;
    name: string | null;
    registration_number: string | null;
}

export class BuyerVueModel implements BuyerVueInterface {
    inn: number | null = null;
    kpp: number | null = null;
    name: string | null = '-';
    registration_number: string | null = null;

    constructor(o?) {
        constructByInterface(o, this);
    }
}
