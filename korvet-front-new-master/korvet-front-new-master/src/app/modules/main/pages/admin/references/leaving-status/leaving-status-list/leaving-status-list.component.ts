import { Component, OnInit } from '@angular/core';
import {CrudType} from '../../../../../../../common/crud-types';
import {LeavingStatusEditComponent} from '../leaving-status-edit/leaving-status-edit.component';

@Component({
  selector: 'app-leaving-status-list',
  templateUrl: './leaving-status-list.component.html',
  styleUrls: ['./leaving-status-list.component.css']
})
export class LeavingStatusListComponent implements OnInit {
  type =  CrudType.ReferenceLeavingStatus;
  component = LeavingStatusEditComponent;
  code = 'leaving-status';

  constructor() { }

  ngOnInit(): void {
  }

}
