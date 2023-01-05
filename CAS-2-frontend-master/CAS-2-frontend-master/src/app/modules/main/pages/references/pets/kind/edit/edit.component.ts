import {Component, OnInit} from '@angular/core';
import {AnimalModel} from '../../../../../../../models/animal/animal.model';
import {Observable} from 'rxjs';
import {CrudType} from '../../../../../../../common/crud-types';


@Component({templateUrl: './edit.component.html'})

export class EditComponent implements OnInit {
  type = CrudType.DictionaryKind;
  titleName = 'Масть';
  title = 'Создать';

  constructor() {
  }

  ngOnInit(): void {
  }


}
