import {Component, Input} from '@angular/core';
import {MatDialogRef} from '@angular/material/dialog';

@Component({
  selector: 'app-close-dialog',
  templateUrl: './close-dialog.component.html',
  styleUrls: ['./close-dialog.component.css']
})
export class CloseDialogComponent  {
  @Input() title: string;
  @Input() textCenter = false;


  constructor(private dialogRef: MatDialogRef<CloseDialogComponent>) {
  }

  close(): void {
    this.dialogRef.close();
  }
}
