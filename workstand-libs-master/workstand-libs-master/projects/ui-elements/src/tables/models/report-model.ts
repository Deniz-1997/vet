import {HeaderReportSettingModel} from './header-report-setting-model';
import {ColReportSettingModel, ReportSettingType} from './col-report-setting-model';
import {TableArrayModel} from './table-array-model';
import {FormControl, FormGroup, Validators} from '@angular/forms';

export interface ReportInterface {
  title: string;
  indexRowOfColumns: number;
  headers: HeaderReportSettingModel[];
  columns: ColReportSettingModel[];
}

export interface OptionsForSelect {
  value: string | number;
  viewValue: string;
}
export class ReportModel implements ReportInterface {

  public readonly title!: string;

  public readonly formName!: string;

  public readonly isAddRow: boolean = false;

  public readonly useFxFlex: boolean = false;

  public readonly showFooter: boolean = false;

  public readonly useStubCols: boolean = false;

  public readonly showTotalRow: boolean = false;

  public readonly stubCols!: { countCols: number, stub: { nameCol: string, value: string }[] };

  public readonly headers!: HeaderReportSettingModel[];

  public readonly columns!: ColReportSettingModel[];

  public readonly type: any;

  public optionsForSelect: OptionsForSelect[] = [];

  public fxFlex = '100%';

  public fxFlexMd = '50%';

  public maxWidthInput = '300px';

  public indexRowOfColumns = 0;

  public fxLayout: boolean | string = 'top';

  private excludeColumns = [
    'position',
    'name',
  ];

  getDataForTable(): TableArrayModel {
    return {
      ...this,
      rows: this.getRows(),
      form: this.getForm,
      getOptionsForSelect: (val): OptionsForSelect[] => {
        const optionsForSelect = [];
        optionsForSelect.push({value: 1, viewValue: 'Выбор 1'});
        optionsForSelect.push({value: 2, viewValue: 'Выбор 2'});
        optionsForSelect.push({value: 3, viewValue: 'Выбор 3'});
        optionsForSelect.push({value: 4, viewValue: 'Выбор 4'});
        optionsForSelect.push({value: 5, viewValue: 'Выбор 5'});
        return optionsForSelect;
      },
      setTotalRowInFooterRow: (val, col): string | number => col.name === 'name' ? 'Итого' : val.map((a:any) => a[col.name]).reduce((a:any, b:any) => Number(a) + Number(b), 0),
    };
  }

  getForm(): FormGroup {
    const formCols = new FormGroup({});
    this.columns.forEach(col => {
      if (this.excludeColumns.find(c => c === col.name) === undefined) {
        const {name, validateRules, type, value} = col;

        if (type !== ReportSettingType.EMPTY_ROW) {
          if (name === undefined) {
            throw new Error('Not found column ' + col);
          }
          const validateOpts = [];

          if (typeof validateRules !== 'undefined') {
            const {min, max, required, requiredTrue, email, minLength, maxLength, pattern} = validateRules;

            if (required) {
              validateOpts.push(Validators.required);
            }

            if (max) {
              validateOpts.push(Validators.max(max));
            }

            if (min) {
              validateOpts.push(Validators.min(min));
            }
          }

          formCols.addControl(name, new FormControl(value ?? null, validateOpts));
        }
      }
    });
    return formCols;
  }

  getRows(): any {
    const cols = [];

    if (this.useStubCols) {

      if (this.stubCols.stub.length <= 0) {
        throw new Error('Var "useStubCols" is true. Need to add elements in array of stub');
      }

      if (this.stubCols.countCols <= 0) {
        throw new Error('CountCols must be greater than 0');
      }

      for (let i = 0; i < this.stubCols.countCols; i++) {
        const {nameCol, value} = this.stubCols.stub[i];
        const contentCols: any = {position: i};
        this.columns.forEach(({name}) => {
          if (typeof contentCols[name] === 'undefined') {
            contentCols[name] = name === nameCol ? value : '';
          }
        });

        cols.push(contentCols);
      }
      return cols;
    }

    return [];
  }
}
