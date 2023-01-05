import {Component, HostBinding, Input, OnInit} from '@angular/core';

@Component({
  selector: 'k-badge',
  template: `
    <ng-content></ng-content>
    <span class="krv-badge__wrapper" *ngIf="value !== '' && value !== 0 && value !== '0'">
      <span class="krv-badge__badge {{color}}" [ngStyle]="{inset: getInset()}">{{value}}</span>
    </span>`,
  styles: []
})
export class BadgeComponent implements OnInit {

  @Input() avatar: boolean | string = false;
  @Input() bordered: boolean | string = false;
  @Input() dot: boolean | string = false;
  @Input() overlap: boolean | string = true;
  @Input() tile: boolean | string = false;
  @Input() bottom: boolean | string = false;
  @Input() left: boolean | string = false;
  @Input() inline: boolean | string = false;

  @Input() transition: string = 'scale-rotate-transition';
  @Input() color: string = 'primary';
  @Input() icon: string = '';
  @Input() value: string | number = 0;

  @Input() offsetX: number | string = '12px';
  @Input() offsetY: number | string = '12px';

  @HostBinding('class')
  elementClass: Array<string> = [
    'krv-badge',
    'theme--light',
  ];

  constructor() {
  }

  ngOnInit(): void {
    const classes: any = {
      'krv-badge--avatar': this.avatar === '',
      'krv-badge--bordered': this.bordered === '',
      'krv-badge--bottom': this.bottom === '',
      'krv-badge--dot': this.dot === '',
      'krv-badge--icon': this.icon != '',
      'krv-badge--inline': this.inline === '',
      'krv-badge--left': this.left === '',
      'krv-badge--overlap': this.overlap === '',
      'krv-badge--tile': this.tile === '',
    };
    Object.keys(classes).forEach(v => {
      if (classes[v] !== false) {
        this.elementClass.push(v);
      }
    });
  }

  getInset(): string {
    return `auto auto calc(100% - ${this.offsetX}) calc(100% - ${this.offsetY})`;
  }


}
