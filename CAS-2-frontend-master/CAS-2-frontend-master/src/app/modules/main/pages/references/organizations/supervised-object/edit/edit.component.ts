import {Component} from '@angular/core';
import {CrudType} from '../../../../../../../common/crud-types';

@Component({templateUrl: './edit.component.html'})

export class EditComponent {
  type = CrudType.ReferenceSupervisedObject ;
  titleName = 'Поднадзорный объект';
  title = 'Создать';

  constructor() {
  }
}
