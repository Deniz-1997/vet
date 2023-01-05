import {Component, OnInit} from '@angular/core';
import {CrudType} from 'src/app/common/crud-types';
import {EditComponent} from '../edit/edit.component';

@Component({templateUrl: './list.component.html'})

export class ListComponent implements OnInit {
  type = CrudType.ReferenceShelter;
  component = EditComponent;
  code = 'referenceShelter';

  constructor() {
  }

  ngOnInit() {
  }

}
