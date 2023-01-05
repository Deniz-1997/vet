import { Component, OnInit } from '@angular/core';
import {CrudType} from '../../../../../../../common/crud-types';
import {ReasonForLeavingEditComponent} from '../reason-for-leaving-edit/reason-for-leaving-edit.component';

@Component({
  selector: 'app-reason-for-leaving-list',
  templateUrl: './reason-for-leaving-list.component.html',
  styleUrls: ['./reason-for-leaving-list.component.css']
})
export class ReasonForLeavingListComponent implements OnInit {
  type = CrudType.ReferenceReasonForLeaving;
  component = ReasonForLeavingEditComponent;
  code = 'reason-for-leaving';

  constructor() { }

  ngOnInit(): void {
  }

}
