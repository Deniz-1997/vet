import { Component, OnInit } from '@angular/core';
import {CrudType} from '../../../../../common/crud-types';
import {Observable} from 'rxjs';
import {select, Store} from '@ngrx/store';
import {map} from 'rxjs/operators';
import {SearchModels} from '../../../../../models/search.models';
import {LeavingModel} from '../../../../../models/leaving/leaving.models';
import {ReferenceLeavingStatusModel} from '../../../../../models/reference/reference.leaving.status.models';
import {LeavingChangeStatusService} from '../../../../../services/leaving-change-status.service';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData, getCrudModelGetListLoading} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({
  selector: 'app-leaving-list',
  templateUrl: './leaving-list.component.html',
})
export class LeavingListComponent extends  SearchModels implements OnInit {
  crudType = CrudType;
  type = CrudType.Leaving;
  model = CrudType.Leaving;
  leavings$: Observable<LeavingModel[]>;
  leavingsLoading$: Observable<boolean>;
  leavingsStatuses$: Observable<{ label: string, value: ReferenceLeavingStatusModel }[]>;
  private maxNameLength = 70;
  c = '#';
  d = 'demo';


  constructor(
    protected store: Store<CrudState>,
    public leavingStatusChange: LeavingChangeStatusService,
  ) {
    super();

    this.leavings$ = store.pipe(select(getCrudModelData, {type: this.type}));
    this.leavingsLoading$ = store.pipe(select(getCrudModelGetListLoading, {type: this.type}));

    this.store.dispatch(new LoadGetListAction({type: CrudType.ReferenceLeavingStatus}));
    this.leavingsStatuses$ = store.pipe(
      select(getCrudModelData, {type: CrudType.ReferenceLeavingStatus}),
      map(statuses => statuses.map(leavingStatus => ({label: leavingStatus.name, value: leavingStatus})))
    );
  }

  ngOnInit() {
    super.ngOnInit();
  }

  getShortName(name: string) {
    if (name && name.length > this.maxNameLength) {
      const subName = name.split(' ');
      let result = '';
      for (let i = 0; i < subName.length; i++) {
        result += subName[i] + ' ';
        if (result.length > this.maxNameLength) {
          return result + '...';
        }
      }
    }
    return name;
  }

  islongLenght(name): boolean {
    return name.length > this.maxNameLength;
  }

}
