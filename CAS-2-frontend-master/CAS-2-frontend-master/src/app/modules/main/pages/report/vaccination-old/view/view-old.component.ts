import {Component, EventEmitter, Input, OnInit} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {select, Store} from '@ngrx/store';
import {Observable} from 'rxjs';
import {CrudType} from '../../../../../../common/crud-types';
import {
  getCrudModelAppendListLoading,
  getCrudModelData,
  getCrudModelTotalCount
} from '../../../../../../api/api-connector/crud/crud.selectors';
import {LoadAppendListAction, LoadGetListAction} from '../../../../../../api/api-connector/crud/crud.actions';
import {CrudState} from '../../../../../../api/api-connector/crud/crud-store.service';
import {CrudDataType} from '../../../../../../api/api-connector/crud/crud.config';

@Component({
  selector: 'app-uploaded-vaccination-view',
  templateUrl: './view-old.component.html',
  styleUrls: ['../vaccination-old.component.css']
})
export class ViewOldComponent implements OnInit {
  id: string;
  reference: Array<any>;
  referenceErrorItems: Array<any>;
  totalCount: number;
  countErrorItems: number;
  title: number;
  date: number;
  offset = 0;
  order: { sort: 'ASC' };
  limit = 20;
  outAppend = new EventEmitter<{ limit: number, offset: number }>();
  type = CrudType.UploadedVaccinationExcelRowEntry;
  appendLoading$: Observable<boolean>;
  items$: Observable<Array<CrudDataType>>;
  totalCount$: Observable<number>;
  displayedColumns = ['id', 'statusMsg'];
  statusMsg: string;

  constructor(
    protected store: Store<CrudState>,
    protected route: ActivatedRoute,
  ) {

  }

  ngOnInit(): void {
    this.id = this.route.snapshot.paramMap.get('id');
    this.appendLoading$ = this.store.pipe(select(getCrudModelAppendListLoading, {type: this.type}));
    this.totalCount$ = this.store.pipe(select(getCrudModelTotalCount, {type: this.type}));
    this.items$ = this.store.pipe(select(getCrudModelData, {
      type: CrudType.UploadedVaccinationExcelRowEntry,
      params: {
        filter: {
          excelFile: {id: this.id},
          status: 'sys_error',
        }
      }
    }));
    this.store.dispatch(new LoadGetListAction( {
      type: CrudType.UploadedVaccinationExcelRowEntry,
      params: {
        filter: {
          excelFile: {
            id: this.id,
          }
        }
      },
      onSuccess: (res) => {
        this.reference = res.response.items;
        this.totalCount = res.response.totalCount;
        this.title = this.reference[0].excelFile.sourceName;
        this.date = this.reference[0].excelFile.uploadedAt;
        this.statusMsg = this.reference[0].excelFile.statusMsg;
        this.GetErrorItems();
      }
    }));
  }

  GetErrorItems(): void {
    this.store.dispatch(new LoadGetListAction( {
      type: CrudType.UploadedVaccinationExcelRowEntry,
      params: {
        filter: {
          excelFile: {id: this.id},
          status: 'sys_error',
        },
        limit: this.limit,
      },
      onSuccess: (res) => {
        this.countErrorItems = res.response.totalCount;
        this.referenceErrorItems = res.response.items;
      }
    }));
  }

  appendList(event: { limit: number, offset: number }): void {
    const {limit, offset} = event;
    this.store.dispatch(new LoadAppendListAction({
      type: this.type,
      params: {
        filter: {
          excelFile: {id: this.id},
          status: 'sys_error',
        },
        order: this.order,
        offset: offset + limit,
        limit: limit,
      },
      onSuccess: (res) => {
        this.offset = offset + limit;
        this.outAppend.emit(event);
        for (const item of res.response.items) {
          this.referenceErrorItems.push(item);
        }
      },
    }));
  }
}
