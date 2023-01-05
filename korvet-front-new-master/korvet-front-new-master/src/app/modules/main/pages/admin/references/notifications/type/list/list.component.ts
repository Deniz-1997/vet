import {Component, OnInit} from '@angular/core';
import {CrudType} from '../../../../../../../../common/crud-types';

@Component({
  selector: 'app-owner-activity-list',
  templateUrl: './list.component.html'
})
export class ListComponent implements OnInit {

  type = CrudType.ReferenceNotificationsType;

  constructor() {
  }

  ngOnInit() {
  }
}
