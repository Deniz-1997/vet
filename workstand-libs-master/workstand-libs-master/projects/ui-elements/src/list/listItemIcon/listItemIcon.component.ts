import {Component, HostBinding, OnInit} from '@angular/core';

@Component({
  selector: 'k-list-item-icon',
  template: `<ng-content></ng-content>`
})
export class ListItemIconComponent implements OnInit {

  @HostBinding('class')
  elementClass: Array<string> = ['krv-list-item__icon'];

  constructor() {
  }

  ngOnInit(): void {
  }

}
