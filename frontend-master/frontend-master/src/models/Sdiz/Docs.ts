import {constructByInterface} from "@/utils/construct-by-interface";
import * as moment from "moment";

export interface DocsInterface {
    date: moment.Moment | Date | string | number | (number | string)[] | moment.MomentInputObject | null;
    type: any;
    number: string | null;
    type_id: number | null;
    sdiz_number?: string | null;
}

export class DocsModel implements DocsInterface {
    date: moment.Moment | Date | string | number | (number | string)[] | moment.MomentInputObject | null = null;
    type: string | null = null;
    number: string | null = null;
    type_id: number | null = null;
    sdiz_number?: string | null = null;

    constructor(o?: DocsInterface) {
        constructByInterface(o, this);
    }
}
