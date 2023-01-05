import {Component, OnInit} from '@angular/core';
import {CrudType} from '../../../../../../common/crud-types';

@Component({
  selector: 'app-ftp-history-list',
  templateUrl: './list.component.html',
  styleUrls: ['./list.component.scss']
})
export class ListComponent implements OnInit {
  type = CrudType.FtpHistory;
  c = '#';
  d = 'demo';

  constructor() {
  }

  ngOnInit() {
  }

  hasError(item): boolean {
    return item && item.report && item.report.errors && item.report.errors[0] && item.report.errors[0].type;
  }

  checkReport(report) {
    return Object.keys(report).length > 0 && report.hasOwnProperty('totalCount');
  }
}
