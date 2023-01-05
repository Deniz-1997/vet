import {Component, ContentChild, HostBinding, Input, OnInit, TemplateRef} from '@angular/core';

@Component({
  selector: 'k-list',
  templateUrl: './list.component.html',
})
export class ListComponent implements OnInit {

  @Input() dense: boolean = false;
  @Input() disabled: boolean = false;
  @Input() flat: boolean = false;
  @Input() nav: boolean = false;
  @Input() rounded: boolean = false;
  @Input() subheader: boolean = false;
  @Input() twoLine: boolean = false;
  @Input() threeLine: boolean = false;

  @ContentChild('templateItems', {static: true}) templateItems!: TemplateRef<any>;

  @HostBinding('class')
  elementClass: Array<string> = [
    'krv-list',
    'krv-sheet',
    'theme--light',
  ];

  constructor() {
  }

  ngOnInit(): void {
    const classes: any = {
      'krv-list--dense': this.dense,
      'krv-list--disabled': this.disabled,
      'krv-list--flat': this.flat,
      'krv-list--nav': this.nav,
      'krv-list--rounded': this.rounded,
      'krv-list--subheader': this.subheader,
      'krv-list--two-line': this.twoLine,
      'krv-list--three-line': this.threeLine,
    };
    Object.keys(classes).forEach(v => {
      if (classes[v] !== false) {
        this.elementClass.push(v);
      }
    });
  }

}
