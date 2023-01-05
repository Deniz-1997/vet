import {Component, OnInit} from '@angular/core';
import {CrudType} from '../../../../../../../common/crud-types';

@Component({templateUrl: './edit.component.html'})

export class EditComponent implements OnInit {
  type = CrudType.DictionaryDisease;
  titleName = 'Заболевание';
  title = 'Создать';

  constructor() {
  }

  ngOnInit(): void {
  }


}
