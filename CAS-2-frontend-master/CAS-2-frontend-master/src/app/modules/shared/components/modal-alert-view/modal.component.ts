import {Component, Inject, OnInit} from '@angular/core';
import {MAT_DIALOG_DATA, MatDialog, MatDialogRef} from '@angular/material/dialog';
import {DomSanitizer} from '@angular/platform-browser';

@Component({
  selector: 'app-alert-view',
  templateUrl: './modal.component.html',
  styleUrls: ['./modal.component.css'],
})
export class ModalAlertViewComponent implements OnInit {
  constructor(
    @Inject(MAT_DIALOG_DATA) public data: {
      html: string,
      header: string,
    },
    private dialogRef: MatDialogRef<ModalAlertViewComponent>,
    private sanitizer: DomSanitizer,
  ) {
    data.html = sanitizer.bypassSecurityTrustHtml(data.html) as string;
  }

  ngOnInit(): void {

  }

  close(): void {
    this.dialogRef.close();
  }
}
