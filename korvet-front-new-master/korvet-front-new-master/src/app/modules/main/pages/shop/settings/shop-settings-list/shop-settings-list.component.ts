import { Component, OnInit } from '@angular/core';
import {CrudType} from '../../../../../../common/crud-types';

import {Store} from '@ngrx/store';
import {ShopSettingsEditComponent} from '../shop-settings-edit/shop-settings-edit.component';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';

@Component({
  selector: 'app-shop-settings-list',
  templateUrl: './shop-settings-list.component.html',
  styleUrls: ['./shop-settings-list.component.css']
})
export class ShopSettingsListComponent implements OnInit {
  type = CrudType.ShopSettings;
  component = ShopSettingsEditComponent;
  code = 'shop-settings';

  constructor(
    protected store: Store<CrudState>
  ) { }

  ngOnInit(): void {
  }

}
