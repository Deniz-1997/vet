import {Component, Input} from '@angular/core';
import {CrudType} from '../../../../../common/crud-types';
import {Store} from '@ngrx/store';
import {Router} from '@angular/router';
import {MatDialog} from '@angular/material/dialog';
import {AuthService} from '../../../../../services/auth.service';
import {ModalConfirmComponent} from '../../../../shared/components/modal-confirm/modal-confirm.component';
import {AppointmentLogsModel} from '../../../../../models/appointment/appointment-logs.models';
import {LeavingModel} from '../../../../../models/leaving/leaving.models';
import {LeavingLogsModel} from '../../../../../models/leaving/leaving-logs.models';
import {LeavingChangeStatusService} from '../../../../../services/leaving-change-status.service';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadCreateAction} from 'src/app/api/api-connector/crud/crud.actions';

@Component({
  selector: 'app-leaving-view-items',
  templateUrl: './leaving-view-items.component.html'
})
export class LeavingViewItemsComponent {
  @Input() leaving: LeavingModel;
  @Input() showFormTotal: boolean; // Управление показом формы "Итого"
  @Input() leavingLogs: LeavingLogsModel[];
  budget: number;

  constructor(
    private store: Store<CrudState>,
    protected router: Router,
    private dialog: MatDialog,
    public authService: AuthService,
    private leavingStatusChanged: LeavingChangeStatusService,
  ) {
    this.showFormTotal = true;

    if (typeof this.leavingLogs === 'undefined') {
      this.leavingLogs = [];
    }
  }

  getProductItemSum() {
    const productItems = this.leaving.productItems;
    let sum = 0;
    if (!productItems || !productItems.length) {
      return sum;
    }
    for (const i in productItems) {
      if (productItems.hasOwnProperty(i)) {
        if (productItems[i].priceWithCharge) {
          sum += Math.round(parseFloat(((productItems[i].priceWithCharge * productItems[i].quantity || 0)).toString()) * 100) / 100;
        } else {
          sum += Math.round(parseFloat((productItems[i].amount || 0).toString()) * 100) / 100;
        }
      }
    }
    this.budget = sum;
    return sum;
  }

  onCreateReceipt() {
    this.createReceipt();
  }

  createReceipt(): void {
    this.store.dispatch(new LoadCreateAction({
      type: CrudType.LeavingCashReceipt,
      params: {id: this.leaving.id},
      onSuccess: (res) => {
        console.log(res.response);
        if (res.response && res.response.id) {
          this.router.navigate(['/cash/cash-receipt/', res.response.id]).then();
        }
      }
    }));
  }


  dialogChangeStatus(status): void {

    const comment = 'Действие необратимо. Редактировать товарную часть приема будет невозможно.';

    /*if (this.formGroup.dirty) {
      comment = '<span class="text-danger">ВНИМАНИЕ!!!</span> Несохраненные правки будут отменены. <br>' +
        'Действие необратимо. Редактировать товарную часть приема будет невозможно.';
    }*/

    const dialogRef = this.dialog.open(ModalConfirmComponent, {
      data: {
        head: 'Вы точно хотите завершить выезд?',
        headComment: comment,
        actions: [
          {
            class: 'btn-st btn-st--left btn-st--gray',
            action: false,
            title: 'Нет'
          },
          {
            class: 'btn-st btn-st--right btn-st--red',
            action: true,
            title: 'Да'
          },
        ],
      }
    });

    dialogRef.afterClosed().subscribe((result: boolean) => {
      if (result) {
        this.changeStatus(status);
        if (this.budget === 0 ) {
          this.leavingStatusChanged.changeLeavingStatusCode('COMPLETED', this.leaving);
        }
      }
    });
  }

  public changeStatus(status) {
    if (this.budget === 0 && status === 'DRAFT') {
      this.leavingStatusChanged.changeLeavingStatusCode('OPENED', this.leaving);
    }
    const param = {
      id: this.leaving.id,
      code: status
    };

    this.store.dispatch(new LoadCreateAction({
      type: CrudType.LeavingState,
      params: param, onSuccess: (res) => {
        if (res.status === true && res.response && res.response.state) {
          console.log(res.response);
          if (this.leaving.state.code === 'REGISTERED') {
            console.log(1);
            this.router.navigate(['/leaving/', this.leaving.id, 'leaving-edit']).then();
          } else {
            // Хак для перегрузки компонента на текущей странице, вместо window.location.reload().
            // Взял тут: https://stackoverflow.com/a/47865035
            this.router.navigateByUrl('/', {skipLocationChange: true}).then(() => {
              this.router.navigate(['/leaving/', this.leaving.id]).then();
            });
          }
        }
      }
    }));
  }
}
