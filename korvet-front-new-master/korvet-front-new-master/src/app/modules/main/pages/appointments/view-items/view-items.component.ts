import {Component, Input} from '@angular/core';
import {AppointmentModel} from '../../../../../models/appointment/appointment.models';
import {CrudType} from '../../../../../common/crud-types';
import {Store} from '@ngrx/store';
import {Router} from '@angular/router';
import {MatDialog} from '@angular/material/dialog';
import {AuthService} from '../../../../../services/auth.service';
import {ModalConfirmComponent} from '../../../../shared/components/modal-confirm/modal-confirm.component';
import {AppointmentLogsModel} from '../../../../../models/appointment/appointment-logs.models';
import { ProbeSamplingComponent } from '../../laboratory/probe-sampling/edit/edit.component';
import { ModalProbeSamplingFormComponent } from '../../laboratory/modal-probe-sampling-form/modal-probe-sampling-form.component';
import {ChangeAppointmentStatusService} from '../../../../../services/appointment-change-status.service';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadCreateAction, LoadGetAction} from 'src/app/api/api-connector/crud/crud.actions';

@Component({
  selector: 'app-appointments-view-items',
  templateUrl: './view-items.component.html'
})
export class ViewItemsComponent {
  @Input() appointment: AppointmentModel;
  @Input() showFormTotal: boolean; // Управление показом формы "Итого"
  @Input() appointmentLogs: AppointmentLogsModel[];
  budget: number;

  constructor(
    private store: Store<CrudState>,
    protected router: Router,
    private dialog: MatDialog,
    public authService: AuthService,
    private appointmentStatusChange: ChangeAppointmentStatusService,
  ) {
    this.showFormTotal = true;

    if (typeof this.appointmentLogs === 'undefined') {
      this.appointmentLogs = [];
    }
  }

  getProductItemSum() {
    const productItems = this.appointment.productItems;
    let sum = 0;
    if ((!productItems || !productItems.length) && !(this.appointment.probeSamplings || this.appointment.probeSamplings.length)) {
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
    for (const i in this.appointment.probeSamplings) {
      sum += this.getProbeSamplingPrice(this.appointment.probeSamplings[i]);
    }
    this.budget = sum;
    return sum;
  }

  onCreateReceipt() {
    this.createReceipt();
  }

  createReceipt(): void {
    this.store.dispatch(new LoadCreateAction({
      type: CrudType.AppointmentCashReceipt, params: {id: this.appointment.id},
      onSuccess: (res) => {
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
        head: 'Вы точно хотите завершить прием?',
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
          this.appointmentStatusChange.changeAppointmentStatusCode('COMPLETED', this.appointment.id);
        }

      }
    });
  }

  public changeStatus(status) {
    if (this.budget === 0 && status === 'DRAFT') {
      this.appointmentStatusChange.changeAppointmentStatusCode('OPENED', this.appointment.id);
    }
    const param = {
      id: this.appointment.id,
      code: status
    };

    this.store.dispatch(new LoadCreateAction({
      type: CrudType.AppointmentState,
      params: param, onSuccess: (res) => {
        if (res.status === true && res.response && res.response.state) {
          if (this.appointment.state.code === 'REGISTERED') {
            this.router.navigate(['/appointments/', this.appointment.id, 'edit']).then();
          } else {
            // Хак для перегрузки компонента на текущей странице, вместо window.location.reload().
            // Взял тут: https://stackoverflow.com/a/47865035
            this.router.navigateByUrl('/', {skipLocationChange: true}).then(() => {
              this.router.navigate(['/appointments/', this.appointment.id]).then();
            });
          }
        }
      }
    }));
  }

  probeSamplingModal(probeSampling = null) {
    const dialogRef = this.dialog.open(ModalProbeSamplingFormComponent, {
      data: {
        appointment: this.appointment,
        probeSampling: probeSampling
      }
    });

    dialogRef.afterClosed().subscribe((result) => {
      if (result) {
        this.store.dispatch(new LoadGetAction({
          type: CrudType.Appointment,
          params: this.appointment.id,
          onSuccess: (res) => {
            if (res.response && res.status == true) {
              this.appointment.probeSamplings = res.response.probeSamplings;
            }
          },
        }));
      }
    });
  }

  getProbeSamplingPrice(probeSampling) {
    return ProbeSamplingComponent.getProbeSamplingAmount(probeSampling);
  }
}
