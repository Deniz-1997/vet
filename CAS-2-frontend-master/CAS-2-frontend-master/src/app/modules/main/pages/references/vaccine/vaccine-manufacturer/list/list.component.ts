import {Component, OnInit} from '@angular/core';
import {CrudType} from '../../../../../../../common/crud-types';
import {EditComponent} from '../edit/edit.component';
import {Observable} from 'rxjs';
import {ActionGroupModel} from '../../../../../../../models/action/action.group.models';
import {ReferenceBreedModel} from '../../../../../../../models/reference/reference.breed.models';


@Component({
  templateUrl: './list.component.html'
})
export class ListComponent implements OnInit {

  type = CrudType.DictionaryManufacturer;
  component = EditComponent;
  code = 'vaccine-manufacturer';

  constructor() {
  }

  ngOnInit(): void {
  }
}
