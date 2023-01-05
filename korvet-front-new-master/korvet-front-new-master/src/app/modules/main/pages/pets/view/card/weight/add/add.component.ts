import {Component, OnInit} from '@angular/core';
import {FormGroup} from '@angular/forms';
import {ViewService} from '../../../view.service';
import {PetModel} from '../../../../../../../../models/pet/pet.models';
import {CrudType} from 'src/app/common/crud-types';
import {AddComponent as AddTemperature} from '../../temperature/add/add.component';
import {MatDialogRef} from '@angular/material/dialog';

@Component({selector: 'app-pets-view-card-weight-add', templateUrl: './add.component.html'})

export class AddComponent implements OnInit {
  public formGroup: FormGroup;
  type = CrudType.PetWeight;
  pet = new PetModel();

  constructor(
    public petsViewService: ViewService,
    public dialogRef: MatDialogRef<AddTemperature>,
  ) {
    petsViewService.pet.subscribe(pet => {
      this.pet = pet;
    });
  }

  ngOnInit() {
  }

  afterSubmit(res): void {
    if (res.status === true && res.response && res.response.id) {
      this.petsViewService.getPet();
      this.dialogRef.close();
    }
  }

  cancel() {
    this.dialogRef.close();
  }
}
