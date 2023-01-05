import {Component, Inject, Optional} from '@angular/core';
import {Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {NotifyService} from '../../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../../services/breadcrumbs.service';
import {CrudType} from '../../../../../../../common/crud-types';
import {TemplateReferenceModel} from '../../../../../../../models/template/template-reference.models';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {ReferenceItemModels} from '../../../../../../../models/reference/reference.item.models';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';

@Component({templateUrl: './edit.component.html'})

export class EditComponent extends ReferenceItemModels {
  public templateReferenceId;
  protected listNavigate = ['admin', 'references', 'template-reference'];
  protected titleName = 'Справочники конструктора';

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService,
    @Optional() public dialogRef: MatDialogRef<EditComponent>,
    @Optional() @Inject(MAT_DIALOG_DATA) public data: {openDialog: boolean , id: string}
  ) {
    super(CrudType.TemplateReference, TemplateReferenceModel, data.id, data.openDialog);
  }

  setModel() {
    this.formGroup = new FormGroup({
      name: new FormControl(this.item.name, [Validators.required]),
    });
  }

  ngAfterContentInit() {
    if (Number(this.id)) {
      this.templateReferenceId = this.id;
    }
  }
}
