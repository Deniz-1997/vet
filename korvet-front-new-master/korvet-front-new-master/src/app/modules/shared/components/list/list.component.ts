import {Component, Input, OnInit, ViewChild} from '@angular/core';
import {AbstractResources} from '../../../../resources/abstract-resources';
import {DomSanitizer} from '@angular/platform-browser';
import {ViewContainerDirective} from '../../directives/view-container.directive';
import {ColumnListItems} from '../../../../interfaces/list.interface';
import {ListFilterComponent} from '../list-filter/list-filter.component';
import {ListActionComponent} from '../list-action/list-action.component';
import {Observable} from 'rxjs';
import {select, Store} from '@ngrx/store';
import {map} from 'rxjs/operators';
import {CrudType} from 'src/app/common/crud-types';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData, getCrudModelGetListLoading} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({
  selector: 'app-list',
  templateUrl: './list.component.html',
  styleUrls: ['./list.component.css'],
})
export class ListComponent implements OnInit {
  @Input() columns: ColumnListItems[];
  @Input() options: { title: string, api: AbstractResources, type: CrudType, words: string[], linkAdd: string };
  @ViewChild(ViewContainerDirective, {static: true}) appViewContainer: ViewContainerDirective;
  @Input() filter: ListFilterComponent;
  @Input() action: ListActionComponent;

  items$: Observable<any[]>;
  loading$: Observable<boolean>;
  items: string[] = [];
  totalCount = 0;

  constructor(
    private sanitizer: DomSanitizer,
    private store: Store<CrudState>) {
  }

  ngOnInit() {
    if (!this.options.title) {
      this.options.title = '';
    }
    this.store.dispatch(new LoadGetListAction({type: this.options.type, params: {order: {'id': 'DESC'}, limit: 40, offset: 0}}));
    this.items$ = this.store.pipe(select(getCrudModelData, {type: this.options.type}), map(data => <any[]>data));
    this.loading$ = this.store.pipe(select(getCrudModelGetListLoading, {type: this.options.type}));
  }

  getData(row, col: any): any {
    let res: any = row[col.name];
    if (col.name.indexOf('.') > -1) {
      const arName = col.name.split('.');
      let i = 0;
      while (i < arName.length) {
        res = res ? res[arName[i]] : row[arName[i]];
        i++;
      }
    }
    if (col.hasOwnProperty('cellFunction')) {
      return col['cellFunction'](row, col, res);
    } else if (col.hasOwnProperty('cellTemplate')) {
      return this.sanitizer.bypassSecurityTrustHtml(col['cellTemplate']);
    }
    return res;
  }

  load() {
    /*this.listService.setOptions(this.options).load(this);*/
    return false;
  }
}
