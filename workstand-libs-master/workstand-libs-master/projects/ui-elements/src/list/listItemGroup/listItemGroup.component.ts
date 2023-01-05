import {Component, HostBinding, Input, OnInit} from '@angular/core';

@Component({
  selector: 'k-list-item-group',
  template: `
    <ng-content></ng-content>`,
})
export class ListItemGroupComponent implements OnInit {

  @Input() active: number = 0;

  @HostBinding('class')
  elementClass: Array<string> = [
    'krv-item-group',
    'theme--light',
    'krv-list-item-group',
    'primary--text',
  ];

  constructor() {
  }

  ngOnInit(): void {
  }

}
