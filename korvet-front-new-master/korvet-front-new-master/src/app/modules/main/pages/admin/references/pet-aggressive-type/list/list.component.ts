import {Component, OnInit} from '@angular/core';
import {CrudType} from '../../../../../../../common/crud-types';
import {BehaviorSubject} from 'rxjs';
import {EditComponent} from '../edit/edit.component';

@Component({templateUrl: './list.component.html'})

export class ListComponent implements OnInit {

  type = CrudType.ReferencePetAggressiveType;
  component = EditComponent;
  code = 'pet-aggressive-type';
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
