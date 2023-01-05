import {Component, Inject, OnInit} from '@angular/core';
import {MAT_DIALOG_DATA} from '@angular/material/dialog';

@Component({
  templateUrl: './html.component.html'
})
export class MainComponent implements OnInit {

  constructor(
    @Inject(MAT_DIALOG_DATA) public data: {
      head: string,
      headComment: string,
      buttonTitle: string,
      body: string,
      products: any,
      actions: {
        title: string,
        action: any,
        class: string
      }[]
    },
  ) {
  }

  ngOnInit() {
  }
}
