import {Injectable} from '@angular/core';
import {Store} from '@ngrx/store';
import {InfoDialogComponent} from '../../../shared/components/info-dialog/info-dialog.component';
import {MatDialog} from '@angular/material/dialog';
import {ShiftModel} from '../../../../models/cash/shift.models';
import {Router} from '@angular/router';
import {HttpClient} from '@angular/common/http';
import {interval, Subscription} from 'rxjs';
import {switchMap} from 'rxjs/operators';
import {Urls} from '../../../../common/urls';
import {CrudType} from 'src/app/common/crud-types';
import {ModalConfirmComponent} from 'src/app/modules/shared/components/modal-confirm/modal-confirm.component';
import {AuthService} from '../../../../services/auth.service';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadCreateAction, LoadGetAction} from 'src/app/api/api-connector/crud/crud.actions';

declare var $: any;

export class AsyncStatus {
  asyncStatus: string;
  status: boolean;
  response?: any;
}

@Injectable({
  providedIn: 'root'
})
export class CashService {

  public currentShift = {};

  correlationId;
  loaderInfo = false;
  loaderStatus = false;
  period = 1000;

  asyncSubscription: Subscription;
  asyncSubscriptionInfo: Subscription;
  asyncSubscriptionStatus: Subscription;
  asyncCashFlowSubscription: Subscription;

  constructor(
    protected store: Store<CrudState>,
    public dialog: MatDialog,
    private router: Router,
    private http: HttpClient,
    protected authService: AuthService,
  ) {
  }

  onOpen(id, callback?) {
    if (id) {
      this.store.dispatch(new LoadCreateAction({
        type: CrudType.CashRegisterShiftOpen, params: {
          id: id
        },
        onSuccess(res) {
          if (callback) {
            callback(res);
          }
        }
      }));
    }
  }

  onXReport(id, callback?) {
    if (id) {
      this.store.dispatch(new LoadCreateAction({
        type: CrudType.CashRegisterShiftXReport, params: id,
        onSuccess(res) {
          if (callback) {
            callback(res);
          }
        }
      }));
    }
  }

  onContinuePrint(id, callback?) {
    if (id) {
      this.store.dispatch(new LoadCreateAction({
        type: CrudType.CashRegisterContinuePrint, params: id,
        onSuccess(res) {
          if (callback) {
            callback(res);
          }
        }
      }));
    }
  }

  onClose(id, callback?) {
    if (id) {
      this.store.dispatch(new LoadCreateAction({
        type: CrudType.CashRegisterShiftClose, params: {
          id: id
        },
        onSuccess(res) {
          if (callback) {
            callback(res);
          }
        }
      }));
    }
  }

  // onCloseReguest(id) {
  //   this.store.dispatch(new LoadCreateAction({type: CrudType.CashRegisterShiftClose, params: id,
  //     onSuccess() {
  //       this.loadingClose.next(false);
  //       $.removeCookie(`shift-${this.authService.user$.value.user.id}`);
  //     },
  //     onError: () => {
  //       this.loadingClose.next(false);
  //     }
  //   }));
  // }

  onShowDocuments(id) {
    if (id) {
      this.store.dispatch(new LoadGetAction({type: CrudType.CashRegisterShiftDocuments, params: id}));
    }
  }

  onInfo(id) {
    if (id) {
      this.loaderInfo = true;
      this.store.dispatch(new LoadCreateAction({
        type: CrudType.CashRegisterInfo, params: id,
        onSuccess: (res) => {
          if (res) {
            this.getAsyncResultInfo(res.response.correlationId, (result) => {
              this.loaderInfo = false;
              const dialogRef = this.dialog.open(InfoDialogComponent, {
                data: {
                  head: 'Параметры регистрации ККМ',
                  info: result.response,
                  // info: this.rrr,
                  actions: [
                    {
                      class: 'btn-st btn-st--right btn-st--blue',
                      action: true,
                      title: 'Закрыть'
                    },
                  ],
                },
                width: '600px',
              });
            });
          }
        },
        onError: () => this.loaderInfo = false
      }));
    }
  }

  onStatus(id) {
    if (id) {
      this.loaderStatus = true;
      this.store.dispatch(new LoadCreateAction({
        type: CrudType.CashRegisterStatus, params: id,
        onSuccess: (res) => {
          if (res) {
            this.getAsyncResultStatus(res.response.correlationId, (result) => {
              this.loaderStatus = false;
              const dialogRef = this.dialog.open(InfoDialogComponent, {
                data: {
                  head: 'Отчет о текущем состоянии расчетов',
                  info: result.response,
                  // info: this.ttt,
                  actions: [
                    {
                      class: 'btn-st btn-st--right btn-st--blue',
                      action: true,
                      title: 'Закрыть'
                    },
                  ],
                }
              });
            });
          }
        },
        onError: () => this.loaderInfo = false
      }));
    }
  }

  onReset(id) {
    if (id) {
      this.store.dispatch(new LoadCreateAction({
        type: CrudType.CashReceiptReset, params: id,
        onError: res => {
          const dialogRef = this.dialog.open(ModalConfirmComponent, {
            data: {
              head: 'Ошибка сброса смены!',
              headComment: res.errors[0].message,
              actions: [
                {
                  class: 'btn-st btn-st--right btn-st--blue',
                  action: true,
                  title: 'Закрыть'
                },
              ],
            },
          });
        },
        onSuccess: res => {
          if (res.response.id) {
            window.location.reload();
          }
        },
      }));
    }
  }

  onReturn(id) {
    if (id) {
      this.store.dispatch(new LoadCreateAction({
        type: CrudType.CashReceiptReturn, params: id,
        onSuccess: res => {
          if (res.response.id) {
            this.router.navigateByUrl('/', {skipLocationChange: true}).then(() => {
              this.router.navigate(['cash', 'cash-receipt', res.response.id]).then();
            });
          }
        }
      }));
    }
  }

  onCashRegisterRegister(id, callback?) {
    if (id) {
      this.store.dispatch(new LoadCreateAction({
        type: CrudType.CashReceiptRegister,
        params: id,
        onSuccess: res => {
          if (callback) {
            callback(res);
          }
        },
        onError: res => {
          if (callback) {
            callback(res);
          }
        }
      }));
    }
  }

  onCashFlowRegister(id, callback?) {
    if (id) {
      this.store.dispatch(new LoadCreateAction({
        type: CrudType.CashFlowRegister,
        params: id,
        onSuccess: res => {
          if (callback) {
            callback(res);
          }
        },
        onError: res => {
          if (callback) {
            callback(res);
          }
        }
      }));
    }
  }

  getCashFlowAsyncResult(correlationId, callback?) {
    this.asyncCashFlowSubscription = interval(this.period).pipe(
      switchMap(() => this.http.get<AsyncStatus>(Urls.api + 'cash-flow/' + correlationId + '/register/', {})),
    ).subscribe(data => {
      if (data.asyncStatus === 'ERROR' || data.asyncStatus === 'DONE') {
        if (callback) {
          callback(data);
        }
        this.asyncCashFlowSubscription.unsubscribe();
      }
    }, (err) => {
      if (callback) {
        callback(err);
      }
    });
  }

  getAsyncResult(correlationId, callback?) {
    this.asyncSubscription = interval(this.period).pipe(
      switchMap(() => this.http.get<AsyncStatus>(Urls.apiAsyncResult + correlationId + '/', {})),
    ).subscribe(data => {
      if (data.asyncStatus === 'ERROR' || data.asyncStatus === 'DONE') {
        if (callback) {
          callback(data);
        }
        this.asyncSubscription.unsubscribe();
      }
    }, (err) => {
      if (callback) {
        callback(err);
      }
    });
  }

  getAsyncResultInfo(correlationId, callback?) {
    this.asyncSubscriptionInfo = interval(this.period).pipe(
      switchMap(() => this.http.get<AsyncStatus>(Urls.apiAsyncResult + correlationId + '/', {})),
    ).subscribe(data => {
      if (data.asyncStatus === 'ERROR' || data.asyncStatus === 'DONE') {
        if (callback) {
          callback(data);
        }
        this.asyncSubscriptionInfo.unsubscribe();
      }
    }, (err) => {
      if (callback) {
        callback(err);
      }
    });
  }

  getAsyncResultStatus(correlationId, callback?) {
    this.asyncSubscriptionStatus = interval(this.period).pipe(
      switchMap(() => this.http.get<AsyncStatus>(Urls.apiAsyncResult + correlationId + '/', {})),
    ).subscribe(data => {
      if (data.asyncStatus === 'ERROR' || data.asyncStatus === 'DONE') {
        if (callback) {
          callback(data);
        }
        this.asyncSubscriptionStatus.unsubscribe();
      }
    }, (err) => {
      if (callback) {
        callback(err);
      }
    });
  }

  unSubscribe() {

    this.loaderInfo = false;
    this.loaderStatus = false;

    if (this.asyncSubscription) {
      this.asyncSubscription.unsubscribe();
    }

    if (this.asyncSubscriptionInfo) {
      this.asyncSubscriptionInfo.unsubscribe();
    }

    if (this.asyncSubscriptionStatus) {
      this.asyncSubscriptionStatus.unsubscribe();
    }

    if (this.asyncCashFlowSubscription) {
      this.asyncCashFlowSubscription.unsubscribe();
    }
  }

  /*Получить текущую смену*/
  getCurrentShift(id, callback?) {
    if (id) {
      const that = this;
      this.currentShift[id] = new ShiftModel();
      this.store.dispatch(new LoadGetAction({
        type: CrudType.CashRegisterCurrentShift, params: id, onSuccess(res) {
          if (res && res.response && res.response.id) {
            that.currentShift[id] = new ShiftModel(res.response);
          }
          that.currentShift[id].isLoaded = true;
          if (callback) {
            callback(res);
          }
        }
      }));
    }
  }

  isReturn(item) {
    return !(item && item.type && item.type.code && ['RETURN'].indexOf(item.type.code) >= 0);
  }

  isNew(item) {
    return item && item.fiscal && item.fiscal.state && ['NEW', 'ERROR'].indexOf(item.fiscal.state.code) >= 0;
  }

  // isModeCashboxMobile(item) {
  //   const getData = function (url) {
  //     return this.http.get(url);
  //   };
  //   console.log(getData);
  //   const user = this.authService.user$.getValue();
  //   console.log(item);
  //   console.log(user.user['roles'].indexOf('ROLE_CASHIER') > -1);
  //   return true;
  //
  //   // return item && item.fiscal && item.fiscal.state && ['NEW', 'ERROR'].indexOf(item.fiscal.state.code) >= 0;
  // }

  isDone(item) {
    return item && item.fiscal && item.fiscal.state && ['DONE'].indexOf(item.fiscal.state.code) >= 0;
  }

  isPrinting(item) {
    return item && item.fiscal && item.fiscal.state && ['PRINTING'].indexOf(item.fiscal.state.code) >= 0;
  }
}
