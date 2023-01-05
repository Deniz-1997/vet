import {Component} from '@angular/core';
import {MatDialog} from '@angular/material/dialog';


@Component({
  templateUrl: 'dialog-elements.html',
  styleUrls: ['./dialog-elements.css'],
})
export class DialogElementsComponent {

  constructor(
    public dialog: MatDialog,
  ) {
  }
  close(): void {
    this.dialog.closeAll();
  }

}
