import {Component, OnInit} from '@angular/core';
import {ReferenceItemModels} from '../../../../../../../models/reference/reference.item.models';
import {Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {NotifyService} from '../../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../../services/breadcrumbs.service';
import {CrudType} from '../../../../../../../common/crud-types';
import {DocumentHistoryModel} from '../../../../../../../models/document/document-history.models';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';

@Component({
  selector: 'app-product-history-view',
  templateUrl: './view.component.html'
})
export class ViewComponent extends ReferenceItemModels implements OnInit {
  protected listNavigate = ['store', 'product-history'];
  protected titleName = 'История';

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService,
  ) {
    super(CrudType.DocumentHistory, DocumentHistoryModel);
  }

  public goListUrl(): string {
    return '/' + this.listNavigate.join('/');
  }

  protected setModel() {

  }
}
