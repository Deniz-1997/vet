import {Component, ContentChild, Input, OnInit, TemplateRef} from '@angular/core';
import {NgForOfContext} from '@angular/common';
import {select, Store} from '@ngrx/store';
import {Observable} from 'rxjs';
import {CrudType} from 'src/app/common/crud-types';
import {ApiParamsInterface} from 'src/app/api/api-connector/api-connector.models';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadMatchesAction} from 'src/app/api/api-connector/crud/crud.actions';
import {CrudDataInterface, CrudDataType} from 'src/app/api/api-connector/crud/crud.config';
import {getCrudModelMatches, getCrudModelMatchesLoading} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({
  selector: 'app-matches-list-view',
  templateUrl: './matches-list-view.component.html',
  styleUrls: ['./matches-list-view.component.css']
})
export class MatchesListViewComponent implements OnInit {

  @Input() title = 'Совпадения';
  @ContentChild('itemTemplate', {static: true}) itemTemplate: TemplateRef<NgForOfContext<CrudDataInterface | CrudDataType>>;
  loading$: Observable<boolean>;
  matches: CrudDataInterface[] | CrudDataType[] = [];
  @Input() private type: CrudType;
  private matches$: Observable<CrudDataInterface[] | CrudDataType[]>;

  constructor(private store: Store<CrudState>) {
  }

  ngOnInit() {
    this.matches$ = this.store.pipe(select(getCrudModelMatches, {type: this.type}));
    this.loading$ = this.store.pipe(select(getCrudModelMatchesLoading, {type: this.type}));
    this.matches$.subscribe(matches => this.matches = matches);
  }

  getMatches(params: ApiParamsInterface): void {
    this.store.dispatch(new LoadMatchesAction({
      type: this.type,
      params: params,
    }));
  }

}
