import {Component, OnInit} from '@angular/core';
import {CrudType} from '../../../../../../../common/crud-types';
import {AnimalModel} from '../../../../../../../models/animal/animal.model';
import {Observable} from 'rxjs';

@Component({templateUrl: './edit.component.html'})

export class EditComponent {
  type = CrudType.ReferenceLocation;
  titleName = 'Местоположение';
  title = 'Создать';

  constructor() {
  }
}
