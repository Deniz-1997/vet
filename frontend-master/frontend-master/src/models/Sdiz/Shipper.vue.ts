import {constructByInterface} from "@/utils/construct-by-interface";

export interface ShipperVueInterface {
    inn: number | null;
    kpp: number | null;
    name: string | null;
    address_text: string | null;
    repository: string | null;
    subject_id: number | null;
    registration_number: string | null;
}

export class ShipperVueModel implements ShipperVueInterface {
    inn: number | null = null;
    kpp: number | null = null;
    name: string | null = '-';
    subject_id: number | null = null;
    address_text: string | null = null;
    repository: string | null = null;
    registration_number: string | null = null;

    constructor(o?) {
        constructByInterface(o, this);
    }
}
