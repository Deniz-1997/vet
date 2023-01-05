import {Component, Inject, OnInit} from '@angular/core';
import {Observable, Subscription} from 'rxjs';
import {PetTemperatureModel} from '../../../../../../../../models/pet/pet-temperature.models';
import {select, Store} from '@ngrx/store';
import {ViewService} from '../../../view.service';
import {PetModel} from '../../../../../../../../models/pet/pet.models';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {CrudType} from 'src/app/common/crud-types';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelGetListLoading, getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({
  templateUrl: './change.component.html'
})
export class ChangeComponent implements OnInit {
  temperatures$: Observable<PetTemperatureModel[]>;
  temperaturesLoading$: Observable<boolean>;
  type = CrudType.PetTemperature;
  pet = new PetModel();
  private subscriptions: Subscription[] = [];

  constructor(
    public dialogRef: MatDialogRef<ChangeComponent>,
    @Inject(MAT_DIALOG_DATA) public data: any,
    public petsViewService: ViewService,
    private store: Store<CrudState>) {
    const s = petsViewService.pet.subscribe(pet => {
      if (pet && pet.id) {
        this.pet = pet;
        this.store.dispatch(new LoadGetListAction({
          type: this.type,
          params: {filter: {pet: {id: this.pet.id}}, order: {date: 'DESC'}}
        }));
      }
    });
    this.subscriptions.push(s);
  }

  ngOnInit() {
    this.temperaturesLoading$ = this.store.pipe(select(getCrudModelGetListLoading, {type: this.type}));
    this.temperatures$ = this.store.pipe(select(getCrudModelData, {type: this.type}));
  }

  onDelete(item: PetTemperatureModel) {
    this.dialogRef.close(item);
  }

}
