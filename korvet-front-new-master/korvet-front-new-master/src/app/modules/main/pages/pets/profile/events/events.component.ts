import {Component, Input, OnInit} from '@angular/core';
import {Observable} from 'rxjs';
import {EventModel} from '../../../../../../models/event.models';
import {UsersService} from '../../../../../../services/users.service';
import {ReferenceEventStatusInterface} from '../../../../../../models/reference/reference.event.status';

@Component({selector: 'app-pets-profile-events', templateUrl: './events.component.html'})

export class EventsComponent implements OnInit {
  @Input() events$: Observable<EventModel[]>;
  @Input() pet$: Observable<EventModel[]>;
  @Input() limit = 0;

  constructor(
    public userApiService: UsersService,
  ) {
  }

  ngOnInit() {
  }

  getDate(date: string) {
    return date.substr(0, 10);
  }

  getTime(date: string) {
    return date.substr(11, 5);
  }

  showAll($event) {
    if ($event) {
      $event.preventDefault();
    }
    this.limit = 0;
  }

  getStatus(status?: ReferenceEventStatusInterface) {
    return status ? status.name : '-';
  }
}
