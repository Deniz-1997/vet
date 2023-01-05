import { Component, Input, OnInit } from '@angular/core';

import { Store } from '@ngrx/store';
import { CrudType } from 'src/app/common/crud-types';
import { ListFilterFieldInterface } from 'src/app/modules/shared/components/list-filter/list-filter.model';
import { ListFilterTypeEnum } from 'src/app/modules/shared/components/list-filter/list-filter.enum';
import { ResearchHistoryModel } from 'src/app/models/laboratory/research-history.model';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';

@Component({
  selector: 'app-research-history-list',
  templateUrl: './history.component.html',
  styleUrls: ['./history.component.css']
})
export class ResearchHistoryComponent implements OnInit {
  @Input() id: string;
  historyLoading = false;
  type = CrudType.ResearchHistory;
  historyList: ResearchHistoryModel[] = [];

  constructor(
    protected store: Store<CrudState>
  ) {
  }

  ngOnInit(): void {
    this.historyLoading = true;
    this.store.dispatch(new LoadGetListAction({
      type: this.type, 
      params: {
        filter: {
          researchDocumentId: this.id
        }
      },
      onSuccess: (res) => {
        if (res.response && res.status == true) {
          this.historyList = res.response.items;
        }
        this.historyLoading = false;
      },
      onError: _=> {
        this.historyLoading = false;
      }
    }));
  }
}
