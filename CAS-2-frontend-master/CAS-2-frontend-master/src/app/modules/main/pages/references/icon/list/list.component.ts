import {Component, OnInit} from '@angular/core';
import {EditComponent} from '../edit/edit.component';
import {CrudType} from '../../../../../../common/crud-types';

@Component({templateUrl: './list.component.html'})

export class ListComponent implements OnInit {

  type = CrudType.ReferenceIcon;
  component = EditComponent;
  code = 'references-icon';

  constructor() {
  }

  ngOnInit(): void {

  }

}
