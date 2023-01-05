import { Component, OnDestroy, OnInit } from '@angular/core';
import { select, Store } from '@ngrx/store';
import { CrudType } from '../../../../../../common/crud-types';
import { ActivatedRoute, Router } from '@angular/router';
import { CashReceiptModel } from '../../../../../../models/cash/cash.receipt.models';
import { BreadcrumbsService } from '../../../../../../services/breadcrumbs.service';
import { BehaviorSubject, Observable } from 'rxjs';
import { ModalConfirmComponent } from '../../../../../shared/components/modal-confirm/modal-confirm.component';
import { ModalConfirmSumComponent } from '../../../../../shared/components/modal-confirm-sum/modal-confirm-sum.component';
import { MatDialog } from '@angular/material/dialog';
import { AsyncStatus, CashService } from '../../cash.service';
import { NotifyService } from '../../../../../../services/notify.service';
import { CashboxDevicesModel } from '../../../../../../models/reference/reference.cashbox-devices.type.models';
import { EntityModel } from '../../../../../../models/entity.models';
import { FormControl } from '@angular/forms';
import { HttpClient } from '@angular/common/http';
import { Urls } from '../../../../../../common/urls';
import { AppointmentInterface } from '../../../../../../models/appointment/appointment.models';
import { AuthService } from '../../../../../../services/auth.service';
import { ShopOrderModel } from 'src/app/models/shop/shop.order.models';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetAction, LoadPatchAction, LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {UserAuthModel} from 'src/app/api/auth/auth.models';
import {getUser} from 'src/app/api/auth/auth.selectors';

@Component({ templateUrl: './view.component.html', styleUrls: ['./view.component.css'] })

export class ViewComponent implements OnInit, OnDestroy {

  formCtrl: FormControl;
  type = CrudType;
  isModeCashboxMobile = false;
  deviceId = null;
  devices: CashboxDevicesModel[] = [];
  appointment: AppointmentInterface;
  shopOrder: ShopOrderModel;
  user$: Observable<UserAuthModel>;
  user: UserAuthModel;
  public item: CashReceiptModel;
  title = 'Кассовый чек';
  loading = new BehaviorSubject(false);
  private id: number;
  private sub: any;
  private currentTotal: number;

  constructor(
    public cashService: CashService,
    protected store: Store<CrudState>,
    protected route: ActivatedRoute,
    protected router: Router,
    private notify: NotifyService,
    protected brdSrv: BreadcrumbsService,
    private dialog: MatDialog,
    private http: HttpClient,
    private authService: AuthService,
  ) {
    this.formCtrl = new FormControl();
    this.user$ = store.pipe(select(getUser));
  }

  ngOnInit() {
    this.user$.subscribe(user => {
      if (user) {
        this.user = user;
        // @ts-ignore
        this.isModeCashboxMobile = user.user.mode_cashbox_mobile;
        // @ts-ignore
        this.deviceId = user.user.cashbox_device_id;
      }
    });

    this.sub = this.route.params.subscribe(params => {
      this.id = +params['id'];
    });
    this.store.dispatch(new LoadGetAction({
      type: CrudType.CashReceipt,
      params: this.id,
      onSuccess: (res) => {
        if (res.status === true) {
          this.item = res.response;
          this.countTotalAmount();
          this.initBtnSelectCashboxDevice();
          this.loadAppointment();
          this.loadShopOrder();
        }
      }
    }));
  }

  ngOnDestroy(): void {
    this.sub.unsubscribe();
  }

  onBreakCheck() {
    // проверяем какой режим кассы выбран
    if (this.isModeCashboxMobile) {
      const selectName = this.formCtrl.value;
      let deviceId = 0;
      this.devices.map(v => {
        if (v.name === selectName) {
          deviceId = v.deviceId;
        }
      });
      // проверяем выбрали-ли кассу
      this.loading.next(true);

      const id = this.item.id;

      this.http.post<AsyncStatus>(Urls.api + 'aqsi/order/' + id, { device_id: deviceId }).subscribe(
        item => {
          this.store.dispatch(new LoadPatchAction({
            type: CrudType.User,
            params: <any>{
              id: this.user.user.id,
              cashbox_device_id: deviceId,
            }
          }));
          this.notify.handleMessage('Заказ отправлен на мобильную кассу', 'success', 5000);
        },
        () => {
          this.loading.next(false);
        },
        () => {
          this.loading.next(false);
          this.router.navigateByUrl('/', { skipLocationChange: true }).then(() => {
            this.router.navigate(['/cash/cash-receipt/', id]).then();
          });
        }
      );
    } else {
      if (this.item.paymentType.code === 'ELECTRONICALLY') {
        const dialogRef = this.dialog.open(ModalConfirmComponent, {
          data: {
            head: 'Подтвердите, что чек POS-терминала прошел',
            headComment: '(операция по карте провелась)',
            actions: [
              {
                class: 'btn-st btn-st--left btn-st--gray',
                action: false,
                title: 'Отмена'
              },
              {
                class: 'btn-st btn-st--right',
                action: true,
                title: 'Да'
              },
            ],
          }
        });

        dialogRef.afterClosed().subscribe((result: boolean) => {
          if (result) {
            this.onCashRegisterRegister();
          }
        });
      } else {
        this.onCashRegisterRegister();
      }
    }
  }

  onCashRegisterRegister() {
    this.loading.next(true);
    this.cashService.onCashRegisterRegister(this.id, (res) => {
      if (res.response.correlationId) {
        this.cashService.getAsyncResult(res.response.correlationId, (data) => {
          this.loading.next(false);
          if (data && data.asyncStatus === 'DONE') {
            this.router.navigateByUrl('/', { skipLocationChange: true }).then(() => {
              this.router.navigate(['/cash/cash-receipt/', this.item.id]).then();
            });
          }
        });
      } else {
        this.loading.next(false);
      }
    });
  }

  countTotalAmount() {
    const items = this.item.items;
    let amount = 0;
    for (let i = 0; i < items.length; i++) {
      const product = items[i];
      if (product.priceWithCharge || product.amount) {
        amount += product.priceWithCharge * product.quantity || product.amount;
      }
    }

    this.currentTotal = amount;
    return amount;
  }

  clearReceiptFromAppointment(id) {
    if (id) {
      const dialogRef = this.dialog.open(ModalConfirmSumComponent, {
        data: {
          head: `Вы уверены, что хотите открепить чек от приема?`,
          body: 'Чек будет откреплен, действие необратимо.',
          actions: [
            {
              class: 'btn-st btn-st--left btn-st--blue',
              action: false,
              title: 'Отмена'
            },
            {
              class: 'btn-st btn-st--right btn-st--red',
              action: true,
              title: 'Открепить чек'
            },
          ],
          numbersTitle: 'Введите сумму чисел для продолжения',
        },
      });

      dialogRef.afterClosed().subscribe((result: boolean) => {
        if (result) {
          if (this.appointment) {
            this.clearAppointmentCashReceipt(id);
          } else if (this.shopOrder) {
            this.clearShopOrderCashReceipt();
          }
        }
      });
    }
  }

  clearAppointmentCashReceipt(id) {
    this.loading.next(true);
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.Appointment,
      params: {
        filter: { cashReceipt: { id: id } },
      },
      onSuccess: (r) => {
        if (r.response.items.length > 0) {
          const item = r.response.items[0];
          if (item['owner']) {
            item['owner'] = { id: item['owner']['id'], name: item['owner']['name'] };
          }
          if (item.user) {
            item.user = {id: item.user.id};
          }
          item.cashReceipt = null;
          delete item.errors;
          delete item.previous;
          delete item.productItems;
          delete item.appointmentTemperature;
          delete item.appointmentWeight;
          this.store.dispatch(new LoadPatchAction({
            type: CrudType.Appointment,
            params: item,
            onSuccess: (res) => {
              this.loading.next(false);
              this.appointment = undefined;
              this.notify.handleMessage('Чек отвязан от приема', 'success', 3000);
            },
            onError: e => {
              this.notify.handleMessage('Возникла ошибка на стороне сервера', 'danger', 3000);
              this.loading.next(false);
            }
          }));
        } else {
          this.loading.next(false);
          this.notify.handleMessage('Не найден прием для данного чека', 'warning', 3000);
        }
      },
      onError: (error) => {
        this.loading.next(false);
        this.notify.handleMessage('Возникла ошибка на стороне сервера', 'danger', 3000);
      }
    }));
  }

  clearShopOrderCashReceipt() {
    this.loading.next(true);
    this.shopOrder.cashReceipt = null;
    if (!this.shopOrder['errors']) {
      delete this.shopOrder['errors'];
    }
    this.store.dispatch(new LoadPatchAction({
      type: CrudType.ShopOrders,
      params: this.shopOrder,
      onSuccess: (res) => {
        this.loading.next(false);
        this.shopOrder = undefined;
        this.notify.handleMessage('Чек отвязан от продажи', 'success', 3000);
      },
      onError: e => {
        this.notify.handleMessage('Возникла ошибка на стороне сервера', 'danger', 3000);
        this.loading.next(false);
      }
    }));
  }

  onReturn() {
    const dialogRef = this.dialog.open(ModalConfirmSumComponent, {
      data: {
        head: `Вы уверены, что хотите сделать возврат?`,
        body: 'Действие необратимо.',
        actions: [
          {
            class: 'btn-st btn-st--left btn-st--blue',
            action: false,
            title: 'Отмена'
          },
          {
            class: 'btn-st btn-st--right btn-st--red',
            action: true,
            title: 'Возврат'
          },
        ],
        numbersTitle: 'Введите сумму чисел для продолжения',
      },
    });

    dialogRef.afterClosed().subscribe((result: boolean) => {
      if (result) {
        this.cashService.onReturn(this.item.id);
      }
    });
  }

  loadAppointment() {
    const id = this.item.id;

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.Appointment,
      params: {
        filter: { cashReceipt: { id: id } },
      },
      onSuccess: (res) => {
        if (res.status === true && res.response.items.length > 0) {
          this.appointment = res.response.items[0];
        }
      }
    }));
  }

  loadShopOrder() {
    const id = this.item.id;

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ShopOrders,
      params: {
        filter: { cashReceipt: { id: id } },
      },
      onSuccess: (res) => {
        if (res.status === true && res.response.items.length > 0) {
          this.shopOrder = res.response.items[0];
        }
      }
    }));
  }

  displayFn(device?: EntityModel): string | undefined {
    return device ? device.name : undefined;
  }

  showClearReceiptBtn() {
    if (typeof this.appointment === 'undefined' && typeof this.shopOrder === 'undefined') {
      return false;
    }

    return this.authService.permissions('ROLE_ROOT');
  }

  private initBtnSelectCashboxDevice() {
    const filter = { shopId: { '!name': 0 } };

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.CashboxDevices,
      params: { filter: filter },
      onSuccess: (resp) => {
        this.devices = resp.response.items;

        if (this.deviceId !== null) {
          let name = null;

          this.devices.map(v => {
            if (v.deviceId === this.deviceId) {
              name = v.name;
            }
          });

          if (name !== null) {
            this.formCtrl.setValue(name);
          }
        }
      },
      onError: (error) => this.notify.handleMessage(error.message, 'danger', 5000)
    }));
  }
}
