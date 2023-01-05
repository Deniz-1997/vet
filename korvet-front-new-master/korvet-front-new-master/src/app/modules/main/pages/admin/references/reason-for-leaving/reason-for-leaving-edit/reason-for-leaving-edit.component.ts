import {Component, Inject, OnInit, Optional} from '@angular/core';
import {ReferenceItemModels} from '../../../../../../../models/reference/reference.item.models';
import {Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {NotifyService} from '../../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../../services/breadcrumbs.service';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {CrudType} from '../../../../../../../common/crud-types';
import {ReferenceReasonForLeavingModel} from '../../../../../../../models/reference/reference.reason.for.leaving.models';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';

@Component({
  selector: 'app-reason-for-leaving-edit',
  templateUrl: './reason-for-leaving-edit.component.html',
  styleUrls: ['./reason-for-leaving-edit.component.css']
})
export class ReasonForLeavingEditComponent extends ReferenceItemModels {

  protected listNavigate = ['admin', 'references', 'reason-for-leaving'];
  protected titleName = 'Причина выезда';

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService,
    @Optional() public dialogRef: MatDialogRef<ReasonForLeavingEditComponent>,
    @Optional() @Inject(MAT_DIALOG_DATA) public data: {openDialog: boolean , id: string}
  ) {
    super(CrudType.ReferenceReasonForLeaving, ReferenceReasonForLeavingModel, data.id, data.openDialog);
  }

  protected setModel() {
    this.formGroup = new FormGroup({
      name: new FormControl(this.item.name, [Validators.required]),
    });
  }

}
