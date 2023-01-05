import {Component, HostBinding, Input, OnInit} from '@angular/core';

@Component({
  selector: 'k-subheader',
  template: `<ng-content></ng-content>`,
})
export class SubheaderComponent implements OnInit {

  @Input() inset: boolean = false;

  @HostBinding('class')
  elementClass: Array<string> = [];

  classes = {};

  constructor() {
  }

  ngOnInit(): void {
    this.elementClass = [
      'krv-subheader',
      'theme--light',
    ]
    // this.classes = {
    //   // '': true,
    //   'krv-subheader--inset': this.inset,
    //   '': true
    // };
  }

}
