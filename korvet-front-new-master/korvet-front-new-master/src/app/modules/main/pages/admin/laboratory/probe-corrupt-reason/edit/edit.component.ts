import {Component} from '@angular/core';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {ReferenceItemModels} from '../../../../../../../models/reference/reference.item.models';
import {NotifyService} from '../../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../../services/breadcrumbs.service';
import {CrudType} from '../../../../../../../common/crud-types';
import { ProbeCorruptReasonModel } from 'src/app/models/laboratory/probe-corrupt-reason.model';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';

@Component({templateUrl: './edit.component.html'})

export class EditComponent extends ReferenceItemModels {
  protected listNavigate = ['admin', 'references', 'laboratory', 'probe-corrupt-reason'];
  protected titleName = 'Причину брака пробы';

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService
  ) {
    super(CrudType.ProbeCorruptReason, ProbeCorruptReasonModel);
  }

  protected setModel() {
    this.formGroup = new FormGroup({
      name: new FormControl(this.item.name, [Validators.required]),
      sort: new FormControl(this.item.sort, []),
    });
  }

}
