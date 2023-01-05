import {Component, HostBinding, Input, OnInit} from '@angular/core';

type ThemePalette = 'primary' | 'accent' | 'warn' | undefined;
type TypePalette = 'raised' | 'button' | 'stroked' | 'flat' | 'icon'| 'fab' | 'mini-fab'| undefined;
const isBoolean = (val: any) => 'boolean' === typeof val;

@Component({
  selector: 'k-button',
  templateUrl: './button.component.html',
})
export class ButtonComponent implements OnInit {

  @Input() name!: string;
  @Input() iconBtn!: string;
  @Input() color: ThemePalette = 'primary';
  @Input() type: TypePalette = 'raised';

  _disabled: boolean = false;

  @Input() set disabled(value: boolean) {
    this._disabled = isBoolean(value) ? value : true;
  }

  get disabled(): boolean {
    return isBoolean(this._disabled) ? this._disabled : false;
  }

  constructor() {
  }

  ngOnInit(): void {
  }

}
