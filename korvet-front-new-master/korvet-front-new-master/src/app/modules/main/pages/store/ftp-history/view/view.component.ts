import {Component, OnInit} from '@angular/core';
import {ReferenceItemModels} from '../../../../../../models/reference/reference.item.models';
import {select, Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {NotifyService} from '../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../services/breadcrumbs.service';
import {CrudType} from '../../../../../../common/crud-types';
import {StoreFtpHistoryModel} from '../../../../../../models/store/ftp.history';
import {Observable} from 'rxjs';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelGetListLoading} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({
  selector: 'app-ftp-history-view',
  templateUrl: './view.component.html',
  styleUrls: ['./view.component.css']
})
export class ViewComponent extends ReferenceItemModels implements OnInit {
  protected listNavigate = ['store', 'ftp-history'];
  protected titleName = 'Историю';
  getStockLoading$: Observable<boolean>;

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService
  ) {
    super(CrudType.FtpHistory, StoreFtpHistoryModel);
    this.getStockLoading$ = this.store.pipe(select(getCrudModelGetListLoading, {type: CrudType.ReferenceStock}));
  }

  protected setModel() {

  }

  openStore(externalId: string) {
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceStock,
      params: <any>{
        filter: {externalId: externalId},
        fields: {0: 'id'},
        order: {surname: 'ASC'},
        offset: 0,
        limit: 10
      },
      onSuccess: (res) => {
        if (res.status && res.response && res.response.items.length === 1) {
          this.router.navigate(['/admin/references/stock/', res.response.items[0]['id']]);
        }
      }
    }));
  }
}
