import {Component, OnInit} from '@angular/core';
import {ReferenceItemModels} from '../../../../../../models/reference/reference.item.models';
import {Observable} from 'rxjs';
import {select, Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {NotifyService} from '../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../services/breadcrumbs.service';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {ShiftModel} from '../../../../../../models/cash/shift.models';
import {map} from 'rxjs/operators';
import {ReferenceCashRegisterModel} from '../../../../../../models/reference/reference.cash.register.models';
import {CrudType} from 'src/app/common/crud-types';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({templateUrl: './edit.component.html'})

export class EditComponent extends ReferenceItemModels implements OnInit {
  crudType = CrudType;
  users$: Observable<{ id: number, fullName: string }[]>;
  public referenceCashRegisterItems: Observable<ReferenceCashRegisterModel[]>;
  protected listNavigate = ['cash', 'shift'];
  protected titleName = 'Кассовая смена';
  filter = {active: true};

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService
  ) {
    super(CrudType.Shift, ShiftModel);


    this.referenceCashRegisterItems = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceCashRegister}));

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceCashRegister,
      params: {
        fields: {0: 'id', 1: 'name'},
        order: {surname: 'ASC'},
        offset: 0,
        limit: 10
      }
    }));

    this.users$ = this.store.pipe(select(getCrudModelData, {type: CrudType.User})).pipe(
      map(item => {
        return item.map(user => {
            return {id: user['id'], fullName: user.getFullName()};
          }
        );
      })
    );

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.User,
      params: <any>{
        fields: {0: 'id', 1: 'surname', 2: 'name', 3: 'patronymic'},
        order: {fullName: {middleName: 'ASC'}},
        offset: 0,
        limit: 10
      }
    }));
  }

  ngOnInit() {
    super.ngOnInit();
  }

  getFullName(cashier: { name?: string; surname: string; patronymic?: string; }): string {
    return (((cashier.surname).trim() + ' ' + (cashier.name + ' ' + cashier.patronymic).trim()).trim());
  }

  protected setModel() {
    this.formGroup = new FormGroup({
      creator: new FormControl(8, [Validators.required]),
      cashier: new FormControl(this.item.cashier ?
        {id: this.item.cashier, fullName: this.getFullName(this.item.cashier)} : null, [Validators.required]),
      cashRegister: new FormControl(this.item.cashRegister ?
        {id: this.item.cashRegister.id, name: this.item.cashRegister.name} : null, [Validators.required]),
    });
  }
}
