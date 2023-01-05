import {constructByInterface} from "@/utils/construct-by-interface";
import {DocsTransportsVueModel} from "@/models/Sdiz/DocsTransports.vue";
import {numberThousandsMask} from "@/components/common/inputs/mask/numberThousandsMask";
import {LotGpbDataVueModel} from "@/models/Lot/Data/LotGpbData.vue";
import {LotDataVueModel} from "@/models/Lot/Data/LotData.vue";

export interface AmountUnlocksVueInterface {
    id: number;
    full_use: boolean;
    lot_number: string;
    sdiz_number: string;
    amount_note: string;
    operation_date: number;
    createGpbId: number | null;
    create_lot_id: number | null;
    is_canceled: boolean;

    amount_kg_full: number;
    amount_kg_full_mask: string;

    amount_kg_lock: number;
    amount_kg_lock_mask: string;

    amount_kg_unlock: number;
    amount_kg_unlock_mask: string;

    amount_reason_id: number;

    amount_transport_id: DocsTransportsVueModel[];

    lot: LotDataVueModel;
    gpb: LotGpbDataVueModel;
    amount_reason: AmountReason;
}

export class AmountReason{
     name: string = '-';
}

export class AmountUnlocksVueModel implements AmountUnlocksVueInterface {
    id!: number;
    full_use!: boolean;
    lot_number: string = '-';
    sdiz_number: string = '-';
    amount_note: string = '-'

    is_canceled: boolean = false;
    createGpbId: number | null = null;
    create_lot_id: number | null = null;

    operation_date: number = 0;

    amount_kg_full: number = 0;
    amount_kg_full_mask: string = '';

    amount_kg_lock: number = 0;
    amount_kg_lock_mask: string = '';

    amount_kg_unlock: number = 0;
    amount_kg_unlock_mask: string = '';

    amount_reason_id: number = 0;

    lot: LotDataVueModel = new LotDataVueModel();
    gpb: LotGpbDataVueModel = new LotGpbDataVueModel();
    amount_reason: AmountReason = new AmountReason();
    amount_transport_id: DocsTransportsVueModel[] = [];

    constructor(o?: AmountUnlocksVueInterface) {
        if(o !== undefined){
            constructByInterface(o, this, {gpb: LotGpbDataVueModel, lot: LotDataVueModel});
        this.amount_kg_full_mask = numberThousandsMask(this.amount_kg_full)[0];
        this.amount_kg_lock_mask = numberThousandsMask(this.amount_kg_lock)[0];
        this.amount_kg_unlock_mask = numberThousandsMask(this.amount_kg_unlock)[0];

        }
    }

}
