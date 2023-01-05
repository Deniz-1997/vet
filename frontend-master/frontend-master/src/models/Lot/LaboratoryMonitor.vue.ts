import {constructByInterface} from "@/utils/construct-by-interface";

export interface LaboratoryMonitorVueInterface {
    laboratory_monitor_number: string | null;
}

export class LaboratoryMonitorVueModel implements LaboratoryMonitorVueInterface {
    laboratory_monitor_number: string | null = null;

    constructor(o?: LaboratoryMonitorVueInterface) {
        constructByInterface(o, this);
    }
}