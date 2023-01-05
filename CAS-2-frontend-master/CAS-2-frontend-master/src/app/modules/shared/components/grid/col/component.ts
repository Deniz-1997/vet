import {Component, ContentChild, HostBinding, Input, OnInit, TemplateRef} from '@angular/core';
import * as data from 'src/assets/dictionaries.json';

@Component({
  selector: 'app-col',
  templateUrl: './component.html',
})
export class GridColComponent implements OnInit {
  @Input() class: string;
  @Input() col: number;
  @Input() md: number;
  @Input() lg: number;
  @Input() xl: number;
  @Input() sm: number;
  @Input() classHead: string;
  @Input() classBody: string;
  @Input() titleName: string;
  @Input() text: string;
  @Input() required: boolean;
  @Input() offPaddingBottom: boolean;
  @ContentChild('colContent', {static: true}) colContent: TemplateRef<any>;
  @HostBinding('class')
  elementClass: Array<string> = [
    'col'
  ];

  constructor() {
  }

  ngOnInit(): void {
    if (this.titleName !== undefined) {
      this.titleName = Object.values(data)[0][this.titleName] ?? `Не найден ${this.titleName} в словаре`;
    }

    if (this.class !== undefined) {
      const arr = this.class.split(' ');
      arr.forEach(val => this.elementClass.push(val));
    }

    if (this.col !== undefined) {
      this.getClassColByNumber().forEach(colClass => this.elementClass.push(colClass));
    }

    if (this.offPaddingBottom !== undefined && this.offPaddingBottom) {
      this.elementClass.push('form-span-mat');
    }

    this.classHead = (this.classHead !== undefined) ? 'form-head ' + this.classHead : 'form-head';

    this.classBody = (this.classBody !== undefined) ? 'form-body ' + this.classBody : 'form-body';

    if (this.required !== undefined && this.required === true) {
      this.classHead += ' title-required';
    }
  }

  private getClassColByNumber(): Array<string> {
    const {col, md, lg, xl, sm} = this;
    const classes = [];

    if (typeof col !== 'undefined') {
      classes.push([`col-${col}`]);
    }

    if (typeof md !== 'undefined') {
      classes.push([`col-md-${md}`]);
    }

    if (typeof lg !== 'undefined') {
      classes.push([`col-lg-${lg}`]);
    }

    if (typeof xl !== 'undefined') {
      classes.push([`col-xl-${xl}`]);
    }

    if (typeof sm !== 'undefined') {
      classes.push([`col-sm=${sm}`]);
    }

    return classes;
  }
}
