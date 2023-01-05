import {Component, OnInit} from '@angular/core';
import {CrudType} from '../../../../../../../common/crud-types';
import {ListFilterFieldInterface} from '../../../../../../shared/components/list-filter/list-filter.model';
import {Store} from '@ngrx/store';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';

@Component({templateUrl: './list.component.html'})

export class ListComponent implements OnInit {
  type = CrudType.FormTemplateField;
  filterFields: ListFilterFieldInterface[][];

  constructor(private store: Store<CrudState>) {
  }

  ngOnInit() {
    this.filterFields = [];
  }

}
