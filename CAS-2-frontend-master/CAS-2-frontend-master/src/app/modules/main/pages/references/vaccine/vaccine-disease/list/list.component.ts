import {Component, OnInit} from '@angular/core';
import {CrudType} from '../../../../../../../common/crud-types';
import {EditComponent} from '../edit/edit.component';


@Component({
  templateUrl: './list.component.html'
})
export class ListComponent implements OnInit {

  type = CrudType.DictionaryDisease;
  component = EditComponent;
  code = 'vaccine-disease';
  fields = {
    0: 'id',
    1: 'name',
    2: 'createdAt',
    3: 'isInvalid',
    4: 'updatedAt',
    vaccines: ['name']};

  constructor() {
  }

  ngOnInit(): void {
  }
}
