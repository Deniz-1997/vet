import {Component, EventEmitter, OnInit, ViewChild} from '@angular/core';
import {ActivatedRoute, Params} from '@angular/router';
import {select, Store} from '@ngrx/store';
import {Observable} from 'rxjs';
import {CrudType} from '../../../../../../common/crud-types';
import {getCrudModelGetListLoading} from '../../../../../../api/api-connector/crud/crud.selectors';
import {LoadAppendListAction, LoadGetAction, LoadGetListAction} from '../../../../../../api/api-connector/crud/crud.actions';
import {CrudState} from '../../../../../../api/api-connector/crud/crud-store.service';
import {ApiQueueModel} from 'src/app/models/vaccination/api-queue.model';
import {ApiQueueRowModel} from 'src/app/models/vaccination/api-queue-row.model';
import {UsersService} from 'src/app/services/users.service';
import {MatDialog} from '@angular/material/dialog';
import {ModalFileErrorEditComponent} from '../../../../../shared/components/modal-file-error-edit/modal.component';
import {DataNameService} from './data-name.service';
import {FormControl, FormGroup} from '@angular/forms';
import {MatTable} from '@angular/material/table';
import {VaccinationModel} from '../../../../../../models/vaccination/vaccination.model';
import {debounceTime, distinctUntilChanged} from 'rxjs/operators';

@Component({
  selector: 'app-uploaded-vaccination-view',
  templateUrl: './view.component.html',
  styleUrls: ['../vaccination.component.scss']
})
export class ViewComponent implements OnInit {
  @ViewChild(MatTable) table: MatTable<any>;
  id: string;
  reference: Array<any>;
  totalCount: number;
  title: number;
  date: number;
  outAppend = new EventEmitter<{ limit: number, offset: number }>();
  type = CrudType.ApiQueueRow;
  loading$: Observable<boolean>;
  displayedColumns = ['status', 'data', 'errors'];
  order: { id: 'ASC' };
  vaccinations: Array<VaccinationModel> = [];
  statusMsg: string;
  model: ApiQueueModel;
  limit = 10;
  offset = 0;
  filter: Params = {};
  appendLoading$: Observable<boolean>;
  statusList: Array<any>;
  searchValue: string;
  formGroup: any = new FormGroup({
    status: new FormControl(),
    search: new FormControl()
  });
  private fields = {
    0: 'id',
    1: 'createdAt',
    2: 'name',
    rows: ['status'],
    'businessEntity': {0: 'id', 1: 'name'},
    'station': {0: 'id', 1: 'name'},
    'user': {0: 'id', 1: 'name', 2: 'surname', 3: 'patronymic'}
  };

  constructor(
    private dialog: MatDialog,
    protected store: Store<CrudState>,
    protected route: ActivatedRoute,
    private usersService: UsersService,
    public dataNameService: DataNameService,
  ) {

  }

  ngOnInit(): void {
    this.id = this.route.snapshot.paramMap.get('id');
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.Enum,
      params: {
        filter: {id: 'ApiQueueStatusEnum'}
      },
      onSuccess: ({response}) => this.statusList = response[0].items
    }));
    this.loading$ = this.store.pipe(select(getCrudModelGetListLoading, {type: this.type}));

    this.loadModel();
    this.loadData();

    this.formGroup.controls.status.valueChanges.subscribe(({id}) => {
      this.filter = !!id ? {status: id} : {};
      this.loadData();
    });
    this.formGroup.controls.search.valueChanges.pipe(
      debounceTime(500),
      distinctUntilChanged()
    ).subscribe( search => {
      this.searchValue = !!search ? search : undefined;
      this.loadData();
    });
  }

  private loadModel(): void {
    this.store.dispatch(new LoadGetAction({
      type: CrudType.ApiQueue,
      params: {
        id: this.id,
        fields: this.fields
      },
      onSuccess: ({response, status}) => {
        if (status && response) {
          this.model = response;
        }
      }
    }));
  }

  private loadData(): void {
    this.store.dispatch(new LoadGetListAction({
      type: this.type,
      params: {
        fields: ['id', 'data', 'status', 'error'],
        filter: {apiQueue: {id: this.id}, ...this.filter},
        search: this.searchValue,
        offset: 0,
        limit: 10,
      },
      onSuccess: (res) => {
        if (res.status && res.response) {
          this.totalCount = res.response.totalCount;
          this.model.rows = res.response.items;
        }
      }
    }));
  }

  appendList(event: { limit: number; offset: number }): void {
    const {limit, offset} = event;
    this.store.dispatch(new LoadAppendListAction({
      type: this.type,
      params: {
        fields: ['id', 'data', 'status', 'error'],
        filter: {apiQueue: {id: this.id}, ...this.filter},
        offset: offset + limit,
        search: this.searchValue,
        limit: limit,
      },
      onSuccess: (res) => {
        if (res.status && res.response) {
          this.offset = offset + limit;
          this.outAppend.emit(event);
          this.model.rows.push(...res.response.items);
          this.table.renderRows();
        }
      },
    }));
  }

  showModalErrorEdit(element: ApiQueueRowModel): void {
    const dialog = this.dialog.open(ModalFileErrorEditComponent, {
      width: '900px',
      height: '200px',
      autoFocus: false,
      data: element
    });
    dialog.afterClosed().subscribe(res => {
      this.loadData();
    });
  }

  clearFilters(): void {
    this.formGroup.controls.status.setValue('null');
    this.formGroup.controls.search.setValue(undefined);
  }

  getRowData(row: any): Array<{ name: string, value: string }> {
    if (!row) {
      return null;
    }
    const resultString = new Array<{ name: string, value: string }>();
    for (const item of Object.getOwnPropertyNames(row)) {
      resultString.push({name: this.dataNameService.getDataName(item), value: row[item]});
    }
    return resultString;
  }
}
