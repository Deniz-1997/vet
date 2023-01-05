import {
  AfterContentInit,
  Component,
  ContentChild,
  ContentChildren,
  EventEmitter,
  Input,
  QueryList, TemplateRef,
  ViewChild
} from '@angular/core';
import {select, Store} from '@ngrx/store';
import {MatColumnDef, MatTable} from '@angular/material/table';
import {Observable} from 'rxjs';
import {CrudType} from '../../../../common/crud-types';
import {
  getCrudModelAppendListLoading,
  getCrudModelData,
  getCrudModelTotalCount
} from '../../../../api/api-connector/crud/crud.selectors';
import {LoadAppendListAction} from '../../../../api/api-connector/crud/crud.actions';
import {CrudState} from '../../../../api/api-connector/crud/crud-store.service';
import {CrudDataType} from '../../../../api/api-connector/crud/crud.config';

@Component({
  selector: 'app-universal-mat-table',
  templateUrl: './universal-mat-table.html',
  styleUrls: ['./universal-mat-table.scss'],
})
export class UniversalMatTableComponent implements AfterContentInit {
  @Input() dataSource: Array<any>;
  @Input() displayedColumns: Array<string>;
  @Input() type: CrudType;
  @Input() fields: object = {};
  @Input() filter: object = {};
  @Input() order = {};
  @Input() isEmptyInformation: string;
  @ViewChild(MatTable, {static: true}) table: MatTable<any>;
  @ContentChild('headerTable', {static: true}) headerTable: TemplateRef<any>;
  @ContentChildren(MatColumnDef) columnDefs: QueryList<MatColumnDef>;
  outAppend = new EventEmitter<{limit: number, offset: number}>();
  appendLoading$: Observable<boolean>;
  totalCount$: Observable<number>;
  items$: Observable<Array<CrudDataType>>;
  offset = 0;
  limit = 50;



  constructor(protected store: Store<CrudState>) {
  }

  ngAfterContentInit(): void {
    this.totalCount$ = this.store.pipe(select(getCrudModelTotalCount, {type: this.type}));
    this.totalCount$.subscribe(val => {
      if (val) {
        this.offset = 0;
      }
    });
    this.items$ = this.store.pipe(select(getCrudModelData, {type: this.type}));
    this.appendLoading$ = this.store.pipe(select(getCrudModelAppendListLoading, {type: this.type}));
    this.columnDefs.forEach(columnDef => this.table.addColumnDef(columnDef));
  }
  appendList(event: {limit: number, offset: number}): void {
    const {limit, offset} = event;
    this.store.dispatch(new LoadAppendListAction({
      type: this.type,
      params: {
        order: this.order,
        offset: offset + limit,
        limit: limit,
        filter: this.filter,
        fields: this.fields,
      },
      onSuccess: (res) => {
        this.offset = offset + limit;
        this.outAppend.emit(event);
        res.response.items.forEach(element => this.dataSource.push(element));
        this.table.renderRows();
      },
    }));
  }

}

