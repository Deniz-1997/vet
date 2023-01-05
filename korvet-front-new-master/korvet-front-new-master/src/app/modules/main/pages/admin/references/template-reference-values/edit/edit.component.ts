import {Component} from '@angular/core';
import {Observable} from 'rxjs';
import {CrudType} from '../../../../../../../common/crud-types';
import {TemplateReferenceValuesModel} from '../../../../../../../models/template/template-reference-value.models';
import {Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {NotifyService} from '../../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../../services/breadcrumbs.service';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {ReferenceItemModels} from '../../../../../../../models/reference/reference.item.models';
import {FormTemplateService} from '../../../../../../../services/form-template.service';
import {TemplateReferenceModel} from '../../../../../../../models/template/template-reference.models';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';

@Component({templateUrl: './edit.component.html'})

export class EditComponent extends ReferenceItemModels {
  public templateReferenceItems: Observable<TemplateReferenceModel[]>;
  crudType = CrudType;
  protected listNavigate = ['admin', 'references', 'template-reference-values'];
  protected titleName = 'Значения справочника конструктора';

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    private formTemplateService: FormTemplateService,
    protected brdSrv: BreadcrumbsService,
  ) {
    super(CrudType.TemplateReferenceValues, TemplateReferenceValuesModel);
    this.item.templateReference = {id: null};
    this.templateReferenceItems = this.formTemplateService.getTemplateReferences();
  }

  setModel() {
    this.formGroup = new FormGroup({
      name: new FormControl(this.item.name, [Validators.required]),
      templateReference: new FormControl(this.item.templateReference, [Validators.required]),
      // sort: new FormControl(this.item.sort, [Validators.required]),
    });
  }
}
