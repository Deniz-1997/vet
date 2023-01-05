import {Component, HostBinding, OnInit} from '@angular/core';

@Component({
  selector: 'k-list-item-content',
  template: `<ng-content></ng-content>`
})
export class ListItemContentComponent implements OnInit {

  @HostBinding('class')
  elementClass: Array<string> = ['krv-list-item__content'];

  constructor() { }

  ngOnInit(): void {
  }

}
