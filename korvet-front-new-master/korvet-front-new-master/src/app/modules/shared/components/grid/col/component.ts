import {Component, ContentChild, HostBinding, Input, OnInit, TemplateRef} from '@angular/core';
import * as data from 'src/assets/dictionaries.json';

@Component({
  selector: 'app-col',
  templateUrl: './component.html',
})
export class GridColComponent implements OnInit {
  @Input() class: string;
  @Input() col: number;
  @Input() classHead: string;
  @Input() classBody: string;
  @Input() titleName: string;
  @Input() text: string;
  @Input() required: boolean;
  @Input() offPaddingBottom: boolean;
  @ContentChild('colContent', {static: true}) colContent: TemplateRef<any>;
  @HostBinding('class')
  elementClass = 'col ';

  constructor() {
  }

  ngOnInit() {
    this.titleName = Object.values(data)[0][this.titleName];

    if (this.class !== undefined) {
      this.elementClass += ' ' + this.class;
    }

    if (this.col !== undefined) {
      this.elementClass += this.getClassColByNumber();
    }

    if (this.offPaddingBottom !== undefined && this.offPaddingBottom) {
      this.elementClass += ' form-span-mat';
    }

    this.classHead = (this.classHead !== undefined) ? 'form-head ' + this.classHead : 'form-head';

    this.classBody = (this.classBody !== undefined) ? 'form-body ' + this.classBody : 'form-body';

    if (this.required) {
      this.classHead += ' title-required';
    }
  }

  private getClassColByNumber() {
    return 'col-' + this.col;
  }
}
