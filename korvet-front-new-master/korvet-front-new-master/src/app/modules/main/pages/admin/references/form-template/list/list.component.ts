import {Component, OnInit} from '@angular/core';
import {CrudType} from '../../../../../../../common/crud-types';
import {Store} from '@ngrx/store';
import {SearchModels} from '../../../../../../../models/search.models';
import {ListFilterTypeEnum} from '../../../../../../shared/components/list-filter/list-filter.enum';
import {ListFilterFieldInterface} from '../../../../../../shared/components/list-filter/list-filter.model';
import {BreadcrumbsService} from '../../../../../../../services/breadcrumbs.service';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';

@Component({templateUrl: './list.component.html'})

export class ListComponent extends SearchModels implements OnInit {
  type = CrudType.FormTemplate;
  filterFields: ListFilterFieldInterface[][];

  constructor(
    protected store: Store<CrudState>,
    protected brdSrv: BreadcrumbsService,
  ) {
    super();
    this.store.dispatch(new LoadGetListAction({type: CrudType.FormTemplate}));
  }

  ngOnInit() {
    super.ngOnInit();
    this.setBreadcrumbs();
    this.filterFields = [
      [
        {
          mutableSearchType: CrudType.FormTemplate,
          type: ListFilterTypeEnum.select,
          head: {value: 'Архивный'},
          prop: 'active',
          attributes: {
            options: [
              {value: 0, name: 'Да'},
              {value: 1, name: 'Нет'}
            ]
          }
        }
      ],
    ];
  }

  public setBreadcrumbs() {
    if (this.brdSrv) {
      this.brdSrv.deleteIndex(2);
    }
  }
}
