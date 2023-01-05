import {Component, Input, OnInit} from '@angular/core';
import {AbstractControl, FormControl} from '@angular/forms';

@Component({
  selector: 'app-button-active',
  templateUrl: './button-active.component.html'
})
export class ButtonActiveComponent implements OnInit {
  @Input() control: FormControl | AbstractControl | null;
  @Input() text = 'Активна';
  @Input() elementClass = '';

  constructor() {
  }

  ngOnInit() {
  }

  onClickToggle($event): void {
    $event.preventDefault();
    if (this.control) {
      this.control.setValue(!this.control.value);
    }
  }

}
