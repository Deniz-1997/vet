import {Component, OnInit} from '@angular/core';
import {ViewService} from '../../../view.service';
import {PetModel} from '../../../../../../../../models/pet/pet.models';
import {MatDialogRef} from '@angular/material/dialog';
import {CrudType} from 'src/app/common/crud-types';

@Component({
  templateUrl: './add.component.html'
})
export class AddComponent implements OnInit {
  type = CrudType.PetTemperature;
  pet = new PetModel();

  constructor(
    public petsViewService: ViewService,
    public dialogRef: MatDialogRef<AddComponent>,
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
