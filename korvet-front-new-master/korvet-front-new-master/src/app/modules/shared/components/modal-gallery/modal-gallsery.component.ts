import {Component, Inject, OnInit} from '@angular/core';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {OwlOptions} from 'ngx-owl-carousel-o';

@Component({
  selector: 'app-modal-confirm',
  templateUrl: './modal-gallsery.component.html'
})
export class ModalGallseryComponent implements OnInit {
  customOptions: OwlOptions = {
    loop: true,
    mouseDrag: false,
    touchDrag: false,
    pullDrag: false,
    dots: false,
    navSpeed: 700,
    navText: ['<', '>'],
    items: 1,
    nav: true
  };

  constructor(
    @Inject(MAT_DIALOG_DATA) public data: any,
    private dialogRef: MatDialogRef<ModalGallseryComponent>,
  ) {
  }

  ngOnInit() {
  }

}
