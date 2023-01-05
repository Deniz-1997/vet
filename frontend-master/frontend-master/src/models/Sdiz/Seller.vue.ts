import {constructByInterface} from "@/utils/construct-by-interface";

export interface SellerVueInterface {
    inn: number | null;
    kpp: number | null;
    name: string | null;
    registration_number: string | null;
}

export class SellerVueModel implements SellerVueInterface {
    inn: number | null = null;
    kpp: number | null = null;
    name: string | null = '-';
    registration_number: string | null = null;

    constructor(o?) {
        constructByInterface(o, this);
    }
}
