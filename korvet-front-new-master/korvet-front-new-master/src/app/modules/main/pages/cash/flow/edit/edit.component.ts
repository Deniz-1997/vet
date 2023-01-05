import {Component, OnDestroy} from '@angular/core';
import {ReferenceItemModels} from '../../../../../../models/reference/reference.item.models';
import {BehaviorSubject, Observable} from 'rxjs';
import {select, Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {NotifyService} from '../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../services/breadcrumbs.service';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {ReferenceCashRegisterModel} from '../../../../../../models/reference/reference.cash.register.models';
import {EnumModel} from '../../../../../../models/enum .models';
import {CashFlowModel} from '../../../../../../models/cash/cash.flow.models';
import {CashService} from '../../cash.service';
import {CrudType} from 'src/app/common/crud-types';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction, LoadGetAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({templateUrl: './edit.component.html'})

export class EditComponent extends ReferenceItemModels implements OnDestroy {
  crudType = CrudType;
  public referenceCashRegisterItems: Observable<ReferenceCashRegisterModel[]>;
  cashFlowTypeEnum: EnumModel;
  loading = new BehaviorSubject(false);
  protected listNavigate = ['cash', 'cash-flow'];
  protected titleName = 'Внесение / выплата';

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService,
    public cashService: CashService,
  ) {
    super(CrudType.CashFlow, CashFlowModel);

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

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.Enum,
      params: {
        filter: {
          id: 'CashFlowTypeEnum'
        }
      },
      onSuccess: (item) => {
        this.cashFlowTypeEnum = item.response[0].items;
      }
    }));
  }

  getFullName(item) {
    if (item) {
      return ((item.surname || '') + ' ' + (item.name || '') + ' ' + (item.patronymic || '')).trim();
    } else {
      return '';
    }
  }

  onCashFlowRegister(id) {
    this.loading.next(true);
    this.cashService.onCashFlowRegister(id, (res) => {
      if (res.response.correlationId) {
        this.cashService.getAsyncResult(res.response.correlationId, (data) => {
          this.loading.next(false);
          if (data && data.asyncStatus === 'DONE') {
            this.store.dispatch(new LoadGetAction({type: this.type, params: id}));
          }
        });
      } else {
        this.loading.next(false);
      }
    });
  }

  changeType() {
    if (this.id !== 'create') {
      Object.keys(this.cashFlowTypeEnum).map(element => {
        if (this.cashFlowTypeEnum[element].id === this.formGroup.get('type.code').value) {
          this.item.type.title = this.cashFlowTypeEnum[element].name;
        }
      });
    }
  }

  ngOnDestroy() {
    this.cashService.unSubscribe();
  }

  protected setModel() {

    this.formGroup = new FormGroup({
      cashRegister: new FormControl(this.item.cashRegister ? {
          id: this.item.cashRegister.id,
          name: this.item.cashRegister.name
        }
        : null, [Validators.required]),
      type: new FormGroup({
        code: new FormControl(this.item.type ? this.item.type.code : null, [Validators.required])
      }),
      total: new FormControl(this.item.total, [Validators.required]),
    });
  }
}
