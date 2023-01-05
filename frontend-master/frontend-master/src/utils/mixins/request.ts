import Vue from 'vue'
import Component from 'vue-class-component'
import {RequestModel} from "@/models/Request/Request.vue";
import {PaginationModel} from "@/models/Request/Pagination";
import {Pageable} from "@/models/Request/Request.types";

@Component
export class RequestMix extends Vue {

    total: number = 0;

    pageable: Pageable = {pageSize: 10, pageNumber: 0};

    request: RequestModel = new RequestModel();

    pagination: PaginationModel = new PaginationModel();

}
