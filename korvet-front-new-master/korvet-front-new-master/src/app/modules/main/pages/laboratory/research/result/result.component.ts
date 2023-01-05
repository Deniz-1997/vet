import { Component, Input, OnInit } from '@angular/core';

import { Store } from '@ngrx/store';
import { ResearchDocumentModel } from 'src/app/models/laboratory/research-document.model';
import { CrudType } from 'src/app/common/crud-types';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';

@Component({
  selector: 'app-research-result',
  templateUrl: './result.component.html',
  styleUrls: ['./result.component.css']
})
export class ResearchResultComponent implements OnInit {
  @Input() model: ResearchDocumentModel;
  @Input() formBorder: boolean = true;
  researchFiles = [];
  fileLoading: boolean;

  constructor(
    protected store: Store<CrudState>
  ) {
  }

  ngOnInit(): void {
    this.fileLoading = true;
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.File, 
      params: {'filter': {
        'documentId': this.model.id}, order: {date: 'DESC'}},
      onSuccess: (res) => {
        if (res.response && res.status == true) {
          this.researchFiles = res.response.items;
        }
        this.fileLoading = false;
      },
      onError: _=> {this.fileLoading = false;}
    }));
  }
}
