import {Component, OnInit} from '@angular/core';
import {CrudType} from 'src/app/common/crud-types';
import {EditComponent} from '../edit/edit.component';

@Component({
  selector: 'app-list',
  templateUrl: './list.component.html'
})
export class ListComponent implements OnInit {
  type = CrudType.ReferenceOrganization;
  component = EditComponent;
  code = 'cash_organization';

  constructor() {
  }

  ngOnInit() {
  }


}
