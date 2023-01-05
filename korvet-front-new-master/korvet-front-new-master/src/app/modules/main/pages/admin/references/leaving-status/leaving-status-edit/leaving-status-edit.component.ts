import {Component, Inject, Optional} from '@angular/core';
import {ReferenceItemModels} from '../../../../../../../models/reference/reference.item.models';
import {Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {NotifyService} from '../../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../../services/breadcrumbs.service';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {ReferenceLeavingStatusModel} from '../../../../../../../models/reference/reference.leaving.status.models';
import {CrudType} from '../../../../../../../common/crud-types';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';

@Component({
  selector: 'app-leaving-status-edit',
  templateUrl: './leaving-status-edit.component.html',
  styleUrls: ['./leaving-status-edit.component.css']
})
export class LeavingStatusEditComponent extends ReferenceItemModels {

  protected listNavigate = ['admin', 'references', 'leaving-status'];
  protected titleName = 'Статус выезда';

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService,
    @Optional() public dialogRef: MatDialogRef<LeavingStatusEditComponent>,
    @Optional() @Inject(MAT_DIALOG_DATA) public data: {openDialog: boolean , id: string}
  ) {
    super(CrudType.ReferenceLeavingStatus, ReferenceLeavingStatusModel, data.id, data.openDialog);
  }

  protected setModel() {
    this.formGroup = new FormGroup({
      code: new FormControl(this.item.code, [Validators.required]),
      name: new FormControl(this.item.name, [Validators.required]),
      color: new FormControl(this.item.color, []),
    });
  }

}
