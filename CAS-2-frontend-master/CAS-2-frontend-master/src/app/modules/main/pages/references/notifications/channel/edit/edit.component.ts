import {Component, OnInit} from '@angular/core';
import {ReferenceNotificationsChannelModel} from '../../../../../../../models/reference/reference.notifications.channel.models';
import {CrudType} from '../../../../../../../common/crud-types';


@Component({templateUrl: './edit.component.html'})

export class EditComponent implements OnInit {
  listNavigate = ['admin', 'references', 'notifications-channel'];
  titleName = 'Канал оповещения';
  model = ReferenceNotificationsChannelModel;
  type = CrudType.ReferenceNotificationsChannel;

  constructor() {
  }

  ngOnInit(): void {
  }


}
