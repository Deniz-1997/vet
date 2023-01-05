import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';

@Component({
  selector: 'app-buttons-component',
  templateUrl: './buttons-component.component.html',
})
export class ButtonsComponentComponent implements OnInit {
  @Input() isSeeReport: boolean;
  @Input() tabs: any;
  @Output() closeReport = new EventEmitter();
  @Output() clickSubmit: EventEmitter<any> = new EventEmitter();

  constructor() {
  }

  ngOnInit(): void {
  }
}
