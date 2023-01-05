import {Component, EventEmitter, Input, OnDestroy, OnInit, ViewChild} from '@angular/core';
import {select, Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {NotifyService} from '../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../services/breadcrumbs.service';
import {CrudType} from '../../../../common/crud-types';
import {MatDialog} from '@angular/material/dialog';
import {ModalConfirmComponent} from '../../components/modal-confirm/modal-confirm.component';
import {CdkDragDrop, moveItemInArray} from '@angular/cdk/drag-drop';
import {AuditionService} from '../../../../services/audition.service';
import {MainService} from '../../../main/pages/admin/settings/main.service';
import {ListFilterFieldInterface} from '../../components/list-filter/list-filter.model';
import {Observable, Subject} from 'rxjs';
import {ListFilterComponent} from '../../components/list-filter/list-filter.component';
import {ListFilterService} from '../../components/list-filter/list-filter.service';
import {takeUntil} from 'rxjs/operators';
import {CrudDataType} from 'src/app/api/api-connector/crud/crud.config';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadAppendListAction, LoadDeleteAction, LoadGetAction, LoadGetListAction, LoadPatchAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelAppendListLoading, getCrudModelData, getCrudModelGetListLoading, getCrudModelTotalCount} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({
  selector: 'app-reference-edit',
  templateUrl: './reference-list.component.html',
  styleUrls: ['./dragStyle.css']
})
export class ReferenceListComponent implements OnInit, OnDestroy {
  @Input() type: CrudType;
  @Input() code: string;
  @Input() component: any;
  reference: any;
  c = '#';
  g = '22';
  d = 'demo';
  referenceId;
  referenceName: string;
  newReferenceName: string;
  totalCount: number;
  loading = false;
  crudType = CrudType;
  filterPlaceholder = 'Поиск по ключевому слову';
  mutableSearch = false;
  outFilter = new EventEmitter();
  filterExtended = false;
  filterFields: ListFilterFieldInterface[][] = [];
  basicFilter: Object;
  initDispatch = true;
  order: { sort: 'ASC' };
  orderId: { id: 'DESC' };
  sort?: Observable<any>;
  search: string;
  totalCount$: Observable<number>;
  offset = 0;
  limit = 50;
  private filter: Object = {};
  private fields: any = {};
  outAppend = new EventEmitter<{ limit: number, offset: number }>();
  loading$: Observable<boolean>;
  appendLoading$: Observable<boolean>;
  items$: Observable<CrudDataType[]>;
  allCount: number;
  itemsCount = 0;
  private destroy$ = new Subject<any>();
  @ViewChild(ListFilterComponent) listFilterComponent: ListFilterComponent;

  constructor(protected store: Store<CrudState>,
              protected router: Router,
              protected route: ActivatedRoute,
              protected notify: NotifyService,
              protected brdSrv: BreadcrumbsService,
              public dialog: MatDialog,
              public auditionService: AuditionService,
              private listFilterService: ListFilterService,
              public settingsService: MainService) {
  }

  ngOnInit() {
    this.store.dispatch(new LoadGetListAction( {
      type: CrudType.Action,
      params: {
        filter: {
          code: this.code,
        }
      },
      onSuccess: (res) => {
        this.referenceName = res.response.items[0]['name'];
        this.referenceId = res.response.items[0]['id'];
      }
    }));
    if (!this.order) {
      this.order = {sort: 'ASC'};
    }

    if (this.listFilterService.search) {
      this.search = this.listFilterService.search;
    }

    if (this.listFilterService.filter) {
      this.filter = {...this.listFilterService.filter};
    }
    if (this.sort) {
      this.sort.subscribe(() => {
        this.dispatch(this.order);
      });
    }

    this.totalCount$ = this.store.pipe(select(getCrudModelTotalCount, {type: this.type}));
    this.items$ = this.store.pipe(select(getCrudModelData, {type: this.type}));
    this.loading$ = this.store.pipe(select(getCrudModelGetListLoading, {type: this.type}));
    this.appendLoading$ = this.store.pipe(select(getCrudModelAppendListLoading, {type: this.type}));

    if (this.initDispatch && this.type) {
      this.totalCount$.pipe(
        takeUntil(this.destroy$))
        .subscribe(res => {
        this.itemsCount = res;
        if (this.itemsCount > this.limit || this.type === CrudType.Notifications) {
          this.dispatch(this.orderId);
        } else {
          this.dispatch(this.order);
        }
      });
    }
  }
  dispatch(order): void {

    console.log(3);
    this.loading = true;
    const field = {};
    this.fields = field;
    if (this.filter['user'] && this.filter['user'].fullName) {
      this.filter['user'] = {id: this.filter['user'].id};
    }

    this.store.dispatch(new LoadGetListAction({
      type: this.type,
      params: {
        filter: {...this.filter, ...this.basicFilter},
        order: order,
        limit: this.limit,
        offset: this.offset,
        search: this.search,
        fields: field
      },
      onSuccess: (res) => {
        this.loading = false;
        this.allCount = res.response.totalCount;
        this.totalCount = res.response.items.length;
        this.reference = res.response.items;
      }
    }));
  }

  appendList(event: { limit: number, offset: number }): void {
    const {limit, offset} = event;
    this.loading = true;
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
        this.loading = false;
        this.offset = offset + limit;
        this.outAppend.emit(event);
        for (const  i in res.response.items) {
          this.reference.push(res.response.items[i]);
        }
      },
    }));
  }




  filterList(event: { search: string, filter: Object }): void {
    this.loading = true;
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
        this.loading = false;
        this.outFilter.emit(event);
        this.reference = res.response.items;
      }
    }));
  }



  onKey(event) {
    const inputValue = event.target.value;
    return this.newReferenceName = inputValue;
  }
  save(event) {
    this.loading = true;
    this.store.dispatch(new LoadPatchAction({
      type: CrudType.Action,
      params: <any>{
        id: this.referenceId,
        name: this.newReferenceName
      },
      onSuccess: response => {
        this.referenceName = this.newReferenceName;
        this.loading = false;
      }
    }));


  }

  createData($event) {
    if ($event) {
      $event.preventDefault();
    }
    const dialogRef = this.dialog.open(this.component, {
      height: '100% - 50px',
      width: window.innerWidth > 960 ? '60%' : '90%',
      data: {
        openDialog: true,
      }
    });
    dialogRef.afterClosed().subscribe( result => {
      if (result !== undefined) {
        this.reference.push(result);
        this.totalCount += 1;
        this.getList();
      }
    });
  }

  patchData(id: number = null) {
    if (id != null) {
      const idString = String(id);
      const dialogRef = this.dialog.open(this.component, {
        height: '100% - 50px',
        width: window.innerWidth > 960 ? '60%' : '90%',
        data: {
          openDialog: true,
          id: idString,
        }
      });
      dialogRef.afterClosed().subscribe(result => {
        if (result !== undefined) {
          this.loading = true;
          this.getList();
        }
      });
    }
  }

  deleteData(id: number = null) {
    const dialogRef = this.dialog.open(ModalConfirmComponent, {
      height: '100% - 50px',
      width: window.innerWidth > 960 ? '20%' : '90%',
      data: {
        head: 'Вы точно хотите удалить значение?',
        headComment: 'Действие необратимо',
        actions: [
          {
            class: 'btn-st btn-st--left btn-st--gray',
            action: false,
            title: 'Отмена'
          },
          {
            class: 'btn-st btn-st--right btn-st--red',
            action: true,
            title: 'Удалить'
          },
        ],
      }
    });

    dialogRef.afterClosed().subscribe((result: boolean) => {
      if (result) {
        this.loading = true;
        this.store.dispatch(new LoadDeleteAction({
          type: this.type,
          params: {
            id: id},
          onSuccess: (res) => {
            this.totalCount -= 1;
            this.loading = false;
            this.getList();
          }
        }));
      }
    });
  }


  getList() {
    this.store.dispatch(new LoadGetAction({
      type: this.type,
      params: {
        order: this.order
      },
      onSuccess: response => {
        this.reference = response.response.items;
        this.loading = false;
      }
    }));
  }

  drop(event: CdkDragDrop<string[]>) {
    moveItemInArray(this.reference, event.previousIndex, event.currentIndex);
    let currentSort;
    let previousSort;
    let currentId;
    let previousId;
    let step;
    const num = event.currentIndex - event.previousIndex;

    if (event.currentIndex > event.previousIndex) {
      previousSort = event.previousIndex + num;
      previousId = this.reference[event.currentIndex]['id'];
      currentSort = event.previousIndex;
      currentId = this.reference[event.previousIndex]['id'];
      for (step = event.previousIndex + 1; step < event.currentIndex; step ++) {
        const id = Number(this.reference[step]['id']);
        const  sort = step;
        this.changeSort(id, sort);
      }
      for (step = event.currentIndex + 1; step <= this.totalCount - 1; step ++) {
        const id = Number(this.reference[step]['id']);
        const sort = step;
        this.changeSort(id, sort);
      }
      for (step = 0; step < event.previousIndex; step ++) {
        const id = Number(this.reference[step]['id']);
        const sort = step;
        this.changeSort(id, sort);
      }
      } else if (event.currentIndex < event.previousIndex) {
      previousSort = event.previousIndex + num ;
      previousId = this.reference[event.currentIndex]['id'];
      currentSort = event.previousIndex;
      currentId = this.reference[event.previousIndex]['id'];
      for (step = event.currentIndex + 1; step < event.previousIndex; step++ ) {
        const id = Number(this.reference[step]['id']);
        const  sort = step;
        this.changeSort(id, sort);
      }
      for (step = event.previousIndex + 1; step <= this.totalCount - 1; step ++) {
        const id = Number(this.reference[step]['id']);
        const sort = step;
        this.changeSort(id, sort);
      }
      for (step = 0; step < event.currentIndex; step ++) {
        const id = Number(this.reference[step]['id']);
        const sort = step;
        this.changeSort(id, sort);
      }
    }
      this.changeSort(currentId, currentSort);
      this.changeSort(previousId, previousSort);

  }

  changeSort(id, sort: number) {
    this.store.dispatch(new LoadPatchAction({
      type: this.type,
      params: <any>{
        id: id,
        sort: sort
      }
    }));
  }
  ngOnDestroy(): void {
    this.destroy$.next();
  }


}
