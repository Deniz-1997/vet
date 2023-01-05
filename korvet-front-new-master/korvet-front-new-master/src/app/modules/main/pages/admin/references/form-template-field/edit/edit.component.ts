import {Component, Input, OnInit} from '@angular/core';
import {CrudType} from '../../../../../../../common/crud-types';
import {ReferenceItemModels} from '../../../../../../../models/reference/reference.item.models';
import {Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {NotifyService} from '../../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../../services/breadcrumbs.service';
import {FormTemplateFieldModel} from '../../../../../../../models/form-template/form-template-field.models';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {EnumModel} from '../../../../../../../models/enum .models';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';

@Component({templateUrl: './edit.component.html'})

export class EditComponent extends ReferenceItemModels implements OnInit {
  type = CrudType.FormFieldType;
  @Input() FieldTypeEnum: EnumModel[];
  protected titleName = 'Поля шаблона';
  protected listNavigate = ['admin', 'references', 'form-template-field'];

  constructor(protected store: Store<CrudState>,
              protected router: Router,
              protected route: ActivatedRoute,
              protected notify: NotifyService,
              protected brdSrv: BreadcrumbsService) {
    super(CrudType.FormTemplateField, FormTemplateFieldModel);
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.Enum,
      params: {
        filter: {
          id: [
            'FieldTypeEnum'
          ]
        }
      },
      onSuccess: (res) => {
        res.response.map(
          item => {
            this[item.id] = item.items;
          }
        );
      }
    }));
  }

  protected setModel() {
    this.formGroup = new FormGroup({
      name: new FormControl(this.item.name, [Validators.required]),
      code: new FormControl(this.item.code, [Validators.required]),
      type: new FormControl(this.item.type, [Validators.required]),
      description: new FormControl(this.item.description),
    });
  }
}
