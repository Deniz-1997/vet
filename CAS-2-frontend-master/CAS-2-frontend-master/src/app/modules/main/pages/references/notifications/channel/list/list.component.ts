import {Component, OnInit} from '@angular/core';
import {CrudType} from '../../../../../../../common/crud-types';
import {EditComponent} from '../edit/edit.component';

@Component({
  templateUrl: './list.component.html'
})
export class ListComponent implements OnInit {

  type = CrudType.ReferenceNotificationsChannel;
  component = EditComponent;
  code = 'notifications-channel';

  constructor() {
  }

  ngOnInit(): void {
  }
}
