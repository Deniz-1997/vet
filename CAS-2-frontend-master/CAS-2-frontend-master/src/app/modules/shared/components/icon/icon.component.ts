import {ChangeDetectionStrategy, Component, Injector, Input, OnChanges, OnInit, SimpleChanges} from '@angular/core';
import {IconTypeEnum} from './enum/icon-type.enum';
import {getIconPath, getIconPathByType} from '../../../../utils/get-icon-path';
import {SIZE_MAP} from './enum/icon-size.enum';
import {convertToUnit, getTextColor, keys} from '../../../../utils/helpers';

@Component({
  selector: 'app-icon',
  templateUrl: './icon.component.html',
  styleUrls: ['./icon.component.css']
})
export class IconComponent implements OnInit, OnChanges {

  @Input() large = false;
  @Input() small = false;
  @Input() xLarge = false;
  @Input() xSmall = false;

  @Input() dense: boolean; // Makes icon smaller
  @Input() disabled: boolean; // Disable icon (if use icon in a input)
  @Input() right: boolean; // Applies appropriate margins to the icon inside of a button when placed to the left of another element or text
  @Input() left: boolean; // Applies appropriate margins to the icon inside of a button when placed to the right of another element or text
  @Input() outlet = false;
  @Input() cursor = false;

  @Input() type: IconTypeEnum = 0;
  @Input() name: string;
  @Input() mimeType: string;
  @Input() alt: string;
  @Input() color: string;

  @Input() size = '18';
  @Input() sizeType = 'px';
  @Input() class: any;
  @Input() style: any;

  getIcon = getIconPath;

  ngOnChanges(changes: SimpleChanges): void {
    this.classes();
  }

  ngOnInit(): void {
    switch (this.name) {
      case 'icon-administration':
        this.name = 'assignment';
        break;
      case 'setting':
        this.name = 'settings';
        break;
      case 'icon-laboratory':
        this.name = 'science';
        break;
      case 'notifications':
        this.name = 'notifications';
        break;
      case 'icon-description':
        this.name = 'description';
        break;
    }
    this.classes();
  }

  classes(): void {
    this.class = {
      ...this.class,
      'material-icons-outlined': this.outlet !== false && this.outlet !== undefined,
      'material-icons': this.outlet === false || this.outlet === undefined,
    };

    if (this.cursor !== false && this.cursor !== undefined) {
      this.class = {...this.class, 'cursor-pointer': true};
    }

    if (this.color !== '' && this.color !== undefined) {
      const color = getTextColor(this.color, this.class);
      if (typeof color.css === 'undefined') {
        this.class = color;
      } else {
        this.style = color.css;
      }
    }
  }

  getSize(): string {
    const sizes = {
      xSmall: this.xSmall !== false,
      small: this.small !== false,
      medium: this.medium(),
      large: this.large !== false,
      xLarge: this.xLarge !== false,
    };

    const explicitSize = keys(sizes).find(key => sizes[key]);
    return ((explicitSize && SIZE_MAP[explicitSize]) || convertToUnit(this.size));
  }

  getSrcImg(): string {
    return typeof this.mimeType === 'undefined' || this.mimeType === '' ? getIconPath(this.mimeType) : getIconPathByType(this.mimeType);
  }

  medium(): boolean {
    return Boolean(this.xSmall === false && this.small === false && this.large === false && this.xLarge === false);
  }
}
