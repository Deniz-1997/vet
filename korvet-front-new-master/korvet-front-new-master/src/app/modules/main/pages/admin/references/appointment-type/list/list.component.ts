import {Component, OnInit} from '@angular/core';
import {CrudType} from 'src/app/common/crud-types';

@Component({templateUrl: './list.component.html'})

export class ListComponent implements OnInit {

  type = CrudType.ReferenceAppointmentType;

  constructor() {
  }

  ngOnInit() {
  }

}
