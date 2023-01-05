import {Component} from '@angular/core';
import {CrudType} from '../../../../../../../common/crud-types';

@Component({templateUrl: './edit.component.html'})

export class EditComponent {
  type = CrudType.ReferenceDisinfectants;
  titleName = 'Дез. средства';
  title = 'Создать';

  constructor() {
  }
}
