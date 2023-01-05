import { Component, Inject, OnInit } from '@angular/core';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';

@Component({
  selector: 'app-modal-select-pet',
  templateUrl: './modal-select-pet.component.html',
  styleUrls: ['./modal-select-pet.component.css']
})
export class ModalSelectPetComponent implements OnInit {
  formGroup: FormGroup;

  constructor(
    public dialogRef: MatDialogRef<ModalSelectPetComponent>,
    @Inject(MAT_DIALOG_DATA) public data,
    private formBuilder: FormBuilder,

  ) { }

  ngOnInit() {
    this.formGroup = this.formBuilder.group({
      pet: [null, [Validators.required]]
    });
  }

  submit() {
    this.dialogRef.close(this.formGroup.controls.pet.value);
  }

}
