import {Component, Inject, OnInit} from '@angular/core';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {FormControl} from '@angular/forms';

@Component({
  selector: 'app-modal-laboratory-form',
  templateUrl: './modal-laboratory-form.component.html',
  styleUrls: ['./modal-laboratory-form.component.css'],
})
export class ModalLaboratoryFormComponent implements OnInit {
  control = new FormControl('');

  constructor(
    @Inject(MAT_DIALOG_DATA) public data: {
      header: string,
      actions: {
      }[]
    },
    private dialogRef: MatDialogRef<ModalLaboratoryFormComponent>,
  ) {
  }

  ngOnInit() {
  }

  submit() {
  }

  afterSave($event) {
    this.dialogRef.close($event);
  }
}
