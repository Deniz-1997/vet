import {Component} from '@angular/core';
import {ViewService} from '../view.service';
import {UserModels} from '../../../../../../../models/user/user.models';

@Component({templateUrl: './profile.component.html'})

export class ProfileComponent {
  user: UserModels;

  constructor(
    public userViewService: ViewService,
  ) {
    userViewService.user.subscribe(user => this.user = user);
  }

}
