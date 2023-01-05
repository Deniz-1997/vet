import {Component, OnInit} from '@angular/core';

import {Store} from '@ngrx/store';
import {CrudType} from 'src/app/common/crud-types';
import {ListFilterFieldInterface} from 'src/app/modules/shared/components/list-filter/list-filter.model';
import {ListFilterTypeEnum} from 'src/app/modules/shared/components/list-filter/list-filter.enum';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';

@Component({
  selector: 'app-research-document-list',
  templateUrl: './list.component.html',
  styleUrls: ['./list.component.css']
})
export class ResearchDocumentListComponent implements OnInit {
  type = CrudType.ResearchDocument;
  filterFields: ListFilterFieldInterface[][];
  order = {};
  c = '#';
  d = 'demo';

  constructor(
    protected store: Store<CrudState>
  ) {
    this.order = {
      number: 'DESC'
    };
  }

  ngOnInit(): void {
    this.filterFields = [
      [
        {
          mutableSearchType: CrudType.ResearchDocument,
          type: ListFilterTypeEnum.date,
          prop: '>=date',
          head: {value: 'Дата исследования от'}
        },
        {
          mutableSearchType: CrudType.ResearchDocument,
          type: ListFilterTypeEnum.date,
          prop: '<=date',
          head: {value: 'до'}
        },
        {
          mutableSearchType: CrudType.ResearchDocument,
          type: ListFilterTypeEnum.autocomplete,
          prop: 'research',
          field: 'name',
          head: {value: 'Исследование'},
          attributes: {
            optionsType: CrudType.Research
          },
        }
      ],
    ];
  }
}
