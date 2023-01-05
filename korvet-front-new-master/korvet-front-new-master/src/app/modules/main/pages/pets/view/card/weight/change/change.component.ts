import {Component, Inject, OnInit} from '@angular/core';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {select, Store} from '@ngrx/store';
import {PetModel} from '../../../../../../../../models/pet/pet.models';
import {Observable, Subscription} from 'rxjs';
import {PetWeightModel} from '../../../../../../../../models/pet/pet-weight.models';
import {WeightMeasurementModel} from '../../../../../../../../models/pet/weight.measurement.models';
import {ViewService} from '../../../view.service';
import {CrudType} from 'src/app/common/crud-types';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelGetListLoading, getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({templateUrl: './change.component.html'})

export class ChangeComponent implements OnInit {
  pet = new PetModel();
  type = CrudType.PetWeight;
  weight$: Observable<PetWeightModel[]>;
  weightLoading$: Observable<boolean>;
  currentWeight: WeightMeasurementModel = {
    date: '',
    value: 0
  };
  private subscriptions: Subscription[] = [];

  constructor(
    public dialogRef: MatDialogRef<ChangeComponent>,
    @Inject(MAT_DIALOG_DATA) public data: any,
    public petsViewService: ViewService,
    private store: Store<CrudState>,
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
  }

  ngOnInit() {
    this.weightLoading$ = this.store.pipe(select(getCrudModelGetListLoading, {type: this.type}));
    this.weight$ = this.store.pipe(select(getCrudModelData, {type: this.type}));
  }

  onDelete(item: PetWeightModel) {
    this.dialogRef.close(item);
  }

}
