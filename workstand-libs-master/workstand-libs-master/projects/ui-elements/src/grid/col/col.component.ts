import {Component, ContentChild, HostBinding, Input, OnInit, TemplateRef} from '@angular/core';

type AlignSelf = 'start' | 'center' | 'end' | 'auto' | 'baseline' | 'stretch' | '';

@Component({
  selector: 'k-col, [k-col]',
  templateUrl: './col.component.html',
})
export class ColComponent implements OnInit {
  @Input() class!: string;

  //Sets the default number of columns the component extends. Available options are 1 -> 12 and auto.
  @Input('cols') cols: boolean | string | number = false;
  @Input('lg') lg: boolean | string | number = false;
  @Input('md') md: boolean | string | number = false;
  @Input('sm') sm: boolean | string | number = false;
  @Input('xl') xl: boolean | string | number = false;

  // Sets the default offset for the column.
  @Input('offset') offset!: string | number;

  // Sets the default order for the column.
  @Input('order') order!: string | number;

  //Applies the align-items css property. Available options are start, center, end, auto, baseline and stretch.
  @Input('align-self') alignSelf!: AlignSelf;
  @ContentChild('col', {static: true}) col!: TemplateRef<any>;

  @HostBinding('class')
  elementClass: Array<string> = [
    'col'
  ];

  constructor() {
  }

  ngOnInit() {
    if (this.alignSelf !== undefined && this.alignSelf !== '') {
      this.elementClass.push(`align-self-${this.alignSelf}`);
    }

    if (this.offset !== undefined && this.offset !== '') {
      this.elementClass.push(`offset-${this.offset}`);
    }

    if (this.order !== undefined && this.order !== '') {
      this.elementClass.push(`order-${this.order}`);
    }

    if (typeof this.cols === 'string') {
      this.elementClass.push(`col-${this.cols}`);
    }

    if (typeof this.xl === 'string') {
      this.elementClass.push(`col-xl-${this.xl}`);
    }

    if (typeof this.lg === 'string') {
      this.elementClass.push(`col-lg-${this.lg}`);
    }

    if (typeof this.md === 'string') {
      this.elementClass.push(`col-md-${this.md}`);
    }

    if (typeof this.sm === 'string') {
      this.elementClass.push(`col-sm-${this.sm}`);
    }
    if (this.class !== undefined) {
      const arr = this.class.split(' ');
      arr.forEach(val => this.elementClass.push(val));
    }
  }
}
