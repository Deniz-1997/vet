import {Component, OnInit} from '@angular/core';
import {CrudType} from '../../../../../../../common/crud-types';
import {EditComponent} from '../edit/edit.component';

@Component({
  templateUrl: './list.component.html'
})
export class ListComponent implements OnInit {

  type = CrudType.DictionaryAnimalLivingPlaces;
  component = EditComponent;
  code = 'pets-living';
  fields = {
    0: 'address',
    1: 'arrivalDate',
    2: 'createdAt',
    3: 'id',
    4: 'isCurrent',
    5: 'note',
    6: 'show',
    7: 'updatedAt',
    country: ['name'],
    location: ['name']};

  constructor() {
  }

  ngOnInit(): void {
  }
}
