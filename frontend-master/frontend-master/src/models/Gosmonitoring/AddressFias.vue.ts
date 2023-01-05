import {constructByInterface} from "@/utils/construct-by-interface";

export interface AddressFiasVueInterface {
    // actstatus: number | null;
    aolevel: number | null;
    // centstatus: number | null;
    // currstatus: number | null;
    // divtype: number | null;
    // livestatus: number | null;
    operstatus: number | null;

    // actual_status_name: string | null;
    address: string | null;
    addressPartition: string | null;
    address_object_type_name: string | null;
    aoguid: string | null;
    aoid: string | null;
    areacode: string | null;
    autocode: string | null;
    citycode: string | null;
    code: string | null;
    // ctarcode: string | null;
    current_status_name: string | null;
    enddate: string | null;
    // extrcode: string | null;
    name: string | null;
    guid: string | null;
    // ifnsfl: string | null;
    // ifnsul: string | null;
    // offname: string | null;
    okato: string | null;
    oktmo: string | null;
    operation_status_name: string | null;
    placecode: string | null;
    // plaincode: string | null;
    plancode: string | null;
    postalcode: string | null;
    previd: string | null;
    regioncode: string | null;
    // sextcode: string | null;
    shortname: string | null;
    startdate: string | null;
    streetcode: string | null;
    type: string | null;
    updatedate: string | null;
    center_status_name: null;
    nextid: null;
    // normdoc: null;
    parentGuid: null;
    parentguid: null;
    previous: null;
    // terrifnsfl: null;
    // terrifnsul: null;
}

export class AddressFiasVueModel implements AddressFiasVueInterface {
    // actstatus: number | null = null;
    aolevel: number | null = null;
    // centstatus: number | null = null;
    // currstatus: number | null = null;
    // divtype: number | null = null;
    // livestatus: number | null = null;
    operstatus: number | null = null;

    // actual_status_name: string | null = null;
    address: string | null = '-';
    addressPartition: string | null = null;
    address_object_type_name: string | null = null;
    aoguid: string | null = null;
    aoid: string | null = null;
    areacode: string | null = null;
    autocode: string | null = null;
    citycode: string | null = null;
    code: string | null = null;
    // ctarcode: string | null = null;
    current_status_name: string | null = null;
    enddate: string | null = null;
    // extrcode: string | null = null;
    name: string | null = null;
    guid: string | null = null;
    // ifnsfl: string | null = null;
    // ifnsul: string | null = null;
    // offname: string | null = null;
    okato: string | null = null;
    oktmo: string | null = null;
    operation_status_name: string | null = null;
    placecode: string | null = null;
    // plaincode: string | null = null;
    plancode: string | null = null;
    postalcode: string | null = null;
    previd: string | null = null;
    regioncode: string | null = null;
    // sextcode: string | null = null;
    shortname: string | null = null;
    startdate: string | null = null;
    streetcode: string | null = null;
    type: string | null = null;
    updatedate: string | null = null;
    center_status_name: null = null;
    nextid: null = null;
    // normdoc: null = null;
    parentGuid: null = null;
    parentguid: null = null;
    previous: null = null;
    // terrifnsfl: null = null;
    // terrifnsul: null = null;

    constructor(o?) {
        if (o !== undefined) {
            if (typeof o.place_of_checking_id !== 'undefined') {
            } else {
                constructByInterface(o, this);
            }
        }
    }
}
