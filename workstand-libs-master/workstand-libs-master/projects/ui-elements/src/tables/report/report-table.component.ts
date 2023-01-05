import {
  ChangeDetectionStrategy, ChangeDetectorRef, Component,
  EventEmitter, Input, OnInit,
  Output, ViewChild, ViewEncapsulation
} from '@angular/core';
import {FormArray, FormControl, FormGroup} from '@angular/forms';
import {PluckPipe} from 'ngx-pipes';
import {MatTable} from '@angular/material/table';
import {CloneArray, GetRandomString, isBoolean} from '../functions';
import {AutocompleteModel} from "../../autocomplete/autocomplete.component";
import {AutoFillInterface} from "../models/col-report-setting-model";

type TableType = 'mat' | 'table' | 'blocky';

@Component({
  selector: 'k-report-table',
  templateUrl: './report-table.component.html',
  styleUrls: ['../table.css'],
  changeDetection: ChangeDetectionStrategy.OnPush,
  encapsulation: ViewEncapsulation.None,
  providers: [PluckPipe]
})
export class ReportTableComponent implements OnInit {

  @ViewChild(MatTable) matTable!: MatTable<any>;

  isDoneRenderRows = false;

  dataSource: any[] = [];
  @Output() getAutocompleteList : EventEmitter<any> = new EventEmitter();
  @Output() stateRenderTable: EventEmitter<any> = new EventEmitter();

  constructor(private pluckPipe: PluckPipe,
    private ref: ChangeDetectorRef) {
  }

  /** Уникальный ИД записи, по умолчанию position, нужно для формирование записей в таблице */
  private _rowIdName!: string;
  get rowIdName(): string {
    return this._rowIdName ?? 'position';
  }
  set rowIdName(value: string) {
    this._rowIdName = value == null ? 'position' : value;
  }

  /** Тип таблицы, у нас 3 типы таблиц. Блочная, с помощью MatTable и обычная таблица */
  private _tableType!: TableType;
  get tableType(): TableType {
    return this._tableType;
  }
  set tableType(value: TableType) {
    this._tableType = value == null ? 'blocky' : value;
  }

  /** Максимальная ширина инпута в таблице */
  private _maxWidthInput!: string;
  get maxWidthInput(): string {
    return this._maxWidthInput;
  }
  set maxWidthInput(value: string) {
    this._maxWidthInput = `${value == null ? '300' : value}`;
  }

  /** Добавление строки в таблицу */
  private _isAddRow!: boolean;

  get isAddRow(): boolean {
    return this._isAddRow;
  }
  set isAddRow(value: boolean) {
    this._isAddRow = isBoolean(value) ? value : false;
  }

  /** Форма с данными для отчета */
  private _form!: any;
  @Input('form')
  get form(): any {
    return this._form;
  }
  set form(value: any) {
    this._form = value == null ? new FormGroup({}) : value;
  }
  private _readonly!: boolean;
  @Input('isReadonly')
  get isReadonly(): boolean {
    return this._readonly;
  }
  set isReadonly(value: boolean) {
    this._readonly = isBoolean(value) ? value : false;
  }

  private _autocompleteList!: Array<AutocompleteModel>;
  @Input('autocompleteList')
  get autocompleteList(): Array<AutocompleteModel> {
    return this._autocompleteList;
  }
  set autocompleteList(value: Array<AutocompleteModel>) {
    this._autocompleteList = value;
  }

  asyncFilterAutocomplete(value: string, crudType: string) {
    const option = {value: value, crudType: crudType};
    this.getAutocompleteList.emit(option);
  }

  autoFillValue(value: any, indexRow: number, autofill: AutoFillInterface): void {
    if (!!autofill && !!value) {
      const {autocompleteFields, reportFields} = autofill;
      reportFields.forEach((name, idx) => {
        let values: string;
        if (typeof autocompleteFields[idx] === 'object') {
          const obj: {key: string, value: string} = autocompleteFields[idx];
           values = value[obj.key][obj.value];
        } else values = value[autocompleteFields[idx]];
        this.form.get([indexRow, name]).setValue(values);
      })
    }
    if (value === undefined && !!autofill) {
      const {reportFields} = autofill;
      reportFields.forEach((name, idx) => {
        this.form.get([indexRow, name]).setValue(null);
      })
    }
  }

  /** Модель таблицы */
  private _table?: any;
  @Input('table')
  get table() {
    return this._table;
  }
  set table(value) {
    this._table = value;
    this.tableType = value.type;
    this.showFooter = value.showFooter;
    this.showTotalRow = value.showTotalRow;
    this.headers = value.headers;
    this.isAddRow = value.isAddRow;
    this.rows = value.rows;
    this.columns = value.columns;
    this.maxWidthInput = value.maxWidthInput;
  }

  /** Показывать футер для таблицы */
  private _showFooter!: boolean;
  get showFooter(): boolean {
    return this._showFooter;
  }
  set showFooter(value: boolean) {
    this._showFooter = isBoolean(value) ? value : false;
  }

  /** Работает если showFooter = true, генерирует итоги из таблиц */
  private _showTotalRow!: boolean;
  get showTotalRow(): boolean {
    return this._showTotalRow;
  }
  set showTotalRow(value: boolean) {
    this._showTotalRow = isBoolean(value) ? value : false;
  }

  /** Содержит в себе наименование заголовок */
  private _headersName!: string[];
  get headersName(): string[] {
    return this._headersName;
  }
  set headersName(value: string[]) {
    this._headersName = value;
  }

  /** Загаловоки отчета */
  private _headers!: any[];
  get headers(): any[] {
    return this._headers;
  }
  set headers(value: any[]) {
    for (let i in value) {
      if (!value[i]['name']) {
        value[i]['name'] = GetRandomString();
      }
    }
    this._headers = value;
    this.headersArray = this.transformHeaders();
    this.headersName = this.pluckPipe.transform(value, 'name');
  }

  public headersArray: Array<Array<any>> = new Array<Array<any>>();

  private transformHeaders(): Array<Array<any>> {
    let result = new Array<Array<any>>();
    result[0] = new Array<any>();
    for (let item of this.headers) {
      if (!item.level || item.level == 0) {
        result[0].push(item);
      }
      else if (item.level) {
        if (!result[item.level]) {
          result[item.level] = new Array<any>();
        }
        result[item.level].push(item);
      }
    }
    return result;
  }

  /** Сформированные строки из stub */
  private _rows!: any[];
  get rows(): any[] {
    return this._rows;
  }
  set rows(value: any[]) {
    this._rows = value;
  }

  /** Содержит в себе массив колонки отчета  */
  private _columns!: any[];
  get columns(): any[] {
    return this._columns;
  }
  set columns(value: any[]) {
    this._columns = value;
  }

  ngOnInit(): void {
    this.stateRenderTable.emit(true);
    this.buildData(this.rows.length);
    if (this.rows.length ==0 && this.isAddRow)  {
      this.addRow();
    }
  }

  addRow() {
    let contentCols: any = [];
    if (this.dataSource && this.dataSource.length) {
      contentCols = {position: this.dataSource.length};
    }
    else {
      contentCols = {position: 0};
    }
    this.columns.forEach(({name}) => {
      if (typeof contentCols[name] === 'undefined') {
        contentCols[name] = '';
      }
    });
    let newGroup = new FormGroup({});
    for (let item of this.columns) {
      newGroup.addControl(item.name, new FormControl());
    }
    (this.form as FormArray).controls.push(newGroup);
    this._appendRowInDataSource(contentCols);
  }

  private buildData(length: number) {
    const ITEMS_RENDERED_AT_ONCE = 50;
    const INTERVAL_IN_MS = 100;
    let currentIndex = 0;

    const interval = setInterval(() => {
      const nextIndex = currentIndex + ITEMS_RENDERED_AT_ONCE;

      for (let n = currentIndex; n <= nextIndex; n++) {
        if (typeof this.rows[n] !== 'undefined') {
          const row = this.rows[n];
          const rowInDataSrc = this.dataSource.findIndex(_row => _row.position === row.position);
          if (rowInDataSrc === -1 || (this.isAddRow && length > n)) {
            this._appendRowInDataSource(row);
          }
        }

        if (n >= length) {
          this.stateRenderTable.emit(false);
          this.isDoneRenderRows = true;
          clearInterval(interval);
          if (this.tableType !== 'mat') {
            this.ref.detectChanges();
          }
          break;
        }
      }

      currentIndex += ITEMS_RENDERED_AT_ONCE;
    }, INTERVAL_IN_MS);
  }

  private _appendRowInDataSource(row: any) {
    this.rows.push(row);
    this.dataSource.push(row);
    if (typeof this.matTable !== 'undefined') {
      this.matTable.renderRows();
    } else {
      //Запускаем перерисовку дочернего компонента подменяя ссылку на массив!
      this.dataSource = CloneArray<any>(this.dataSource);
    }
  }
}
