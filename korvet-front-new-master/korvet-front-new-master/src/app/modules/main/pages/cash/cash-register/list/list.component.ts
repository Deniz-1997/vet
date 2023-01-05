import {Component} from '@angular/core';
import {CashService} from '../../cash.service';
import {CrudType} from 'src/app/common/crud-types';
import {EditComponent} from '../edit/edit.component';

@Component({templateUrl: './list.component.html'})

export class ListComponent {
  type = CrudType.ReferenceCashRegister;
  component = EditComponent;
  code = 'cash_cash-register';

  constructor(
    protected cashService: CashService,
  ) {
  }

  onInfo(id) {
    this.cashService.onInfo(id);
  }

  onStatus(id) {
    this.cashService.onStatus(id);
  }
}
