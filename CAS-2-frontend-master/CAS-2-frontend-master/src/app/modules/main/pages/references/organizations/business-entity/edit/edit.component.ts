import {Component} from '@angular/core';
import {CrudType} from '../../../../../../../common/crud-types';

@Component({templateUrl: './edit.component.html'})
export class EditComponent {
  type = CrudType.ReferenceBusinessEntity;
  titleName = 'Хоз. субъект';
  title = 'Создать';
  listNavigate = ['reference', 'organization', 'business-entity'];

  constructor() {
  }
}
