import {Component, OnInit} from '@angular/core';
import {CashService} from '../../cash.service';
import {CrudType} from 'src/app/common/crud-types';

@Component({templateUrl: './list.component.html'})

export class ListComponent implements OnInit {
  type = CrudType.Shift;
  c = '#';
  d = 'demo';

  constructor(
    protected cashService: CashService
  ) {
  }

  ngOnInit() {
  }

  onClose(id) {
    this.cashService.onClose(id);
  }

}
