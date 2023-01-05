import { Component, OnInit } from '@angular/core';
import {BehaviorSubject} from 'rxjs';
import {CrudType} from '../../../../../../../common/crud-types';
import {PetReasonRetiringEditComponent} from '../pet-reason-retiring-edit/pet-reason-retiring-edit.component';

@Component({
  selector: 'app-pet-reason-retiring-list',
  templateUrl: './pet-reason-retiring-list.component.html',
  styleUrls: ['./pet-reason-retiring-list.component.css']
})
export class PetReasonRetiringListComponent implements OnInit {
  type = CrudType.ReferencePetReasonRetiringType;
  component = PetReasonRetiringEditComponent;
  code = 'reason-retiring';
  order = {};
  sort: any;
  constructor() {
    this.order = {
      sort: 'ASC'
    };
    this.sort = new BehaviorSubject(this.order);

  }

  ngOnInit(): void {
  }

}
