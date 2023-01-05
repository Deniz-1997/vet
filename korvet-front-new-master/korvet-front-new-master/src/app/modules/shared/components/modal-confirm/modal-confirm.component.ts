import { Component, Inject, OnInit } from '@angular/core';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';

@Component({
  selector: 'app-modal-confirm',
  templateUrl: './modal-confirm.component.html',
  styleUrls: ['./modal-confirm.component.css']
})
export class ModalConfirmComponent implements OnInit {

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
    private dialogRef: MatDialogRef<ModalConfirmComponent>,
  ) { }

  ngOnInit() {
  }

}
