import {Component} from '@angular/core';
import {CrudType} from '../../../../../../../common/crud-types';
import {EditComponent} from '../edit/edit.component';

@Component({
  templateUrl: './list.component.html'
})
export class ListComponent {
  type = CrudType.ReferenceLocation;
  component = EditComponent;
  code = 'location';

  constructor() {
  }
}
