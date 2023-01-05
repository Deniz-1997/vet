import {constructByInterface} from "@/utils/construct-by-interface";

export interface PaginationInterface {
    totalResults: number;
    current: number;
    last: number;
}

export class PaginationModel implements PaginationInterface {
    totalResults: number = 0;
    current: number = 0;
    last: number = 0;

    constructor(o?: PaginationInterface) {
        constructByInterface(o, this);
    }
}