import {constructByInterface} from "@/utils/construct-by-interface";

export interface ConsigneeVueInterface {
    inn: number | null;
    kpp: number | null;
    name: string | null;
    subject_id: number | null;
    address_text: string | null;
    repository: string | null;
    registration_number: string | null;
}

export class ConsigneeVueModel implements ConsigneeVueInterface {
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
