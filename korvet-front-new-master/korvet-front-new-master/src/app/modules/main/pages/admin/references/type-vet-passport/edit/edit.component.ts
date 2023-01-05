import {Component} from '@angular/core';
import {ReferenceItemModels} from '../../../../../../../models/reference/reference.item.models';
import {Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {NotifyService} from '../../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../../services/breadcrumbs.service';
import {CrudType} from '../../../../../../../common/crud-types';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {VeterinaryPassportTypeModel} from '../../../../../../../models/veterinary-passport-type.models';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';

@Component({templateUrl: './edit.component.html'})

export class EditComponent extends ReferenceItemModels {
  protected listNavigate = ['admin', 'references', 'type-vet-passport'];
  protected titleName = 'Тип ветеринарных паспортов';

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService
  ) {
    super(CrudType.VeterinaryPassportType, VeterinaryPassportTypeModel);
  }

  protected setModel() {
    this.formGroup = new FormGroup({
      name: new FormControl(this.item.name, [Validators.required]),
      numberMaskFront: new FormControl(this.item.numberMaskFront),
      numberMask: new FormControl(this.item.numberMask),
      isDefault: new FormControl(!!this.item.isDefault),
    });
  }

}
