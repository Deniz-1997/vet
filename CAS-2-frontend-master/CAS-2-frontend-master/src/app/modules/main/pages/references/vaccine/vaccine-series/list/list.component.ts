import {Component, OnInit} from '@angular/core';
import {CrudType} from '../../../../../../../common/crud-types';
import {EditComponent} from '../edit/edit.component';

@Component({
  templateUrl: './list.component.html'
})
export class ListComponent implements OnInit {

  type = CrudType.DictionaryVaccineSeries;
  component = EditComponent;
  code = 'vaccine-series';
  fields = {
    0: 'createdAt',
    1: 'expires',
    2: 'id',
    3: 'isInvalid',
    4: 'produced',
    5: 'serialNumber',
    6: 'show',
    7: 'updatedAt',
    vaccine: ['name']};

  constructor() {
  }

  ngOnInit(): void {
  }
}
