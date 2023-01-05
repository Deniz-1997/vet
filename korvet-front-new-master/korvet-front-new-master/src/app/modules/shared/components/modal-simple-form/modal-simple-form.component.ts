import {Component, Inject, OnInit} from '@angular/core';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {FormControl} from '@angular/forms';

@Component({
  selector: 'app-modal-simple-form',
  templateUrl: './modal-simple-form.component.html'
})
export class ModalSimpleFormComponent implements OnInit {
  control = new FormControl('');

  constructor(
    @Inject(MAT_DIALOG_DATA) public data: {
      head: string,
      headComment: string,
      buttonTitle: string,
      body: string,
      actions: {
        title: string,
        action: any,
        class: string
      }[]
    },
    private dialogRef: MatDialogRef<ModalSimpleFormComponent>,
  ) {
  }

  ngOnInit() {
  }

  submit() {
    this.dialogRef.close(this.control.value);
  }
}
