import { Component, Input, OnInit } from '@angular/core';

import { Store } from '@ngrx/store';
import { ResearchDocumentModel } from 'src/app/models/laboratory/research-document.model';
import { CrudType } from 'src/app/common/crud-types';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';

@Component({
  selector: 'app-research-header',
  templateUrl: './research-header.component.html',
  styleUrls: ['./research-header.component.css']
})
export class ResearchHeaderComponent implements OnInit {
  @Input() model: ResearchDocumentModel;
  @Input() showResult: boolean = false;

  constructor(
    protected store: Store<CrudState>
  ) {
  }

  ngOnInit(): void {
  }
}
