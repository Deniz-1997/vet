import {Component} from '@angular/core';
import {CrudType} from '../../../../../../../common/crud-types';

@Component({templateUrl: './edit.component.html'})

export class EditComponent {
  type = CrudType.ReferenceSubdivision;
  titleName = 'Подразделение';
  title = 'Создать';

  constructor() {
  }
}
