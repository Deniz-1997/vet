import {Component, Input, OnDestroy, OnInit} from '@angular/core';
import {CrudType} from 'src/app/common/crud-types';
import {Store} from '@ngrx/store';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';

@Component({
  selector: 'app-cash-receipt-count',
  templateUrl: './cash-receipt-count.component.html',
})

export class CashReceiptCountComponent implements OnInit, OnDestroy {
  @Input() id: number;
  count = 0;



  constructor(private store: Store<CrudState>) {
  }

  ngOnInit() {
    if (this.id !== undefined) {
      this.store.dispatch(new LoadGetListAction({
        type: CrudType.CashReceipt,
        params: {
          filter: {
            shift: {'id': this.id},
          },
          fields: {0: 'id'},
          limit: 0
        },
        onSuccess: result => {
          if (result.response.totalCount > 0) {
            this.count = result.response.totalCount;
          }
        }
      }));
    }
  }
  ngOnDestroy() {
    if (this.count !== undefined) {
      this.count = 0;
    }
  }
}
