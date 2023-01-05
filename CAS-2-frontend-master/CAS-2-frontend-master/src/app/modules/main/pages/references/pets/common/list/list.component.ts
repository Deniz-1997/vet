import {Component, OnInit} from '@angular/core';
import {CrudType} from '../../../../../../../common/crud-types';
import {EditComponent} from '../edit/edit.component';

@Component({
  templateUrl: './list.component.html'
})
export class ListComponent implements OnInit {
  type = CrudType.DictionaryAnimal;
  component = EditComponent;
  code = 'pets-common';
  fields = {
    0: 'birthdate',
    1: 'chip',
    2: 'id',
    3: 'name',
    4: 'owner',
    vaccinations: {0: 'id', 1: 'date', station: ['name']},
    breed: ['name'],
    colour: ['name'],
    kind: ['name'],
    location: ['address']};

  constructor() {
  }

  ngOnInit(): void {
  }
}
