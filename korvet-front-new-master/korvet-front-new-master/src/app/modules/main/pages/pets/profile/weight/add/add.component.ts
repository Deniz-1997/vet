import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {FormGroup} from '@angular/forms';
import {CrudType} from 'src/app/common/crud-types';
import {PetModel} from 'src/app/models/pet/pet.models';
import {ViewService} from '../../../view/view.service';
import {MatDialogRef} from '@angular/material/dialog';
import {AddComponent as PetsViewCardTemperatureAddComponent} from '../../../view/card/temperature/add/add.component';

declare var $: any;

@Component({
  selector: 'app-pets-profile-weight-add',
  templateUrl: './add.component.html'
})
export class AddComponent implements OnInit {
  @Input() petId: string;
  @Output() addWeight: EventEmitter<any> = new EventEmitter();
  public formGroup: FormGroup;
  type = CrudType.PetWeight;
  pet = new PetModel();

  constructor(
    public petsViewService: ViewService,
    public dialogRef: MatDialogRef<PetsViewCardTemperatureAddComponent>,
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
      this.addWeight.emit(res.response);
    }
  }

  cancel() {
    this.dialogRef.close();
  }
}
