import {Component, OnInit} from '@angular/core';
import {CrudType} from '../../../../../../common/crud-types';

@Component({
  templateUrl: './list.component.html',
  styleUrls: ['./list.component.css']
})
export class ListComponent implements OnInit {

  type = CrudType.CashboxDevices;
  c = '#';
  d = 'demo';

  constructor() {
  }

  ngOnInit() {
  }
}
