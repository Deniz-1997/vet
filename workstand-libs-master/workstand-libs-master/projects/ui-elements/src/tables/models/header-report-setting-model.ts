import {ColReportSettingModel} from './col-report-setting-model';

export class HeaderReportSettingModel {
  public name?: string;
  public title?: string;
  public colsName?: Array<string>;
  public columns?: ColReportSettingModel | ColReportSettingModel[];
  public class?: string;
  public level?: number;
  public colspan?: number;
  public rowspan?: number;
}
