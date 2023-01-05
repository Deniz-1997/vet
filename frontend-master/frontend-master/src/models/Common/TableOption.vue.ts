import {constructByInterface} from "@/utils/construct-by-interface";


export interface TableOptionDataVueInterface {
    type?: string;
    placeholder: string;
}

export interface TableOptionVueInterface {
    type: string;
    value: string;
    width?: string | undefined;
    maxWidth?: string | undefined;

    data: TableOptionDataVueInterface;
}

export class TableOptionVueModel implements TableOptionVueInterface {
    type: string = '';
    value: string = '';
    width?: string | undefined = '';
    maxWidth?: string | undefined = '';

    data!: TableOptionDataVueInterface;

    constructor(o?) {
        constructByInterface(o, this);
    }
}
