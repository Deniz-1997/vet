import {Component, ContentChild, Input, OnInit, TemplateRef, ViewChild} from '@angular/core';
import {AbstractControl, FormControl} from '@angular/forms';
import {ButtonViewTypeEnum} from './button-view-type.enum';
import {ButtonIconModel} from '../../../../models/button/button.icon.model';

@Component({
  selector: 'app-button',
  templateUrl: './button.component.html',
  styleUrls: ['./button.component.css']
})
export class ButtonComponent implements OnInit {
  @Input() text = 'Сохранить';
  @Input() class: any;
  @Input() fab: boolean;
  @Input() right: boolean;

  @Input() tag = 'button';
  @Input() type: ButtonViewTypeEnum = ButtonViewTypeEnum.text;
  @Input() icon: ButtonIconModel;
  @ContentChild('content', {static: false}) content: TemplateRef<any>;

  constructor() {
    this.class = {
      'app-btn': true,
      'app-btn--has-bg': true,
      'app-btn--is-elevated': true,
      'v-size--default': true,
    };
  }

  ngOnInit(): void {
    if (this.fab !== false && this.fab !== undefined) {
      this.class = {...this.class, 'btn-fab': true};
    }
    if (this.right !== false && this.right !== undefined) {
      this.class = {...this.class, 'float-right': true};
    }
  }

  onClickToggle($event: any): void {
    $event.preventDefault();
  }
}
