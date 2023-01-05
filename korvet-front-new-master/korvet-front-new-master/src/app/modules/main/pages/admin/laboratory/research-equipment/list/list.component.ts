import {Component, OnInit} from '@angular/core';
import {CrudType} from '../../../../../../../common/crud-types';
import {BehaviorSubject} from 'rxjs';

@Component({templateUrl: './list.component.html'})

export class ListComponent implements OnInit {

  type = CrudType.ResearchEquipment;
  order = {};
  sort: any;

  constructor() {
    this.order = {
      sort: 'ASC'
    };
    this.sort = new BehaviorSubject(this.order);
  }

  ngOnInit() {
  }
}
