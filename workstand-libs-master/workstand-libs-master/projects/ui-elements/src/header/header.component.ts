import {Component, HostBinding, OnInit} from '@angular/core';

@Component({
  selector: 'k-header',
  template: `
    <ng-content></ng-content>`,
  styles: [
    ':host { display: block;}'
  ]
})
export class HeaderComponent implements OnInit {

  @HostBinding('class')
  elementClass: Array<string> = [
    'transition-swing',
    'text-h5',
    'mb-5',
    'text-left',
  ];

  constructor() {
  }

  ngOnInit(): void {
  }

}
