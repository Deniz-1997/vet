import {Component, OnInit} from '@angular/core';
import {CrudType} from '../../../../../../../common/crud-types';
import {AnimalModel} from '../../../../../../../models/animal/animal.model';
import {Observable} from 'rxjs';

@Component({templateUrl: './edit.component.html'})

export class EditComponent {
  type = CrudType.ReferencePath;
  titleName = 'Место';
  title = 'Создать';

  constructor() {
  }
}
