import {Component, OnInit} from '@angular/core';
import {ReferenceNotificationsTypeModel} from '../../../../../../../models/reference/reference.notifications.type.models';
import {CrudType} from '../../../../../../../common/crud-types';




@Component({templateUrl: './edit.component.html'})

export class EditComponent implements OnInit {
  listNavigate = ['admin', 'references', 'notifications-type'];
  titleName = 'Тип оповещения';
  model = ReferenceNotificationsTypeModel;
  type = CrudType.ReferenceNotificationsType;

  constructor() {
  }

  ngOnInit(): void {
  }
}
