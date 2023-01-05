import {ColReportSettingModel, HeaderReportSettingModel, ReportModel, ReportSettingType} from '@korvet/ui-elements';
import {getEventStubCols} from '../animal-disease-list-events';

export class Vet1EventsSettingsModel extends ReportModel {
  public readonly showFooter: boolean = false;
  public readonly showTotalRow = false;
  public readonly fxFlex = '100%';
  public readonly type = 'blocky';
  public readonly useFxFlex = true;
  public readonly formName: string = '1-vet-a-events';
  public readonly headers: Array<HeaderReportSettingModel> = [
    {
      title: 'Вид животных - Наименование исследования',
      name: 'name'
    },
    {
      title: 'Обработано-всего, голов',
      name: 'totalCount'
    },
  ];
  public readonly columns: Array<ColReportSettingModel> = [
    {
      name: 'name',
      type: ReportSettingType.TITLE,
      size: {
        fxFlex: '20',
      }
    },
    {
      name: 'totalCount',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
      size: {
        fxFlex: '',
      }
    },
  ];

  public readonly stubCols:
    { countCols: number, stub: Array<{ nameCol: string, value: string, disabled?: boolean }> } = getEventStubCols();

  public readonly useStubCols: boolean = true;
}
