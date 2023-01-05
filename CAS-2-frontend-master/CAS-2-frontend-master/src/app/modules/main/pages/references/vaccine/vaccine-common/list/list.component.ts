import {Component, OnInit} from '@angular/core';
import {CrudType} from '../../../../../../../common/crud-types';
import {EditComponent} from '../edit/edit.component';


@Component({
  templateUrl: './list.component.html'
})
export class ListComponent implements OnInit {

  type = CrudType.DictionaryVaccine;
  component = EditComponent;
  code = 'vaccine-common';
  fields = {
    0: 'id',
    1: 'name',
    2: 'createdAt',
    3: 'invalid',
    4: 'updatedAt',
    5: 'activityDuration',
    6: 'show',
    vaccines: ['name'],
    diseases: ['name'],
    manufacturer: ['name']};

  constructor() {
  }

  ngOnInit(): void {
  }
}
