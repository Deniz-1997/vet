import {Component, EventEmitter, Input, OnChanges, OnInit, Output, SimpleChanges} from '@angular/core';
interface ButtonOptions {
  one?: string;
  two?: string;
  disabledOne?: boolean;
  disabledTwo?: boolean;
}
interface ReportStatus {
  code: string;
  title: string;
}

@Component({
  selector: 'app-buttons-component',
  templateUrl: './buttons-component.component.html',
})

export class ButtonsComponentComponent implements OnInit, OnChanges {
  @Output() closeReport = new EventEmitter();
  @Output() leftButton: EventEmitter<any> = new EventEmitter();
  @Output() rightButton: EventEmitter<any> = new EventEmitter();
  @Input() userType: string;
  @Input() reportStatus: ReportStatus;
  buttonName: ButtonOptions;
  disabled: ButtonOptions;
  statusOne: string;
  statusTwo: string;


  constructor() {
  }

  ngOnChanges(changes: SimpleChanges): void {
    if (this.userType !== undefined) {
      switch (this.userType) {
        case 'ROLE_ROOT':
        case 'ROLE_GOVERNMENT':
          this.buttonName = {one: 'Отправить на доработку', two: 'Принять'};
          this.disabled = {disabledOne: false, disabledTwo: false};
          this.statusOne = 'returned';
          this.statusTwo = 'done';
          break;
        default:
          if (this.reportStatus !== undefined) {
            switch (this.reportStatus['code']) {
              case 'sent':
                this.disabled = {disabledOne: true, disabledTwo: true};
                break;
              case 'returned':
                this.disabled = {disabledOne: false, disabledTwo: false};
                break;
              default:
                this.disabled = {disabledOne: false, disabledTwo: false};
                break;
            }
          } else {
            this.disabled = {disabledOne: false, disabledTwo: false};
          }

          this.buttonName = {one: 'Сохранить', two: 'Подать'};
          this.statusOne = 'new';
          this.statusTwo = 'sent';
          break;
      }
    }
  }

  ngOnInit(): void {
  }
}
