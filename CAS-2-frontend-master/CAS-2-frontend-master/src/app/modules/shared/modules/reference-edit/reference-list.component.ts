import {Component, EventEmitter, Input, OnDestroy, OnInit, ViewChild} from '@angular/core';
import {select, Store} from '@ngrx/store';
import {ActivatedRoute, Params, Router} from '@angular/router';
import {BreadcrumbsService} from '../../../../services/breadcrumbs.service';
import {CrudType, CrudTypes} from '../../../../common/crud-types';
import {MatDialog} from '@angular/material/dialog';
import {MainService} from '../../../main/pages/admin/settings/main.service';
import {ListFilterFieldInterface} from '../../components/list/list-filter/list-filter.model';
import {Observable} from 'rxjs';
import {ListFilterService} from '../../components/list/list-filter/list-filter.service';
import {animate, state, style, transition, trigger} from '@angular/animations';
import {AuthService} from '../../../../services/auth.service';
import {MatTable} from '@angular/material/table';
import {
  getCrudModelAppendListLoading,
  getCrudModelGetListLoading
} from '../../../../api/api-connector/crud/crud.selectors';
import {
  LoadAppendListAction,
  LoadDeleteAction, LoadGetAction,
  LoadGetListAction, LoadPatchAction
} from '../../../../api/api-connector/crud/crud.actions';
import {CrudState} from '../../../../api/api-connector/crud/crud-store.service';
import {getAllProperties, getPropertyesType, PropertyViewType} from '../../decorators/property-type.decorator';
import {UserModel} from '../../../../api/auth/auth.models';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {SnackBarService} from '../../../../services/snack-bar.service';


export enum crudOperations {APPEND}

@Component({
  selector: 'app-reference-list',
  templateUrl: './reference-list.component.html',
  styleUrls: ['./reference-list.component.scss'],
  animations: [
    trigger('detailExpand', [
      state('collapsed', style({height: '0px', minHeight: '0'})),
      state('expanded', style({height: '*'})),
      transition('expanded <=> collapsed', animate('300ms cubic-bezier(0,0,1,1)')),
    ]),
  ],
})
export class ReferenceListComponent implements OnInit, OnDestroy {
  private _type: CrudType;
  @Input()
  get type(): CrudType {
    return this._type;
  }
  set type(value: CrudType) {
    this._type = value;
    this._model = CrudTypes[value].model;
  }
  private _model: any;
  @Input() code: string;
  @Input() component: any;
  @Input() heightDialogRef = '100% - 50px';
  @Input() private fields: any = {};
  @Input() order: Params = {id: 'DESC'};
  @ViewChild(MatTable) table: MatTable<any>;
  crudType = CrudType;
  filterFields: Array<Array<ListFilterFieldInterface>> = [];
  outAppend = new EventEmitter<{limit: number, offset: number}>();
  outFilter = new EventEmitter();
  loading$: Observable<boolean>;
  appendLoading$: Observable<boolean>;
  private filter: Object = {};
  basicFilter: Object;
  tableFields: Array<string> = ['name', 'edit', 'action'];
  flexColumn: Array<number> = [50, 30, 20];
  reference: Array<any> = [];
  totalCount: number;
  offset = 0;
  limit = 20;
  referenceId: number;
  search: string;
  mutableSearch: boolean;
  properties = [];
  formGroup: FormGroup;
  private fixedHeader;

  constructor(protected store: Store<CrudState>,
              protected router: Router,
              protected route: ActivatedRoute,
              protected notify: SnackBarService,
              protected brdSrv: BreadcrumbsService,
              public authService: AuthService,
              public dialog: MatDialog,
              private listFilterService: ListFilterService,
              public settingsService: MainService) {
  }

  ngOnInit(): void {
    this.formGroup = new FormGroup({
      referenceName: new FormControl(null, [Validators.required]),
    });
    this.getClassMetadata();
    this.loading$ = this.store.pipe(select(getCrudModelGetListLoading, {type: this.type}));
    this.appendLoading$ = this.store.pipe(select(getCrudModelAppendListLoading, {type: this.type}));
    this.store.dispatch(new LoadGetAction({
      type: CrudType.Action,
      params: {
        fields: {1: 'name', 2: 'id'},
        filter: {
          code: this.code,
        }
      },
      onSuccess: ({status, response}) => {
        if (status && this.formGroup.controls.referenceName) {
          this.formGroup.controls.referenceName.setValue(response.items[0]['name']);
          this.fixedHeader = this.formGroup.controls.referenceName.value;
          this.referenceId = response.items[0]['id'];
        }
      }
    }));
    if (this.listFilterService.search) {
      this.search = this.listFilterService.search;
    }
    if (this.listFilterService.filter) {
      this.filter = {...this.listFilterService.filter};
    }
    if (this.type) {
      this.dispatch();
    }
  }
  getClassMetadata(): void {
    const propertiesName = getAllProperties(new this._model());
    for (const i in propertiesName) {
      if (propertiesName.hasOwnProperty(i)) {
        this.properties.push(getPropertyesType(new this._model(), propertiesName[i]));
        if (this.properties[i].type === PropertyViewType.FIELDS) {
          this.fields = this.properties[i].fields;
        }
      }
    }
  }
  dispatch(): void {
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
        fields: this.fields
      },
      onSuccess: (res) => {
        this.totalCount = res.response.totalCount;
        this.getRows(res.response.items);
      }
    }));
  }

  appendList(event: {limit: number, offset: number}): void {
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
        this.getRows(res.response.items, crudOperations.APPEND);
      },
    }));
  }

  filterList(event: {search: string, filter: Object}): void {
    this.search = event.search;
    this.filter = event.filter;
    this.offset = 0;
    this.store.dispatch(new LoadGetListAction({
      type: this.type,
      params: {
        offset: this.offset,
        limit: this.limit,
        order: this.order,
        filter: {...this.filter, ...this.basicFilter},
        search: this.search,
        fields: this.fields,
      },
      onSuccess: (res) => {
        this.outFilter.emit(event);
        this.totalCount = res.response.totalCount;
        this.getRows(res.response.items);
      }
    }));
  }

  save(_: any): void {
    if (this.formGroup.valid) {
      this.store.dispatch(new LoadPatchAction({
        type: CrudType.Action,
        params: {
          id: this.referenceId,
          name: this.formGroup.controls.referenceName.value
        } as any,
        onSuccess: ({status}) => {
          if (status) {
            this.fixedHeader = this.formGroup.controls.referenceName.value;
            this.notify.handleMessage('Название сохранено', 'success-snackBar', 1000);
          }
        }
      }));
    }
  }

  createData($event: any): void {
    if ($event) {
      $event.preventDefault();
    }
    const dialogRef = this.dialog.open(this.component, {
      height: this.heightDialogRef,
      width: window.innerWidth > 960 ? '60%' : '90%',
      autoFocus: false,
      data: {
        openDialog: true,
      }
    });
    dialogRef.afterClosed().subscribe(result => {
      if (result !== undefined) {
        this.getList();
      }
    });
  }

  patchData(id: number = null): void {
    if (id != null) {
      const idString = String(id);
      const dialogRef = this.dialog.open(this.component, {
        height: this.heightDialogRef,
        width: window.innerWidth > 960 ? '65%' : '90%',
        autoFocus: false,
        data: {
          openDialog: true,
          id: idString,
          fields: this.fields
        }
      });
      dialogRef.afterClosed().subscribe(result => {
        if (result !== undefined) {
          this.getList();
        }
      });
    }
  }

  deleteData(id: number = null): void {
    this.store.dispatch(new LoadDeleteAction({
      type: this.type,
      params: {
        id: id
      },
      onSuccess: (_) => {
        this.getList();
      }
    }));
  }


  getList(crudOperation?: crudOperations): void {
    this.store.dispatch(new LoadGetListAction({
      type: this.type,
      params: {
        fields: this.fields,
        order: this.order
      },
      onSuccess: ({status, response}) => {
        if (status) {
          this.totalCount = response.totalCount;
          this.getRows(response.items, crudOperation);
        }
      }
    }));
  }

  getRows(reference: any, crudOperation?: crudOperations): void {
    switch (crudOperation) {
      case crudOperations.APPEND:
        break;
      default:
        this.reference = [];
        break;
    }
    reference.forEach(element => {
      if (!element.hasOwnProperty('show')) {
        this.reference.push({element, show: false});
      }
    });
    this.table.renderRows();
  }

  switchUser(user: UserModel): void {
    const userName = this.isContainCyrillic(user.username) ? user.email : user.username;
    localStorage.setItem('switchUser', userName);
    localStorage.removeItem('sidenav');
    window.location.href = '/';
  }

  toggleRow(value: {show: boolean}): void {
    value.show = !value.show;
  }
  ngOnDestroy(): void {
  }

  private isContainCyrillic(name: string): boolean {
    return /[а-яА-ЯЁё]/.test(name);
  }
}
