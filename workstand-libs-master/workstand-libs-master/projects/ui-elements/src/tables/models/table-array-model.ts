import {HeaderReportSettingModel} from './header-report-setting-model';
import {ColReportSettingModel} from './col-report-setting-model';
import {FormGroup} from '@angular/forms';
import {OptionsForSelect} from './report-model';

export interface StubTableInterface {
  countCols: number;
  stub: Array<{ nameCol: string, value: string }>;
}

export class TableArrayModel {
  title!: string;
  formName!: string;
  isAddRow?: boolean;
  useFxFlex?: boolean;
  showFooter?: boolean;
  useStubCols!: boolean;
  type!: 'mat' | 'table' | 'blocky';
  fxFlex!: string;
  fxFlexMd!: string;
  maxWidthInput!: string;
  showTotalRow!: boolean;
  stubCols!: StubTableInterface;

  headers!: HeaderReportSettingModel[];

  form!: () => FormGroup;

  rows!: ColReportSettingModel[];

  columns!: ColReportSettingModel[];

  indexRowOfColumns?: number;
  getOptionsForSelect?: (val: any) => OptionsForSelect[];
  setTotalRowInFooterRow?: (val: any, col: any) => string | number;
  fxLayout?: string | boolean;
}
