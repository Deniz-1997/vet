import {Component, Inject, OnInit, Optional} from '@angular/core';
import {Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {NotifyService} from '../../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../../services/breadcrumbs.service';
import {CrudType} from '../../../../../../../common/crud-types';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {ReferencePetReasonRetiringModel} from '../../../../../../../models/reference/reference.pet.reason.retiring.models';
import {ReferenceItemModels} from '../../../../../../../models/reference/reference.item.models';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';

@Component({
  selector: 'app-pet-reason-retiring-edit',
  templateUrl: './pet-reason-retiring-edit.component.html',
  styleUrls: ['./pet-reason-retiring-edit.component.css']
})
export class PetReasonRetiringEditComponent extends ReferenceItemModels {

  protected listNavigate = ['admin', 'references', 'reason-retiring'];
  protected titleName = 'Причина выбытия животного';

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService,
    @Optional() public dialogRef: MatDialogRef<PetReasonRetiringEditComponent>,
    @Optional() @Inject(MAT_DIALOG_DATA) public data: {openDialog: boolean , id: string}
  ) {
    super(CrudType.ReferencePetReasonRetiringType, ReferencePetReasonRetiringModel, data.id, data.openDialog);
  }

  protected setModel() {
    this.formGroup = new FormGroup({
      name: new FormControl(this.item.name, [Validators.required]),
    });
  }

}
