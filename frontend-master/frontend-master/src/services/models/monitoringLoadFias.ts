import { EMonitoringResult } from "@/services/enums/monitoring";

/** Элемент списка Мониторинга загрузки ФИАС. */
export interface IMonitoringListFias {
    started_at?: string;
    finished_at?: string;
    file_name?: string;
    dictionary_name?: string;
    zip_file_name?: string;
    result: EMonitoringResult;
}
