import {Component, Inject, OnInit} from '@angular/core';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {FormControl} from '@angular/forms';

@Component({
  selector: 'app-modal-confirm-sum',
  templateUrl: './modal-confirm-sum.component.html',
  styleUrls: ['./modal-confirm-sum.component.css']
})
export class ModalConfirmSumComponent implements OnInit {
  sumResult = new FormControl('');
  first: number;
  second: number;
  errorSumm: boolean;

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
      }[],
      numbersTitle: string,
    },
    private dialogRef: MatDialogRef<ModalConfirmSumComponent>,
  ) {
  }

  ngOnInit() {
    this.first = Math.floor((Math.random() * 10) + 1);
    this.second = Math.floor((Math.random() * 10) + 1);
  }

  submit() {
    if (this.sumResult.value === (this.first + this.second)) {
      this.dialogRef.close(true);
    } else {
      this.errorSumm = true;
    }
  }
}
