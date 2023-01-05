import {Component, OnInit} from '@angular/core';
import {CrudType} from 'src/app/common/crud-types';
import {EditComponent} from '../edit/edit.component';
import {Params} from '@angular/router';


@Component({templateUrl: './list.component.html'})

export class ListComponent implements OnInit {
  type = CrudType.User;
  code = 'users';
  component = EditComponent;
  order: Params = {surname: 'ASC'};

  constructor() {
  }

  ngOnInit(): void {
  }

}
