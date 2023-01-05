import {
  Component,
  ContentChild, ElementRef,
  EventEmitter,
  Input,
  OnInit,
  SimpleChange,
  SimpleChanges,
  TemplateRef, ViewChild
} from '@angular/core';
import {convertToUnit} from "../utils/helpers";

@Component({
  selector: 'k-navigation, [k-navigation]',
  templateUrl: './navigation.component.html'
})
export class NavigationComponent implements OnInit {

  styles = {};

  classes = {
    'krv-navigation-drawer': true,
    'theme--light': true
  };

  @ContentChild(TemplateRef)
  templateRef!: TemplateRef<any>;

  @ContentChild('footerNavigation', {static: true})
  footerNavigation!: TemplateRef<any>;

  @Input() isActive: boolean | string = false
  @Input() isMobile: boolean | string = false

  @Input() fixed: boolean | string = false;
  @Input() absolute: boolean | string = false
  @Input() bottom: boolean | string = false
  @Input() clipped: boolean | string = false
  @Input() isMouseover: boolean | string = false
  @Input() floating: boolean | string = false
  @Input() expandOnHover: boolean | string = false
  @Input() right: boolean | string = false
  @Input() temporary: boolean | string = false
  @Input() permanent: boolean | string = false
  @Input() stateless: boolean | string = false
  @Input() touchless: boolean | string = false

  @Input() src!: string;

  @Input() width: number | string = 256;
  @Input() height: number | string = '100%';

  @Input() miniVariantWidth: number | string = 56;
  @Input() isMiniVariant: boolean | string = false
  @Input() miniVariant: boolean = false

  ngOnChanges(changes: SimpleChanges) {
    if (typeof changes.isMobile !== 'undefined' || typeof changes.isActive !== 'undefined') {
      this.styles = this.getStyles();
      this.classes = this.getClasses();
    }
  }

  ngOnInit(): void {
    this.classes = this.getClasses();
  }

  getClasses() {
    return {
      ...this.classes,
      // 'krv-navigation-drawer--close': !this.isActive,
      'krv-navigation-drawer--open': this.isActive,

      'krv-navigation-drawer--absolute': this.absolute !== false,
      'krv-navigation-drawer--bottom': this.bottom !== false,
      'krv-navigation-drawer--clipped': this.clipped !== false,
      'krv-navigation-drawer--fixed': this.fixed !== false,
      'krv-navigation-drawer--floating': this.floating !== false,
      'krv-navigation-drawer--is-mobile': this.isMobile !== false,
      'krv-navigation-drawer--is-mouseover': this.isMouseover !== false,
      'krv-navigation-drawer--mini-variant': this.isMiniVariant !== false,
      'krv-navigation-drawer--custom-mini-variant': Number(this.miniVariantWidth) !== 56,
      'krv-navigation-drawer--open-on-hover': this.expandOnHover !== false,
      'krv-navigation-drawer--right': this.right !== false,
      'krv-navigation-drawer--temporary': this.temporary !== false,
    };
  }

  getStyles(): object {
    return {
      height: convertToUnit(this.height),
      top: '1px',
      maxHeight: this.getMaxHeight(),
      transform: this.getTransformTranslateX(),
      width: convertToUnit(this.width),
    }
  }

  getMaxHeight(): string {
    return this.isMobile || this.isActive ? `calc(100% - 0)` : ''
  }

  getTransformTranslateX(): string {
    let value = 0;

    if (this.isMobile) {
      value = -100
    } else {
      value = 0
    }

    if (this.isActive) {
      value = 0;
    }

    return `translateX(${convertToUnit(value, '%')})`;
  }

  genBorder() {
    // return this.$createElement('div', {
    //   staticClass: 'v-navigation-drawer__border',
    // })
  }

  onRouteChange() {
    // if (this.reactsToRoute && this.closeConditional()) {
    //   this.isActive = false
    // }
  }
}
