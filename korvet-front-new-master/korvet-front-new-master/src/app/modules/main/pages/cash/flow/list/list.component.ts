import {Component, OnDestroy, OnInit} from '@angular/core';
import {Store} from '@ngrx/store';
import {CashService} from '../../cash.service';
import {CrudType} from 'src/app/common/crud-types';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetAction} from 'src/app/api/api-connector/crud/crud.actions';

@Component({templateUrl: './list.component.html'})

export class ListComponent implements OnInit, OnDestroy {
  type = CrudType.CashFlow;
  c = '#';
  d = 'demo';

  constructor(
    protected store: Store<CrudState>,
    public cashService: CashService,
  ) {
  }

  ngOnInit() {
  }

  onCashRegisterRegister(id) {
    this.cashService.onCashFlowRegister(id, (res) => {
      if (res.response.correlationId) {
        this.cashService.getAsyncResult(res.response.correlationId, (data) => {
          if (data && data.asyncStatus === 'DONE') {
            this.store.dispatch(new LoadGetAction({type: this.type, params: id}));
          }
        });
      }
    });
  }

  ngOnDestroy() {
    this.cashService.unSubscribe();
  }
}
