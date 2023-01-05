import {Component, Input, OnInit} from '@angular/core';
import {AbstractControl, FormControl} from '@angular/forms';
import {MatDialogRef} from '@angular/material/dialog';

@Component({
  selector: 'app-button-close',
  templateUrl: './button-close.component.html'
})
export class ButtonCloseComponent implements OnInit {

  constructor(private dialogRef: MatDialogRef<ButtonCloseComponent>) {
  }

  ngOnInit() {
  }

  close() {
    this.dialogRef.close();
  }

}
