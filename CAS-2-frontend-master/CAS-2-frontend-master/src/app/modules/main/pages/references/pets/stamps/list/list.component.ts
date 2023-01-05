import {Component, OnInit} from '@angular/core';
import {CrudType} from '../../../../../../../common/crud-types';
import {EditComponent} from '../edit/edit.component';

@Component({
  templateUrl: './list.component.html'
})

export class ListComponent implements OnInit {

  type = CrudType.DictionaryAnimalStamps;
  component = EditComponent;
  code = 'pets-stamps';
  fields = {
    0: 'createdAt',
    1: 'id',
    2: 'isCurrent',
    3: 'name',
    4: 'show',
    5: 'stampDate',
    6: 'updatedAt',
    animal: ['name', 'id']};

  constructor() {
  }

  ngOnInit(): void {
  }
}
