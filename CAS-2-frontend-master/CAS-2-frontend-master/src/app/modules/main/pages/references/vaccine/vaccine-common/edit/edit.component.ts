import {Component} from '@angular/core';
import {CrudType} from '../../../../../../../common/crud-types';
import {Observable} from 'rxjs';
import {VaccineModel} from '../../../../../../../models/dictionary/vaccine.model';


@Component({templateUrl: './edit.component.html'})

export class EditComponent {
  type = CrudType.DictionaryVaccine ;
  titleName = 'Вакцина';
  title = 'Создать';

  constructor() {
  }
}
