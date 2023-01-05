import {Component} from '@angular/core';
import {CrudType} from '../../../../../../../common/crud-types';
import {EditComponent} from '../edit/edit.component';


@Component({
  templateUrl: './list.component.html'
})
export class ListComponent {

  type = CrudType.ReferenceStation;
  component = EditComponent;
  code = 'station';

  constructor() {
  }
}
