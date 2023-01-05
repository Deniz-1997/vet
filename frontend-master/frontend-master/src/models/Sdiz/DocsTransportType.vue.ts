import {constructByInterface} from "@/utils/construct-by-interface";

export class DocsTransportsTypeVueInterface {
    label?: string | null;
    value?: string | number | null;
}

export class DocsTransportsTypeVueModel implements DocsTransportsTypeVueInterface {
    label: string | null = null;
    value: string | number | null = null;

    constructor(o?: DocsTransportsTypeVueInterface) {
        constructByInterface(o, this);
    }
}
