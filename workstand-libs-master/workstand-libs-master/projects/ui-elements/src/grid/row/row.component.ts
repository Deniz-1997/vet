import {
  AfterContentChecked, ChangeDetectionStrategy,
  Component, Input, Optional,
  ContentChildren, ElementRef, OnChanges,
  OnInit, QueryList, SimpleChanges, ViewEncapsulation, ContentChild, HostBinding, TemplateRef
} from '@angular/core';
import {ColComponent} from "../col/col.component";
import {Directionality} from "@angular/cdk/bidi";
import {isBoolean} from "../../utils/helpers";

type AlignContentType = 'start' | 'center' | 'end' | 'space-between' | 'space-around' | 'stretch' | string;
type JustifyType = 'start' | 'center' | 'end' | 'space-between' | 'space-around' | string;
type AlignType = 'start' | 'center' | 'end' | 'baseline' | 'stretch' | string;

@Component({
  exportAs: 'kRow',
  selector: 'k-row',
  templateUrl: './row.component.html',
  changeDetection: ChangeDetectionStrategy.OnPush,
  encapsulation: ViewEncapsulation.None,
})
export class RowComponent implements OnInit, OnChanges, OnInit, AfterContentChecked {
  @Input() class!: string;
  @ContentChild('row', {static: true}) row!: TemplateRef<any>;
  @HostBinding('class')
  elementClasses: Array<string> = [
    'row'
  ]

  /** Query list of tiles that are being rendered. */
  // @ContentChildren(ColComponent, {descendants: true}) _cols!: QueryList<ColComponent>;

  constructor(private _element: ElementRef<HTMLElement>,
              @Optional() private _dir: Directionality) {
  }

  /** Applies the align-items css property. Available options are start, center, end, baseline and stretch. */
  private _align!: AlignType;

  @Input('align')
  get align(): AlignType {
    return this._align;
  }

  set align(value: AlignType) {
    this._align = `${value == null ? '' : value}`;
  }

  /** Applies the justify-content css property. Available options are start, center, end, space-between and space-around. */
  private _justify!: JustifyType;

  @Input('justify')
  get justify(): JustifyType {
    return this._justify;
  }

  set justify(value: JustifyType) {
    this._justify = `${value == null ? '' : value}`;
  }

  /** Applies the align-content css property. Available options are start, center, end, space-between, space-around and stretch. */
  private _alignContent!: AlignContentType;

  @Input('align-content')
  get alignContent(): AlignContentType {
    return this._alignContent;
  }

  set alignContent(value: AlignContentType) {
    this._alignContent = `${value == null ? '' : value}`;
  }

  /** Removes the gutter between k-cols. */
  private _isNoGutters!: boolean;

  @Input('no-gutters')
  get isNoGutters(): boolean {
    return this._isNoGutters;
  }

  set isNoGutters(value: boolean) {
    this._isNoGutters = isBoolean(value) ? value : false;
  }

  /** Reduces the gutter between k-cols. */
  private _isDense!: boolean;

  @Input('dense')
  get isDense(): boolean {
    return this._isDense;
  }

  set isDense(value: boolean) {
    this._isDense = isBoolean(value) ? value : false;
  }

  ngAfterContentChecked() {
    // console.log(this._element, 'after content checked');
    // console.log(this);
  }

  ngOnChanges(changes: SimpleChanges): void {
    // console.log(changes, 'ng on changes');
    // if (changes['isDense']) {
    //   this.isDense = this.isDense === '';
    // }
    // if (changes['isNoGutters']) {
    //   this.isNoGutters = this.isNoGutters === '';
    // }
  }

  ngOnInit() {

    if (this.justify !== undefined && this.justify !== '') {
      this.elementClasses.push(`justify-${this.justify}`);
    }

    if (this.alignContent !== undefined && this.alignContent !== '') {
      this.elementClasses.push(`align-content-${this.alignContent}`);
    }

    if (this.align !== undefined && this.align !== '') {
      this.elementClasses.push(`align-${this.align}`);
    }

    if (this.isNoGutters) {
      this.elementClasses.push(`no-gutters`);
    }

    if (this.isDense) {
      this.elementClasses.push(`row--dense`);
    }
    if (this.class !== undefined) {
      const arr = this.class.split(' ');
      arr.forEach(val => this.elementClasses.push(val));
    }
  }

  // private _layoutTiles() {
  //   // if (!this._tileCoordinator) {
  //   //   this._tileCoordinator = new TileCoordinator();
  //   // }
  //   //
  //   // const tracker = this._tileCoordinator;
  //   // const tiles = this._tiles.filter(tile => !tile._gridList || tile._gridList === this);
  //   // const direction = this._dir ? this._dir.value : 'ltr';
  //   //
  //   // this._tileCoordinator.update(this.cols, tiles);
  //   // this._tileStyler.init(this.gutterSize, tracker, this.cols, direction);
  //   //
  //   // tiles.forEach((tile, index) => {
  //   //   const pos = tracker.positions[index];
  //   //   this._tileStyler.setStyle(tile, pos.row, pos.col);
  //   // });
  //   //
  //   // this._setListStyle(this._tileStyler.getComputedHeight());
  // }
}
