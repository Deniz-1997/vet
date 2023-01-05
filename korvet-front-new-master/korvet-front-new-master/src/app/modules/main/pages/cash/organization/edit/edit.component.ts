import {Component, Inject, Optional} from '@angular/core';
import {ReferenceItemModels} from '../../../../../../models/reference/reference.item.models';
import {Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {NotifyService} from '../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../services/breadcrumbs.service';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {ReferenceOrganizationModel} from '../../../../../../models/reference/reference.organization.models';
import {EnumModel} from '../../../../../../models/enum .models';
import {CrudType} from 'src/app/common/crud-types';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';

@Component({templateUrl: './edit.component.html'})

export class EditComponent extends ReferenceItemModels {
  TaxationTypeEnum: EnumModel;
  protected listNavigate = ['cash', 'organization'];
  protected titleName = 'Организацию';

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService,
    @Optional() public dialogRef: MatDialogRef<EditComponent>,
    @Optional() @Inject(MAT_DIALOG_DATA) public data: {openDialog: boolean , id: string}
  ) {
    super(CrudType.ReferenceOrganization, ReferenceOrganizationModel, data.id, data.openDialog);

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.Enum,
      params: {
        filter: {
          id: 'TaxationTypeEnum'
        }
      },
      onSuccess: (item) => {
        this.TaxationTypeEnum = item.response[0].items;
      }
    }));
  }

  protected setModel() {
    this.formGroup = new FormGroup({
      name: new FormControl(this.item.name, [Validators.required]),
      inn: new FormControl(this.item.inn, [Validators.required]),
      taxationType: new FormGroup({
        code: new FormControl(this.item.taxationType ? this.item.taxationType.code : null, [Validators.required])
      }),
    });
  }
}
