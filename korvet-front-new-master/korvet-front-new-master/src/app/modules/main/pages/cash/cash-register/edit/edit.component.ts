import {Component, Inject, Optional} from '@angular/core';
import {ReferenceItemModels} from '../../../../../../models/reference/reference.item.models';
import {select, Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {NotifyService} from '../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../services/breadcrumbs.service';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {ReferenceCashRegisterModel} from '../../../../../../models/reference/reference.cash.register.models';
import {Observable} from 'rxjs';
import {ReferenceOrganizationModel} from '../../../../../../models/reference/reference.organization.models';
import {ReferenceCashRegisterServerModel} from '../../../../../../models/reference/reference.cash.register.server.models';
import {CashService} from '../../cash.service';
import {CrudType} from 'src/app/common/crud-types';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({templateUrl: './edit.component.html'})

export class EditComponent extends ReferenceItemModels {
  crudType = CrudType;
  currentId;
  public organizationItems: Observable<ReferenceOrganizationModel[]>;
  public cashRegisterServerItems: Observable<ReferenceCashRegisterServerModel[]>;
  protected listNavigate = ['cash', 'cash-register'];
  protected titleName = 'ккм';

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService,
    protected cashService: CashService,
    @Optional() public dialogRef: MatDialogRef<EditComponent>,
    @Optional() @Inject(MAT_DIALOG_DATA) public data: {openDialog: boolean , id: string}
  ) {
    super(CrudType.ReferenceCashRegister, ReferenceCashRegisterModel, data.id, data.openDialog);

    this.organizationItems = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceOrganization}));

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceOrganization,
      params: {
        fields: {0: 'id', 1: 'name'},
        order: {surname: 'ASC'},
        offset: 0,
        limit: 10
      }
    }));

    this.cashRegisterServerItems = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceCashRegisterServer}));

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceCashRegisterServer,
      params: {
        fields: {0: 'id', 1: 'name'},
        order: {surname: 'ASC'},
        offset: 0,
        limit: 10
      }
    }));
  }

  onInfo(id) {
    this.cashService.onInfo(id);
  }

  onStatus(id) {
    this.cashService.onStatus(id);
  }

  protected setModel() {
    if (this.item.id) {
      this.currentId = this.item.id;
    }
    this.formGroup = new FormGroup({
      name: new FormControl(this.item.name, [Validators.required]),
      organization: new FormControl(this.item.organization ?
        {id: this.item.organization.id, name: this.item.organization.name} : null, [Validators.required]),
      cashRegisterServer: new FormControl(this.item.cashRegisterServer ?
        {id: this.item.cashRegisterServer.id, name: this.item.cashRegisterServer.name} : null, [Validators.required]),
      active: new FormControl(this.item.active === false ? false : true)
    });
  }
}
