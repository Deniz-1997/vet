import {Component, Inject, Optional} from '@angular/core';
import {ReferenceItemModels} from '../../../../../../models/reference/reference.item.models';
import {CrudType} from '../../../../../../common/crud-types';
import {select, Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {NotifyService} from '../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../services/breadcrumbs.service';
import {ShopSettingsModel} from '../../../../../../models/shop/shop.settings.models';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {AuthService} from '../../../../../../services/auth.service';
import {Subject} from 'rxjs';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadPatchAction, LoadCreateAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelCreatePatchLoading, getCrudModelCreateLoading} from 'src/app/api/api-connector/crud/crud.selectors';


@Component({
  selector: 'app-shop-settings-edit',
  templateUrl: './shop-settings-edit.component.html',
  styleUrls: ['./shop-settings-edit.component.css']
})
export class ShopSettingsEditComponent extends ReferenceItemModels {
  protected listNavigate = ['shop', 'shop-settings'];
  protected titleName = 'Настройка';
  private destroy$ = new Subject<any>();
  crudType = CrudType;
  readonly SHOP_STOCK_SETTING_HEADER: string = 'Склад магазина';
  filterUnitId: number;
  fieldsStock = {0: 'id', 1: 'name', 2: 'unit'};

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService,
    protected authService: AuthService,
    @Optional() public dialogRef: MatDialogRef<ShopSettingsEditComponent>,
    @Optional() @Inject(MAT_DIALOG_DATA) public data: {openDialog: boolean, id: string}
  ) {
    super(CrudType.ShopSettings, ShopSettingsModel, data.id, data.openDialog);
    this.authService.user$.subscribe((res) => {
      const unit_id = (this.authService.user$.value !== null) ? this.authService.user$.value.user['unit']['id'] : null;
      if (unit_id) {
        this.getUnit(unit_id);
      }
    });
    this.loading$ = this.store.pipe(select(this.item.id ? getCrudModelCreatePatchLoading : getCrudModelCreateLoading, {type: this.type}));
  }

  getUnit(unit_id) {
    if (unit_id) {
      return this.filterUnitId = unit_id;
    }
  }

  submit($event?, value?: any): void {
    if ($event) {
      $event.preventDefault();
    }
    if (this.formGroup.valid) {
      const model = {
        name: this.SHOP_STOCK_SETTING_HEADER,
        unit: {id: this.formGroup.controls.unit.value.id},
        data: {stock: {id: this.formGroup.controls.data.value.id.toString(), name: this.formGroup.controls.data.value.name}},
      };
      const action = this.item.id ? LoadPatchAction : LoadCreateAction;
      if (this.item.id) {
        model['id'] = this.item.id;
      }
      this.store.dispatch(new action({
        type: this.type,
        params: <any>model, onSuccess: (res) => {
          if (res.status === true && res.response && res.response.id) {
            const n = JSON.parse(JSON.stringify(this.listNavigate));
            if (this.data.openDialog) {
              this.dialogRef.close(res.response);
            } else {
              this.router.navigate(n).then();
            }
            /*n.push(res.response.id);*/
          }
        }
      }));
    } else {
      this.notify.handleMessage('Заполните обязательные поля', 'warning');
      this.showError = true;
    }
  }

  protected setModel() {
    this.formGroup = new FormGroup({
      data: new FormControl(this.item.data ? {id: +this.item.data.stock.id, name: this.item.data.stock.name} : null),
      unit: new FormControl(this.item.unit, [Validators.required])
    });
  }
}
