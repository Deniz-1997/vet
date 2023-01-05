import {Component} from '@angular/core';
import {MatDialog} from '@angular/material/dialog';


@Component({
  templateUrl: 'dialog-elements.html',
})
export class DialogElements {

  constructor(
    public dialog: MatDialog,
  ) {
  }
  close() {
    this.dialog.closeAll();
  }

}
