import {Component, OnInit} from '@angular/core';
import {CrudType} from '../../../../../../common/crud-types';
import {SettingModel} from '../../../../../../models/setting.models';

@Component({templateUrl: './edit.component.html'})

export class EditComponent implements OnInit {
  type = CrudType.Settings;
  model = SettingModel;
  titleName = 'Настройки';
  title = 'Создать';

  constructor() {
  }

  ngOnInit(): void {
  }
}
