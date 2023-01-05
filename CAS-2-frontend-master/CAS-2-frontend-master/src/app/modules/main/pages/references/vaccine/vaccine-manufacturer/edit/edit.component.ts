import {Component, OnInit} from '@angular/core';
import {CrudType} from '../../../../../../../common/crud-types';

import {ManufacturerModel} from '../../../../../../../models/dictionary/manufacturer.model';


@Component({templateUrl: './edit.component.html'})

export class EditComponent implements OnInit {
  type = CrudType.DictionaryManufacturer;
  model = ManufacturerModel;
  titleName = 'Производителя';
  title = 'Создать';

  constructor() {
  }

  ngOnInit(): void {
  }


}
