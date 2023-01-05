import { Component, Input, OnInit, ViewChild } from '@angular/core';
import { select, Store } from '@ngrx/store';
import { Observable } from 'rxjs';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelGetListLoading} from 'src/app/api/api-connector/crud/crud.selectors';
import { CrudType } from 'src/app/common/crud-types';
import { ViewService } from '../view.service';

@Component({ templateUrl: './researchs.component.html' })

export class ResearchsComponent implements OnInit {
  researchList = [];
  loading$: Observable<boolean>;
  type = CrudType.ResearchDocument;

  constructor(
    private store: Store<CrudState>,
    private service: ViewService,
  ) {
    this.loading$ = this.store.pipe(select(getCrudModelGetListLoading, { type: this.type }));
  }

  ngOnInit() {
    this.store.dispatch(new LoadGetListAction({
      type: this.type,
      params: {
        filter:
        {
          status: { code: 'DONE' },
          probeItem: { probeSampling: { pet: { id: this.service.id } } }
        },
        order: {date : 'DESC'},
      },
      onSuccess: (res) => {
        if (res.response && res.status == true) {
          this.researchList = res.response.items;
        }
      }
    }));
  }
}
