import {Component, OnInit} from '@angular/core';
import {CrudType} from '../../../../../../../common/crud-types';

@Component({templateUrl: './list.component.html'})

export class ListComponent implements OnInit {
  type = CrudType.VeterinaryPassportType;

  constructor() {
  }

  ngOnInit() {
  }

}
