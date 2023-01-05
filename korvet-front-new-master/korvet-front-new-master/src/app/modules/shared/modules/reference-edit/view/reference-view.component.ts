import {Component, Input, OnInit} from '@angular/core';
import {MainService} from '../../../../main/pages/admin/settings/main.service';
import {CrudType} from '../../../../../common/crud-types';
import {CashService} from '../../../../main/pages/cash/cash.service';
import {Store} from '@ngrx/store';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';

@Component({
  selector: 'app-reference-view',
  templateUrl: './reference-view.component.html',
})
export class ReferenceViewComponent implements OnInit {
  @Input() item;
  @Input() type;
  crudType = CrudType;
  iconCode: string;

  constructor(public settingsService: MainService,
              protected store: Store<CrudState>,
              protected cashService: CashService) {

  }

  ngOnInit() {
  }
  onInfo(id) {
    this.cashService.onInfo(id);
  }

  onStatus(id) {
    this.cashService.onStatus(id);
  }

  lengthProducts(item): number {
    let count = item.products.length;

    if (count) {
      item.products.map(
        product => {
          count += product.children.length;
          return product;
        }
      );
    }

    return count;
  }
}

