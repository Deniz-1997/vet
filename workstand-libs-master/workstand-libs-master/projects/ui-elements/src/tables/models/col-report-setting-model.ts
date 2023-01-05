export interface SizeColsInterfaces {
  cols?: number | undefined;
  md?: number | undefined;
  lg?: number | undefined;
  xl?: number | undefined;
  sm?: number | undefined;
  fxFlex?: string | number | undefined;
}

export enum ReportSettingType {
  TEXT, INPUT_TEXT, INPUT_NUMBER, CRUD, TITLE, INPUT_DATE, EMPTY_ROW, AUTOCOMPLETE
}

export interface ValidationColReportInterface {
  min?: number;
  max?: number;
  required?: boolean;
  requiredTrue?: boolean;
  email?: string;
  minLength?: number;
  maxLength?: number;
  pattern?: string;
}

export interface AutoFillInterface {
  autocompleteFields: Array<string | any>,
  reportFields: Array<string>
}

export class ColReportSettingModel {
  public name!: string;
  public placeholder?: string;
  public value?: string | number;
  public headerName?: string;

  public style?: object; // {'display': 'block', 'max-width': '300px'}
  public size?: SizeColsInterfaces;
  public type!: ReportSettingType;
  public crudType?: string;
  public validateRules?: ValidationColReportInterface;
  public autofill?: AutoFillInterface;

  public position?: number;
  public disabled?: boolean;
  public readonly?: boolean;
  public required?: boolean;

}
