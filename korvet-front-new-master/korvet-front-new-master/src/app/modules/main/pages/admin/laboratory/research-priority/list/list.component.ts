import {Component, OnInit} from '@angular/core';
import {CrudType} from '../../../../../../../common/crud-types';
import {BehaviorSubject} from 'rxjs';

@Component({templateUrl: './list.component.html'})

export class ListComponent implements OnInit {

  type = CrudType.ResearchPriority;
  order = {};
  sort: any;

  constructor() {
    this.order = {
      level: 'ASC'
    };
    this.sort = new BehaviorSubject(this.order);
  }

  ngOnInit() {
  }
}
