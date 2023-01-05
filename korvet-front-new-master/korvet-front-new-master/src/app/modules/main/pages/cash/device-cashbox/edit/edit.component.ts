import {Component} from '@angular/core';
import {CrudType} from 'src/app/common/crud-types';
import {Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {NotifyService} from '../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../services/breadcrumbs.service';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {CashboxDevicesModel} from '../../../../../../models/reference/reference.cashbox-devices.type.models';
import {ReferenceItemModels} from '../../../../../../models/reference/reference.item.models';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';

@Component({templateUrl: './edit.component.html'})

export class EditComponent extends ReferenceItemModels {
  crudType = CrudType;
  protected listNavigate = ['cash', 'device-cashbox'];
  protected titleName = 'Терминалы оплат';

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService
  ) {
    super(CrudType.CashboxDevices, CashboxDevicesModel);
  }

  protected setModel() {
    this.formGroup = new FormGroup({
      name: new FormControl(this.item.name, [Validators.required]),
    });
  }


}
