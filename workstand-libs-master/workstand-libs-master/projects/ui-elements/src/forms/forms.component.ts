import {Component, HostBinding, OnInit} from '@angular/core';

@Component({
  selector: 'k-form',
  template: `
    <div class="transition-swing text-h5 ">
      <ng-content></ng-content>
    </div>
  `,
})
export class FormsComponent implements OnInit {

  @HostBinding('class')
  elementClass: Array<string> = [
    'text-left',
    'transition-swing',
    'text-h5',
    'mb-5',
    'text-left',
  ];

  constructor() {
  }

  ngOnInit(): void {
  }

}
