import {Component, Input} from '@angular/core';
import {CashReceiptModel} from '../../../../../../models/cash/cash.receipt.models';

@Component({selector: 'app-cash-receipt-items-view', templateUrl: './items-view.component.html'})

export class ItemsViewComponent {

  @Input() item: CashReceiptModel;

  constructor() {
  }

}
