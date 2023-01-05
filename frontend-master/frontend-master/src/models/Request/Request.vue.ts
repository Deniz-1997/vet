import {constructByInterface} from "@/utils/construct-by-interface";
import {FilterRequestInterface} from "@/models/Request/Filter.vue";

export interface SortOptionInterface {
    field: string;
    direction: string;
}

export interface SortInterface {
    options: SortOptionInterface[]
}

export interface SortOptionInterface {
    field: string;
    direction: string;
}

export interface RequestInterface {
    page_size: number;
    page: number;
    last: number;
    filter: FilterRequestInterface;
    sort: SortInterface;
}

export class RequestModel implements RequestInterface {
    page_size: number = 5;
    page: number = 1;
    last: number = 0;
    filter: FilterRequestInterface = {options: []};
    sort: SortInterface = {
        options: [
            {field: 'id', direction: 'DESC'},
        ]
    };

    constructor(o?: RequestInterface) {
        constructByInterface(o, this);
    }
}
