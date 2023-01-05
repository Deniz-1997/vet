import {Component, Inject, Optional} from '@angular/core';
import {ReferenceItemModels} from '../../../../../../models/reference/reference.item.models';
import {select, Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {NotifyService} from '../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../services/breadcrumbs.service';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {ReferenceCashRegisterServerModel} from '../../../../../../models/reference/reference.cash.register.server.models';
import {Observable} from 'rxjs';
import {ReferenceUnitModel} from '../../../../../../models/reference/reference.unit.models';
import {CrudType} from 'src/app/common/crud-types';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({templateUrl: './edit.component.html'})

export class EditComponent extends ReferenceItemModels {
  crudType = CrudType;
  public unitItems: Observable<ReferenceUnitModel[]>;
  protected listNavigate = ['cash', 'cash-register-server'];
  protected titleName = 'ККМ-сервер';

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService,
    @Optional() public dialogRef: MatDialogRef<EditComponent>,
    @Optional() @Inject(MAT_DIALOG_DATA) public data: {openDialog: boolean , id: string}
  ) {
    super(CrudType.ReferenceCashRegisterServer, ReferenceCashRegisterServerModel, data.id, data.openDialog);

    this.unitItems = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceUnit}));

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceUnit,
      params: {
        fields: {0: 'id', 1: 'name'},
        order: {surname: 'ASC'},
        offset: 0,
        limit: 10
      }
    }));
  }

  protected setModel() {
    this.formGroup = new FormGroup({
      name: new FormControl(this.item.name, [Validators.required]),
      unit: new FormControl((this.item.unit && this.item.unit.id)
        ? {id: this.item.unit.id, name: this.item.unit.name} : null, [Validators.required]),
    });
  }

}
