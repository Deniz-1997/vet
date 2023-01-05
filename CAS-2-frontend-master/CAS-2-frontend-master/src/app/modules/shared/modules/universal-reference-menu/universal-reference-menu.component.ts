import {Component, Input, OnInit} from '@angular/core';
import {select, Store} from '@ngrx/store';
import {Observable} from 'rxjs';
import {CrudType} from '../../../../common/crud-types';
import {ActionModel} from '../../../../models/action/action.models';
import {getCrudModelData, getCrudModelGetListLoading} from '../../../../api/api-connector/crud/crud.selectors';
import {CrudState} from '../../../../api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from '../../../../api/api-connector/crud/crud.actions';



@Component({
  selector: 'app-universal-reference-menu',
  templateUrl: './universal-reference-menu.component.html',
  styleUrls: ['./universal-reference-menu.component.css']
})
export class UniversalReferenceMenuComponent implements OnInit {
  @Input()
  url: string;
  @Input()
  title: string;
  @Input()
  useChildPath = false;
  parentId: number;
  displayedColumns = ['name', 'description'];
  loading$: Observable<boolean>;
  type = CrudType.Action;
  dataSource$: Observable<Array<ActionModel>>;

  constructor(protected store: Store<CrudState>) {
    this.loading$ = this.store.pipe(select(getCrudModelGetListLoading, {type: this.type}));
    if (!this.url) {
      this.url = window.location.pathname;
    }
  }

  ngOnInit(): void {
    if (this.url !== undefined) {
      this.getParentId(this.url);
    }
  }

  getParentId(url: string): void {
    this.dataSource$ = this.store.pipe(select(getCrudModelData, {type: this.type}));
    this.store.dispatch(new LoadGetListAction({
      type: this.type,
      params: {
        filter: {
          parent: {
            url: this.url
          }
        },
        order: {sort: 'ASC', name: 'ASC'},
      }
    }));
  }
}
