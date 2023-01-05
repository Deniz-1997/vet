import {Component, OnDestroy, OnInit} from '@angular/core';
import {ViewService} from '../../view.service';
import {select, Store} from '@ngrx/store';
import {Observable, Subscription} from 'rxjs';
import {PetWeightModel} from '../../../../../../../models/pet/pet-weight.models';
import {PetModel} from '../../../../../../../models/pet/pet.models';
import {WeightMeasurementModel} from '../../../../../../../models/pet/weight.measurement.models';
import {MatDialog} from '@angular/material/dialog';
import {AddComponent} from './add/add.component';
import {ChangeComponent} from './change/change.component';
import {ModalConfirmComponent} from '../../../../../../shared/components/modal-confirm/modal-confirm.component';
import {NotifyService} from '../../../../../../../services/notify.service';
import {CrudType} from 'src/app/common/crud-types';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction, LoadDeleteAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({
  selector: 'app-pets-view-card-weight',
  templateUrl: './weight.component.html'
})
export class WeightComponent implements OnInit, OnDestroy {
  pet = new PetModel();
  type = CrudType.PetWeight;
  weight$: Observable<PetWeightModel[]>;
  currentWeight: WeightMeasurementModel = {
    date: '',
    value: 0
  };
  private subscriptions: Subscription[] = [];

  constructor(
    public petsViewService: ViewService,
    private store: Store<CrudState>,
    private dialog: MatDialog,
    private notify: NotifyService,
  ) {
    const s = petsViewService.pet.subscribe(pet => {
      if (pet && pet.id) {
        this.pet = pet;
        this.currentWeight = pet.actualWeight;
        this.store.dispatch(new LoadGetListAction({
          type: this.type,
          params: {filter: {pet: {id: this.pet.id}}, order: {date: 'DESC'}}
        }));
      }
    });
    this.subscriptions.push(s);
    this.weight$ = this.store.pipe(select(getCrudModelData, {type: this.type}));
  }

  ngOnInit() {
  }

  onAdd() {
    this.dialog.open(AddComponent, {});
  }

  onShow() {
    const dialogRef = this.dialog.open(ChangeComponent, {});
    dialogRef.afterClosed().subscribe((result: PetWeightModel) => {
      if (result) {
        this.onDelete(result);
      }
    });
  }

  onDelete(item: PetWeightModel) {
    const dialogRef = this.dialog.open(ModalConfirmComponent, {
      data: {
        head: `Вы точно хотите удалить замер веса ${item.value}
         сделаный ${this.petsViewService.getDateFormat(this.currentWeight.date)}?`,
        headComment: 'Действие необратимо',
        actions: [
          {
            class: 'btn-st btn-st--left btn-st--gray',
            action: false,
            title: 'Отмена'
          },
          {
            class: 'btn-st btn-st--right btn-st--red',
            action: true,
            title: 'Удалить'
          },
        ],
      }
    });

    dialogRef.afterClosed().subscribe((result: boolean) => {
      if (result) {
        this.store.dispatch(new LoadDeleteAction({
          type: this.type,
          params: {
            id: item.id
          },
          onSuccess: () => {
            this.petsViewService.getPet();
          },
          onError: (error) => this.notify.handleMessage(error.message, 'danger', 5000)
        }));
      }
    });
  }

  ngOnDestroy() {
    this.subscriptions
      .forEach(s => s.unsubscribe());
  }

}
