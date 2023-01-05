import {Component, OnInit} from '@angular/core';
import {Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {NotifyService} from '../../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../../services/breadcrumbs.service';
import {CrudType} from '../../../../../../../common/crud-types';
import {ReferenceItemModels} from '../../../../../../../models/reference/reference.item.models';
import {FormFieldPropertyModel} from '../../../../../../../models/form-template/form-field-property.models';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';

@Component({templateUrl: './edit.component.html'})

export class EditComponent extends ReferenceItemModels implements OnInit {
  type = CrudType.FormTemplateFieldProperty;
  protected titleName = 'Свойства полей шаблонов';
  protected listNavigate = ['admin', 'references', 'form-template-field-property'];

  constructor(protected store: Store<CrudState>,
              protected router: Router,
              protected route: ActivatedRoute,
              protected notify: NotifyService,
              protected brdSrv: BreadcrumbsService) {
    super(CrudType.FormTemplateFieldProperty, FormFieldPropertyModel);
  }

  protected setModel() {
    this.formGroup = new FormGroup({
      name: new FormControl(this.item.name, [Validators.required]),
      code: new FormControl(this.item.code, [Validators.required]),
      description: new FormControl(this.item.description),
    });
  }

}
