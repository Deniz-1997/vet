import {Component, Inject, Optional} from '@angular/core';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {ReferencePetIdentifierTypeModel} from '../../../../../../../models/reference/reference.pet.identifier.type.models';
import {ReferenceItemModels} from '../../../../../../../models/reference/reference.item.models';
import {NotifyService} from '../../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../../services/breadcrumbs.service';
import {CrudType} from '../../../../../../../common/crud-types';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';

@Component({templateUrl: './edit.component.html'})

export class EditComponent extends ReferenceItemModels {
  protected listNavigate = ['admin', 'references', 'pet-identifier-type'];
  protected titleName = 'Тип идентификатора животных';

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService,
    @Optional() public dialogRef: MatDialogRef<EditComponent>,
    @Optional() @Inject(MAT_DIALOG_DATA) public data: {openDialog: boolean , id: string}
  ) {
    super(CrudType.ReferencePetIdentifierType, ReferencePetIdentifierTypeModel, data.id, data.openDialog);
  }

  protected setModel() {
    this.formGroup = new FormGroup({
      name: new FormControl(this.item.name, [Validators.required]),
    });
  }

}
