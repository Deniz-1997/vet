import {Component} from '@angular/core';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {ReferenceItemModels} from '../../../../../../../../models/reference/reference.item.models';
import {NotifyService} from '../../../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../../../services/breadcrumbs.service';
import {CrudType} from '../../../../../../../../common/crud-types';
import {ReferenceNotificationsTypeModel} from '../../../../../../../../models/reference/reference.notifications.type.models';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';

@Component({templateUrl: './edit.component.html'})

export class EditComponent extends ReferenceItemModels {
  protected listNavigate = ['admin', 'references', 'notifications-type'];
  protected titleName = 'Тип оповещения';

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService
  ) {
    super(CrudType.ReferenceNotificationsType, ReferenceNotificationsTypeModel);
  }

  protected setModel() {
    this.formGroup = new FormGroup({
      name: new FormControl(this.item.name, [Validators.required]),
      template: new FormControl(this.item.template, [Validators.required]),
      required: new FormControl(this.item.required ? this.item.required : false, []),
    });
  }
}
