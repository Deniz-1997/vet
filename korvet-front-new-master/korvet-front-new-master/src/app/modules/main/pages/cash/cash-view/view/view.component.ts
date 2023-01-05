import {Component, OnDestroy, OnInit} from '@angular/core';
import {BehaviorSubject, Observable} from 'rxjs';
import {select, Store} from '@ngrx/store';
import {ReferenceCashRegisterModel} from '../../../../../../models/reference/reference.cash.register.models';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {CashService} from '../../cash.service';
import {NotifyService} from '../../../../../../services/notify.service';
import {AuthService} from '../../../../../../services/auth.service';
import {ModalConfirmComponent} from '../../../../../shared/components/modal-confirm/modal-confirm.component';
import {MatDialog} from '@angular/material/dialog';
import {CashRegisterShiftDocumentsModel} from '../../../../../../models/cash/cash.register.shift.documents .models';
import {CrudType} from 'src/app/common/crud-types';
import {ModalConfirmSumComponent} from 'src/app/modules/shared/components/modal-confirm-sum/modal-confirm-sum.component';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction, LoadGetAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

declare var $: any;

@Component({templateUrl: './view.component.html'})

export class ViewComponent implements OnInit, OnDestroy {
  titleName = 'Текущая смена';
  c = '#';
  d = 'demo';
  c2 = '#';
  d2 = 'demo';

  crudType = CrudType;
  type = CrudType.ReferenceOrganization;
  currentCashRegister;

  public referenceCashRegisterItems: Observable<ReferenceCashRegisterModel[]>;
  public shiftDocuments$: Observable<CashRegisterShiftDocumentsModel[]>;

  currentCashReceipts;
  currentCashFlows;

  public cashRegister: string | null = null;
  formGroup: FormGroup;
  events = {
    shift: {
      open: false
    },
    continuePrint: false,
    xReport: false
  };

  loading = new BehaviorSubject(false);

  constructor(
    protected store: Store<CrudState>,
    public cashService: CashService,
    public dialog: MatDialog,
    protected notify: NotifyService,
    public authService: AuthService,
  ) {
  }

  ngOnInit() {

    this.shiftDocuments$ = this.store.pipe(select(getCrudModelData, {type: CrudType.CashRegisterShiftDocuments}));

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

    this.formGroup = new FormGroup({
      cashRegister: new FormControl(this.currentCashRegister ? {id: this.currentCashRegister.id, name: this.currentCashRegister.name}
        : null, [Validators.required])
    });

    if (this.authService.user$.value) {
      const currentCookie = $.cookie(`shift-${this.authService.user$.value.user.id}`);
      if (currentCookie) {
        this.currentCashRegister = JSON.parse(currentCookie);
        this.formGroup.controls.cashRegister.setValue(this.currentCashRegister);
        this.onShift(this.currentCashRegister);
      }
    } else {
      this.authService.userId$.subscribe(
        (userId: number) => {
          const currentCookie = $.cookie(`shift-${userId}`);
          if (currentCookie) {
            this.currentCashRegister = JSON.parse(currentCookie);
            if (this.formGroup && !this.formGroup.get('cashRegister').value) {
              this.formGroup.controls.cashRegister.setValue(this.currentCashRegister);
              this.onShift(this.currentCashRegister);
            }
          }
        }
      );
    }

    this.formGroup.get('cashRegister').valueChanges.subscribe(
      result => {
        if (result instanceof Object) {
          this.onShift(result);
        }
      }
    );
  }

  onShift(item) {

    const that = this;
    that.cashRegister = item ? item.id : null;

    that.cashService.getCurrentShift(item.id, (res) => {
      if (res.status && res.response) {
        this.store.dispatch(new LoadGetAction({
          type: CrudType.CashRegisterShiftDocuments,
          params: item.id,
          onError: (error) => {
            this.currentCashReceipts = null;
          },
          onSuccess: result => {
            if (result) {
              this.currentCashReceipts = result.response.cashReceipts;
              this.currentCashFlows = result.response.cashFlows;
            }
          }
        }));
        this.cashService.getCurrentShift(this.formGroup.get('cashRegister').value.id);
        $.cookie(`shift-${this.authService.user$.value.user.id}`,
          `{"id":"${this.formGroup.get('cashRegister').value.id}", "name":"${this.formGroup.get('cashRegister').value.name}"}`);
      } else {
        this.currentCashReceipts = null;
      }
    });
  }

  submit() {
    console.log(this.formGroup.value);
  }

  onOpen(id) {
    const that = this;
    this.events.shift.open = true;
    this.loading.next(true);

    this.cashService.onOpen(id, (res) => {
      if (res.response.correlationId) {
        this.cashService.getAsyncResult(res.response.correlationId, (data) => {

          this.loading.next(false);

          if (data.asyncStatus === 'DONE') {
            that.events.shift.open = false;
            this.cashService.getCurrentShift(this.formGroup.get('cashRegister').value.id);
            $.cookie(`shift-${this.authService.user$.value.user.id}`,
              `{"id":"${this.formGroup.get('cashRegister').value.id}", "name":"${this.formGroup.get('cashRegister').value.name}"}`);
          }
        });
      }
    });
    if (this.authService.user$.value.user.id !== this.cashService.currentShift[this.cashRegister].cashier.id) {
      setTimeout(() => {
        return this.loading.next(false);
      }, 4000);
    }
  }

  onXReport(id) {
    const that = this;
    this.events.xReport = true;
    this.cashService.onXReport(id, (res) => {
      if (res.status) {
        that.events.xReport = false;
        this.notify.handleMessage('Команда отправлена на кассу', 'success');
      }
    });
  }

  onContinuePrint(id) {
    const that = this;
    this.events.continuePrint = true;
    this.cashService.onContinuePrint(id, (res) => {
      if (res.status) {
        that.events.continuePrint = false;
        this.notify.handleMessage('Команда отправлена на кассу', 'success');
      }
    });
  }

  onClose(id) {
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.CashRegisterShiftDocuments,
      params: id,
      onSuccess: item => {
        if (item) {
          const hasNew = item.response.cashReceipts
            .some(i => i.fiscal.state.code === 'NEW');
          if (hasNew) {
            this.openDeleteDialog(id);
          } else {
            this.Close(id);
            if (this.authService.user$.value.user.id !== this.cashService.currentShift[this.cashRegister].cashier.id) {
              setTimeout(() => {
                return this.loading.next(false);
              }, 4000);
            }
          }
        }
      }
    }));
  }

  openDeleteDialog(id) {
    if (id) {
      const dialogRef = this.dialog.open(ModalConfirmComponent, {
        data: {
          head: `Вы уверены, что хотите продолжить закрытие смены?`,
          headComment: 'Найдены открытые чеки текущей смены, для закрытия смены рекомендуется или удалить их, или отправить на печать.',
          actions: [
            {
              class: 'btn-st btn-st--left btn-st--gray',
              action: false,
              title: 'Отмена'
            },
            {
              class: 'btn-st btn-st--right btn-st--red',
              action: true,
              title: 'Закрыть смену'
            },
          ],
        }
      });

      dialogRef.afterClosed().subscribe((result: boolean) => {
        if (result) {
          this.Close(id);
        }
      });
    }
  }

  Close(id) {
    this.loading.next(true);
    this.cashService.onClose(id, (res) => {
      if (res.response.correlationId) {
        this.cashService.getAsyncResult(res.response.correlationId, (data) => {
          this.loading.next(false);

          if (data.asyncStatus === 'DONE') {
            this.cashService.getCurrentShift(this.formGroup.get('cashRegister').value.id);
            this.events.shift.open = true;
            $.removeCookie(`shift-${this.authService.user$.value.user.id}`);
          }
        });
      }
    });
  }

  onShowDocuments(id) {
    this.cashService.onShowDocuments(id);
  }

  onInfo(id) {
    this.cashService.onInfo(id);
  }

  onStatus(id) {
    this.cashService.onStatus(id);
  }

  onReturn(item) {
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
        this.cashService.onReturn(item.id);
      }
    });
  }

  onReset(id): void {
    if (id) {
      const dialogRef = this.dialog.open(ModalConfirmSumComponent, {
        data: {
          head: `Вы уверены, что хотите сбросить смену?`,
          body: 'Смена будет принудительно закрыта в КОРВЕТ, убедитесь что смена закрыта на кассе.',
          actions: [
            {
              class: 'btn-st btn-st--left btn-st--blue',
              action: false,
              title: 'Отмена'
            },
            {
              class: 'btn-st btn-st--right btn-st--red',
              action: true,
              title: 'Сбросить смену'
            },
          ],
          numbersTitle: 'Введите сумму чисел для продолжения',
        },
      });

      dialogRef.afterClosed().subscribe((result: boolean) => {
        if (result) {
          this.loading.next(true);
          this.cashService.onReset(id);
          setTimeout(() => {
            return this.loading.next(false);
          }, 4000);
        }
      });
    }
  }

  onCashRegisterRegister(id) {
    alert('Функция в разработке');
  }

  isHasOpenShift(): boolean {
    if (this.cashService.currentShift
      && this.cashService.currentShift[this.cashRegister].isLoaded
      && !this.cashService.currentShift[this.cashRegister].id) {
      return true;
    } else {
      return this.cashRegister && this.cashService.currentShift.hasOwnProperty(this.cashRegister) &&
        this.cashService.currentShift[this.cashRegister].isLoaded &&
        this.cashService.currentShift[this.cashRegister].id && this.cashService.currentShift[this.cashRegister].state
        && this.cashService.currentShift[this.cashRegister].state.code !== 'OPEN';
    }
  }

  getFullName(item) {
    if (item) {
      return ((item.surname || '') + ' ' + (item.name || '') + ' ' + (item.patronymic || '')).trim();
    } else {
      return '';
    }
  }

  ngOnDestroy() {
    this.cashService.unSubscribe();
  }
}
