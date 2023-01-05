import {Component, OnInit} from '@angular/core';
import {MatDialogRef} from '@angular/material/dialog';

@Component({
  selector: 'app-button-close',
  templateUrl: './button-close.component.html'
})
export class ButtonCloseComponent implements OnInit {

  constructor(private dialogRef: MatDialogRef<ButtonCloseComponent>) {
  }

  ngOnInit(): void {
  }

  close(): void {
    this.dialogRef.close();
  }

}
