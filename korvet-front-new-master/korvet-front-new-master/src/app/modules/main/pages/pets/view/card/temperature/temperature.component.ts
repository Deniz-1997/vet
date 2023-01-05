import {Component, OnDestroy, OnInit} from '@angular/core';
import {PetModel} from '../../../../../../../models/pet/pet.models';
import {ViewService} from '../../view.service';
import {select, Store} from '@ngrx/store';
import {Observable, Subscription} from 'rxjs';
import {PetTemperatureModel} from '../../../../../../../models/pet/pet-temperature.models';
import {TemperatureMeasurementModels} from '../../../../../../../models/pet/temperature.measurement.models';
import {MatDialog} from '@angular/material/dialog';
import {ModalConfirmComponent} from '../../../../../../shared/components/modal-confirm/modal-confirm.component';
import {ChangeComponent} from './change/change.component';
import {AddComponent} from './add/add.component';
import {NotifyService} from '../../../../../../../services/notify.service';
import {CrudType} from 'src/app/common/crud-types';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction, LoadDeleteAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({
  selector: 'app-pets-view-card-temperature',
  templateUrl: './temperature.component.html'
})
export class TemperatureComponent implements OnInit, OnDestroy {
  pet = new PetModel();
  type = CrudType.PetTemperature;
  temperatures$: Observable<PetTemperatureModel[]>;
  currentTemperatures: TemperatureMeasurementModels = {
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
        this.currentTemperatures = pet.actualTemperature;
        this.store.dispatch(new LoadGetListAction({
          type: this.type,
          params: {filter: {pet: {id: this.pet.id}}, order: {date: 'DESC'}}
        }));
      }
    });
    this.subscriptions.push(s);
    this.temperatures$ = this.store.pipe(select(getCrudModelData, {type: this.type}));
  }

  ngOnInit() {
  }

  onAdd() {
    this.dialog.open(AddComponent, {
      width: window.innerWidth > 960 ? '30%' : '90%',
      height: '100% - 50px',
    });
  }

  onDelete(item) {
    const dialogRef = this.dialog.open(ModalConfirmComponent, {
      data: {
        head: `Вы точно хотите удалить замер температуры ${item.value}
         сделаный ${this.petsViewService.getDateFormat(this.currentTemperatures.date)}?`,
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

  onShow() {
    const dialogRef = this.dialog.open(ChangeComponent, {});
    dialogRef.afterClosed().subscribe((result: PetTemperatureModel) => {
      if (result) {
        this.onDelete(result);
      }
    });
  }

  ngOnDestroy() {
    this.subscriptions
      .forEach(s => s.unsubscribe());
  }
}
