import {
  Component,
  ContentChild,
  ElementRef,
  EventEmitter,
  Input,
  OnInit,
  Output,
  TemplateRef,
  ViewChild
} from '@angular/core';
import {select, Store} from '@ngrx/store';
import {Observable} from 'rxjs';
import {NgForOfContext} from '@angular/common';
import {ListFilterFieldInterface} from '../list-filter/list-filter.model';
import {ListFilterComponent} from '../list-filter/list-filter.component';
import {BreadcrumbsService} from '../../../../../services/breadcrumbs.service';
import {ListFilterService} from '../list-filter/list-filter.service';
import {CrudType} from '../../../../../common/crud-types';
import {
  getCrudModelAppendListLoading,
  getCrudModelData,
  getCrudModelGetListLoading, getCrudModelTotalCount
} from '../../../../../api/api-connector/crud/crud.selectors';
import {LoadAppendListAction, LoadGetListAction} from '../../../../../api/api-connector/crud/crud.actions';
import {CrudState} from '../../../../../api/api-connector/crud/crud-store.service';
import {CrudDataInterface, CrudDataType} from '../../../../../api/api-connector/crud/crud.config';

@Component({
  selector: 'app-list-filter-view',
  templateUrl: './list-filter-view.component.html',
  styleUrls: ['./list-filter-view.component.css']
})
export class ListFilterViewComponent implements OnInit {

  @Input() type: CrudType;

  @Input() filterPlaceholder = 'Поиск по ключевому слову';
  @Input() mutableSearch: boolean;

  @Output() outFilter = new EventEmitter();
  @Input() filterExtended = false;
  @Input() filterFields: Array<Array<ListFilterFieldInterface>> = [];

  @Input() title: string;
  @ContentChild('titleActionsTemplate', {static: true}) titleActionsTemplate: TemplateRef<any>;

  @Input() listEmptyOptions: {
    elementName?: string,
    title?: string,
    subtitle?: string,
    addLink?: Array<string> | string,
    buttons?: Array<{ title: string, action: string }>,
  } = {};
  @Output() outAppend = new EventEmitter<{ limit: number, offset: number }>();
  @ContentChild('listItemTemplate', {static: true}) listItemTemplate: TemplateRef<NgForOfContext<CrudDataInterface | CrudDataType>>;
  @ContentChild('listItemTemplateGrid', {static: true}) listItemTemplateGrid: TemplateRef<NgForOfContext<CrudDataInterface | CrudDataType>>;
  @Output() outEmptyClick = new EventEmitter<{ action: string }>();
  // @Output() onSort = new EventEmitter<{ order: string }>();
  @ContentChild('listHeadTemplate', {static: true}) listHeadTemplate: TemplateRef<ElementRef>;
  @Input() basicFilter: any;
  @Input() initDispatch = true;
  @Input() pageSize;
  @Input() order: { id: 'DESC' };
  @Input() sort?: Observable<any>;
  search: string;

  @ViewChild(ListFilterComponent) listFilterComponent: ListFilterComponent;
  items$: Observable<Array<CrudDataType>>;
  totalCount$: Observable<number>;
  loading$: Observable<boolean>;
  appendLoading$: Observable<boolean>;
  offset = 0;
  limit = 20;
  private filter: any = {};
  private fields: any = {};

  constructor(private store: Store<CrudState>,
              private brdSrv: BreadcrumbsService,
              private listFilterService: ListFilterService) {
  }

  ngOnInit(): void {
    if (this.pageSize) {
      this.limit = this.pageSize;
    }
    if (!this.order) {
      this.order = {id: 'DESC'};
    }

    if (this.listFilterService.search) {
      this.search = this.listFilterService.search;
    }

    if (this.listFilterService.filter) {
      this.filter = {...this.listFilterService.filter};
    }
    if (this.sort) {
      this.sort.subscribe(() => {
        this.dispatch();
      });
    }
    this.items$ = this.store.pipe(select(getCrudModelData, {type: this.type}));
    this.loading$ = this.store.pipe(select(getCrudModelGetListLoading, {type: this.type}));
    this.totalCount$ = this.store.pipe(select(getCrudModelTotalCount, {type: this.type}));
    this.appendLoading$ = this.store.pipe(select(getCrudModelAppendListLoading, {type: this.type}));
    if (this.initDispatch && this.type) {
      this.dispatch();
    }
  }

  dispatch(): void {
    const field = {};

    this.fields = field;

    if (this.filter['user'] && this.filter['user'].fullName) {
      this.filter['user'] = {id: this.filter['user'].id};
    }

    this.store.dispatch(new LoadGetListAction({
      type: this.type,
      params: {
        filter: {...this.filter, ...this.basicFilter},
        order: this.order,
        limit: this.limit,
        offset: this.offset,
        search: this.search,
        fields: field
      }
    }));
  }

  appendList(event: { limit: number, offset: number }): void {
    const {limit, offset} = event;
    this.store.dispatch(new LoadAppendListAction({
      type: this.type,
      params: {
        order: this.order,
        offset: offset + limit,
        limit: limit,
        filter: {...this.filter, ...this.basicFilter},
        search: this.search,
        fields: this.fields,
      },
      onSuccess: (res) => {
        this.offset = offset + limit;
        this.outAppend.emit(event);
      },
    }));
  }

  filterList(event: { search: string, filter: Object }): void {
    this.search = event.search;
    this.filter = event.filter;
    this.offset = 0;
    this.store.dispatch(new LoadGetListAction({
      type: this.type,
      params: {
        offset: this.offset,
        limit: this.limit,
        filter: {...this.filter, ...this.basicFilter},
        search: this.search,
        fields: this.fields,
      },
      onSuccess: (res) => {
        this.outFilter.emit(event);
      }
    }));
  }

  setPageSize(page: number): void {
    this.limit = page;
    this.dispatch();
  }
}
