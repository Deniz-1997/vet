import {Component, Input, OnDestroy, OnInit, Output, ViewChild} from '@angular/core';
import {select, Store} from '@ngrx/store';
import {combineLatest, Observable, Subject, Subscription} from 'rxjs';
import {CrudType} from 'src/app/common/crud-types';
import {LeavingModel} from '../../../../../../models/leaving/leaving.models';
import {LeavingChangeStatusService} from '../../../../../../services/leaving-change-status.service';
import {ReferenceLeavingStatusModel} from '../../../../../../models/reference/reference.leaving.status.models';
import {map} from 'rxjs/operators';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelGetListLoading, getCrudModelPatchLoading, getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';


@Component({
  selector: 'app-history-leaving-appointments',
  templateUrl: './leaving-history.component.html',
  styleUrls: ['./leaving-history.component.css']
})
export class LeavingHistoryComponent implements OnInit, OnDestroy {
  loading = true;
  leaving: LeavingModel[] = [];
  loading$: Observable<boolean>;
  @Output() leavings = [];
  limit = 40;
  offset = 0;
  @Input() owner$;
  @Input() pet$;
  private destroy$ = new Subject<any>();
  leavingsStatuses$: Observable<{ label: string, value: ReferenceLeavingStatusModel }[]>;
  link: string;
  c = '#';
  d = 'demo';




  constructor(
    public changeStatus: LeavingChangeStatusService,
    private store: Store<CrudState>,
  ) {
    this.loading$ = combineLatest(
      store.pipe(select(getCrudModelGetListLoading, { type: CrudType.Leaving })),
      store.pipe(select(getCrudModelPatchLoading, { type: CrudType.Leaving })),
    ).pipe(map(([getListLoading, patchLoading]) => getListLoading || patchLoading));
    this.leavingsStatuses$ = store.pipe(
      select(getCrudModelData, {type: CrudType.ReferenceLeavingStatus}),
      map(statuses => statuses.map(leavingStatus => ({label: leavingStatus.name, value: leavingStatus})))
    );
  }




  ngOnInit() {
    let filter = {};
    if (this.pet$) {
      filter = {pet: {id : this.pet$?.id}};
      this.link = '/pets/' + this.pet$?.id + '/history-detail';
    }
    if (this.owner$) {
      filter = {owner: {id : this.owner$?.id}};
      this.link = '/owners/' + this.owner$?.id + '/history-detail';
    }

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.Leaving,
      params: {
        filter: filter
      },
      onSuccess: response => {
        if (response.response.items.length > 0) {
          this.leaving = response.response.items;
          this.loading = false;
        }
        return this.loading = false;
      }
    }));
  }



  ngOnDestroy(): void {
    this.destroy$.next();
  }
}
