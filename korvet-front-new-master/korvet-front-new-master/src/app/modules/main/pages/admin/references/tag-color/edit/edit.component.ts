import {Component, Inject, Optional} from '@angular/core';
import {CrudType} from 'src/app/common/crud-types';
import {BreadcrumbsService} from '../../../../../../../services/breadcrumbs.service';
import {NotifyService} from '../../../../../../../services/notify.service';
import {ActivatedRoute, Router} from '@angular/router';
import {ReferenceItemModels} from '../../../../../../../models/reference/reference.item.models';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {Store} from '@ngrx/store';
import {ReferenceTagColorModel} from '../../../../../../../models/reference/reference.tag.color.models';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';

@Component({templateUrl: './edit.component.html'})

export class EditComponent extends ReferenceItemModels {
  protected listNavigate = ['admin', 'references', 'tag-color'];
  protected titleName = 'Цвет бирки';

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService,
    @Optional() public dialogRef: MatDialogRef<EditComponent>,
    @Optional() @Inject(MAT_DIALOG_DATA) public data: {openDialog: boolean , id: string}
  ) {
    super(CrudType.ReferenceTagColor, ReferenceTagColorModel, data.id, data.openDialog);
  }

  protected setModel() {
    this.formGroup = new FormGroup({
      name: new FormControl(this.item.name, [Validators.required]),
    });
  }

}
