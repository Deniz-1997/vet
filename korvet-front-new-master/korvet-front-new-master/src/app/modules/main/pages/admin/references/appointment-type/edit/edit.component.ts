import {Component} from '@angular/core';
import {Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {NotifyService} from '../../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../../services/breadcrumbs.service';
import {CrudType} from 'src/app/common/crud-types';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {ReferenceAppointmentTypeModel} from '../../../../../../../models/reference/reference.appointment.type.models';
import {ReferenceItemModels} from '../../../../../../../models/reference/reference.item.models';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';

@Component({templateUrl: './edit.component.html'})

export class EditComponent extends ReferenceItemModels {
  protected listNavigate = ['admin', 'references', 'appointment-type'];
  protected titleName = 'Тип обращения';

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService
  ) {
    super(CrudType.ReferenceAppointmentType, ReferenceAppointmentTypeModel);
  }

  protected setModel() {
    this.formGroup = new FormGroup({
      name: new FormControl(this.item.name, [Validators.required]),
      // sort: new FormControl(this.item.sort, [Validators.required]),
    });
  }
}
