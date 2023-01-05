import {Component, HostBinding, Input, OnInit} from '@angular/core';

@Component({
  selector: 'app-input',
  templateUrl: './component.html',
})
export class InputComponent implements OnInit {
  @Input() formGroup = null;
  @Input() class: string;

  @HostBinding('class')
  elementClass = 'form-span ';

  constructor() {
  }

  ngOnInit() {
    if (this.class !== undefined) {
      this.elementClass += ' ' + this.class;
    }
  }

}
