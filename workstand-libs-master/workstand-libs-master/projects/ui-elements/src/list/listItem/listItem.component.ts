import {Component, HostBinding, Input, OnInit} from '@angular/core';

@Component({
  selector: 'k-list-item',
  template: `
    <ng-content></ng-content>`,
})
export class ListItemComponent implements OnInit {

  @Input() disabled: boolean | string = false;
  @Input() threeLine: boolean | string = false;
  @Input() twoLine: boolean | string = false;
  @Input() dense: boolean | string = false;
  @Input() link: boolean | string = true;
  @Input() selectable: boolean | string = false;
  @Input() activeClass: string = 'krv-list-item--active';

  @HostBinding('class')
  elementClass: Array<string> = [
    'krv-list-item',
    'theme--light',
  ];

  constructor() {
  }

  ngOnInit(): void {
    const classes: any = {
      'krv-list-item--dense': this.dense,
      'krv-list-item--disabled': this.disabled,
      'krv-list-item--link': this.link,
      'krv-list-item--selectable': this.selectable,
      'krv-list-item--two-line': this.twoLine,
      'krv-list-item--three-line': this.threeLine,
    };
    Object.keys(classes).forEach(v => {
      if (classes[v] !== false) {
        this.elementClass.push(v);
      }
    });
  }
}
