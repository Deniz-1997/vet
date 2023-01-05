import {Component, Inject, Optional} from '@angular/core';
import {ReferenceItemModels} from '../../../../../../models/reference/reference.item.models';
import {Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {NotifyService} from '../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../services/breadcrumbs.service';
import {CrudType} from '../../../../../../common/crud-types';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {SettingModel} from '../../../../../../models/setting.models';
import {MainService} from '../main.service';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';

@Component({templateUrl: './edit.component.html'})

export class EditComponent extends ReferenceItemModels {
  protected listNavigate = ['admin', 'settings'];

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService,
    public settingsService: MainService,
    @Optional() public dialogRef: MatDialogRef<EditComponent>,
    @Optional() @Inject(MAT_DIALOG_DATA) public data: {openDialog: boolean , id: string}
  ) {
    super(CrudType.Settings, SettingModel, data.id, data.openDialog);
  }

  protected setModel() {
    this.formGroup = new FormGroup({
      key: new FormControl(this.item.key, [Validators.required]),
      value: new FormControl(this.item.value, [Validators.required]),
    });
  }
}
