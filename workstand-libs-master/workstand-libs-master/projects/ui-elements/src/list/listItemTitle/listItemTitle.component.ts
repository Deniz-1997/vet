import {Component, HostBinding, OnInit} from '@angular/core';

@Component({
  selector: 'k-list-item-title',
  template: `
    <ng-content></ng-content>`,
})
export class ListItemTitleComponent implements OnInit {

  @HostBinding('class')
  elementClass: Array<string> = ['krv-list-item__title'];

  constructor() {
  }

  ngOnInit(): void {
  }

}
