import { Mapper } from "@/utils";
import { IMonitoringListFias } from "@/services/models/monitoringLoadFias";
import { EMonitoringResult } from "@/services/enums/monitoring";

export class MonitoringLoadFiasItem extends Mapper<IMonitoringListFias> {
  static readonly ResultToName = {
    [EMonitoringResult.COMPLETE]: 'Завершено',
  };

  @Mapper.catch()
  get started_at() {
    return this.get(({ started_at }) => started_at).value;
  }

  @Mapper.catch()
  get finished_at() {
    return this.get(({ finished_at }) => finished_at).value;
  }

  @Mapper.catch()
  get file_name() {
    return this.get(({ file_name }) => file_name).value;
  }

  @Mapper.catch()
  get dictionary_name() {
    return this.get(({ dictionary_name }) => dictionary_name).value;
  }

  @Mapper.catch()
  get zip_file_name() {
    return this.get(({ zip_file_name }) => zip_file_name).value;
  }

  @Mapper.catch()
  get result() {
    return this.get(({ result }) => this.$mapStatus(result)).required.value;
  }

  private $mapStatus(status: EMonitoringResult) {
    return MonitoringLoadFiasItem.ResultToName[status] || '-';
  }

  toJSON() {
    return {
      started_at: this.started_at,
      finished_at: this.finished_at,
      dictionary_name: this.dictionary_name,
      zip_file_name: this.zip_file_name,
      result: this.result,
    };
  }
}
