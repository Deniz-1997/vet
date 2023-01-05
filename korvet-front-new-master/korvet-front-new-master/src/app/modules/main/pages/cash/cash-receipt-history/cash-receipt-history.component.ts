import {Component, Input, OnDestroy, OnInit} from '@angular/core';
import {Subject} from 'rxjs';
import {Store} from '@ngrx/store';
import {CrudType} from 'src/app/common/crud-types';
import {AppointmentModel} from '../../../../../models/appointment/appointment.models';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';

@Component({selector: 'app-cash-receipt-history',
  templateUrl: './cash-receipt-history.component.html'})

export class CashReceiptHistoryComponent implements OnInit, OnDestroy {
  loading = true;
  appointments: AppointmentModel[] = [];
  limit = 40;
  offset = 0;
  @Input() owner$;
  @Input() pet$;
  @Input() type: CrudType;
  private destroy$ = new Subject<any>();
  name: string;
  navigateUrl: string;
  c = '#';
  d = 'demo';
  fields = {
    0: 'id',
    'pet': {
      0: 'id',
      1: 'name'
    },
    'cashReceipt': {
      0: 'id',
      1: 'createdAt',
      2: 'type',
      3: 'fiscal',
      4: 'total'
    }
  };

  constructor(
    private store: Store<CrudState>,
  ) {}


  ngOnInit() {
    let filter = {};
    let type;
    if (this.pet$) {
      filter = {pet: {id : this.pet$?.id}};
    }
    if (this.owner$) {
      filter = {owner: {id : this.owner$?.id}};
    }
    if (this.type === CrudType.Appointment) {
      type = CrudType.Appointment;
      this.name = 'Открыть приём';
      this.navigateUrl = 'appointments';
    }
    if (this.type === CrudType.Leaving) {
      type = CrudType.Leaving;
      this.name = 'Открыть выезд';
      this.navigateUrl = 'leaving';
    }

    this.store.dispatch(new LoadGetListAction({
      type: type,
      params: {
        filter: filter,
        fields: this.fields
      },
      onSuccess: response => {
        if (response.response.items.length > 0) {
          console.log(1111);
          for (const a in response.response.items) {
            if (response.response.items[a]['cashReceipt'] !== null) {
              this.appointments = response.response.items;
            }
          }
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




