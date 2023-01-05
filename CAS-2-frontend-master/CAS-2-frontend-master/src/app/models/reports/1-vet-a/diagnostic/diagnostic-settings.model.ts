import {ColReportSettingModel, HeaderReportSettingModel, ReportModel, ReportSettingType} from '@korvet/ui-elements';
import {getDiagnosticStubCols} from '../animal-disease-list-diagnostic';

export class Vet1DiagnosticSettingsModel extends ReportModel {
  public readonly showFooter: boolean = false;
  public readonly showTotalRow = false;
  public readonly type = 'blocky';
  public readonly fxFlex = '100%';
  public readonly formName: string = '1-vet-a-diagnostic';
  public readonly headers: Array<HeaderReportSettingModel> = [
    {
      title: 'Вид животных - Наименование исследования',
      name: 'name',
    },
    {
      title: 'Исследовано животных, голов',
      name: 'researchCount',
    },
    {
      title: 'Реагировало положительно, голов',
      name: 'researchPositive',
    },
  ];

  public readonly columns: Array<ColReportSettingModel> = [
    {
      name: 'name',
      type: ReportSettingType.TITLE,
      size: {
        fxFlex: ''
      }
    },
    {
      name: 'researchCount',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
      size: {
        fxFlex: ''
      }
    },
    {
      name: 'researchPositive',
      type: ReportSettingType.INPUT_NUMBER,
      validateRules: {
        required: true,
      },
      size: {
        fxFlex: ''
      }
    },
  ];

  public readonly useStubCols: boolean = true;

  public readonly stubCols:
  { countCols: number, stub: Array<{ nameCol: string, value: string, disabled?: boolean }> } = getDiagnosticStubCols();
}
