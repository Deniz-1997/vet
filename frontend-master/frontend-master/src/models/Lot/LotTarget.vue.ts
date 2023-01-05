import {constructByInterface} from "@/utils/construct-by-interface";

export interface LotTargetVueInterface {
    code: number;
    name: string;
    start_date: string;
    finish_date: string;
}

export class LotTargetVueModel implements LotTargetVueInterface {
    code!: number;
    name!: string;
    start_date!: string;
    finish_date!: string;

    constructor(o?: LotTargetVueInterface) {
        constructByInterface(o, this);
    }
}
