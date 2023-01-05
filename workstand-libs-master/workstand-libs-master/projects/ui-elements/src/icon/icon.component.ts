import {Component, HostBinding, Input, OnInit} from '@angular/core';
import {convertToUnit, isCssColor, keys} from "../utils/helpers";

enum SIZE_MAP {
  xSmall = '12px',
  small = '16px',
  default = '25px',
  medium = '28px',
  xMedium = '30px',
  large = '36px',
  xLarge = '40px'
}

@Component({
  selector: 'k-icon',
  template: `{{name}}`,
})
export class IconComponent implements OnInit {
  @Input() dense: boolean = false;
  @Input() disabled: boolean | string = false;
  @Input() left: boolean = false;
  @Input() right: boolean = false;
  @Input() click: boolean = false;
  @Input() size!: number | string;
  @Input() color!: string;
  @Input() name!: string;

  @Input() xMedium: boolean | string = false;
  @Input() medium: boolean | string = false;
  @Input() large: boolean | string = false;
  @Input() small: boolean | string = false;
  @Input() xLarge: boolean | string = false;
  @Input() xSmall: boolean | string = false;

  @HostBinding('disabled')
  disabledAttr: boolean = this.disabled === "";

  @HostBinding('aria-hidden')
  ariaHidden: boolean = false;

  @HostBinding('style.fontSize')
  fontSize: string | undefined = this.getSize();

  @HostBinding('class')
  elementClass: Array<string> = [
    'material-icons',
    'krv-icon',
    'notranslate',
    'theme--light',
  ];

  constructor() {
  }

  ngOnInit(): void {
    const colors = this.setTextColor(this.color);

    let classes: any = {
      'krv-icon--disabled': this.disabled,
      'krv-icon--left': this.left,
      'krv-icon--link': this.click,
      'krv-icon--right': this.right,
      'krv-icon--dense': this.dense,
    };

    if (typeof colors.class !== "undefined") {
      classes = {
        ...classes,
        ...colors.class
      }
    }

    Object.keys(classes).forEach(v => {
      if (classes[v] !== false) {
        this.elementClass.push(v);
      }
    });

    this.fontSize = this.getSize();
  }

  default(): boolean | string {
    return Boolean(this.xSmall !== '' && this.small !== '' && this.large !== '' && this.xLarge !== '' && this.medium !== '' && this.xMedium !== '')
  }

  getSize(): string | undefined {
    const sizes = {
      xSmall: this.xSmall === "",
      small: this.small === "",
      medium: this.medium === "",
      xMedium: this.xMedium === "",
      default: this.default(),
      large: this.large === "",
      xLarge: this.xLarge === "",
    }
    const explicitSize = keys(sizes).find(key => sizes[key])

    return (
      (explicitSize && SIZE_MAP[explicitSize]) || convertToUnit(this.size)
    )
  }

  setTextColor(color?: string | false, data: any = {}) {

    if (typeof data.style === 'string') {
      return data
    }

    if (typeof data.class === 'string') {
      return data
    }

    if (isCssColor(color)) {
      data.style = {
        ...data.style as object,
        color: `${color}`,
        'caret-color': `${color}`,
      }
    } else if (color) {
      const [colorName, colorModifier] = color.toString().trim().split(' ', 2) as (string | undefined)[]
      data.class = {
        ...data.class,
        [colorName + '--text']: true,
      }
      if (colorModifier) {
        data.class['text--' + colorModifier] = true
      }
    }

    return data
  };


}
