import {Component, OnInit} from '@angular/core';
import {CrudType} from '../../../../../../common/crud-types';
import {MainService} from '../main.service';
import {EditComponent} from '../edit/edit.component';

@Component({templateUrl: './list.component.html'})

export class ListComponent implements OnInit {

  type = CrudType.Settings;
  component = EditComponent;
  code = 'settings';

  constructor(
    public settingsService: MainService,
  ) {
  }

  ngOnInit() {
  }

}
