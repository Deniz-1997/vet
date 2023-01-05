import {AfterViewInit, ChangeDetectionStrategy, ChangeDetectorRef, Component, ElementRef, EventEmitter, HostListener, Input, Output, ViewChild} from '@angular/core';
import {FormControl} from '@angular/forms';


@Component({
  selector: 'app-ui-multi-select-field',
  templateUrl: './ui-multi-select-field.component.html',
  styleUrls: ['./ui-multi-select-field.component.css'],
  changeDetection: ChangeDetectionStrategy.OnPush,
})
export class UiMultiSelectFieldComponent implements AfterViewInit {

  // @Input() choices: {id: string, name: string, chosen: boolean}[] = [];

  @Input() control: FormControl;
  @Output() choicesChange = new EventEmitter();
  @Input() searchInput = false;
  @ViewChild('chooseBtn', {static: true}) chooseBtn: ElementRef;
  @ViewChild('choicesList', {static: true}) choicesList: ElementRef;
  @ViewChild('searchElement', {static: true}) searchElement: ElementRef;
  searchControl = new FormControl('');
  private loader = true;
  private parentElement: HTMLElement;
  private timeOutExceeded = true;

  constructor(private cdr: ChangeDetectorRef) {
  }

  _choices: { id: number, name: string, chosen: boolean }[] = [];

  get choices() {
    if (this._chosen.length > 0) {
      this._choices.forEach(row => {
        if (this._chosen.some(n => n.id === row.id)) {
          row.chosen = this._chosen.find(n => n.id === row.id).chosen;
        }
      });
    }
    return this.searchControl.value ?
      this._choices.filter(
        choice => new RegExp(this.searchControl.value, 'i').test(choice.name)
      ) : this._choices;
  }

  @Input()
  set choices(choices: { id: number, name: string, chosen: boolean }[]) {
    if (this.loader && this._choices.length > 0 && this.control) {
      this.loader = false;
      this._choices.map(data => {
        data.chosen = this.control && this.control.value && this.control.value['id'] && this.control.value['id'].length &&
          this.control.value['id'].some(activity => {
            return activity === data.id;
          });
      });
    }
    this._choices = choices;
    this.cdr.detectChanges();
  }

  _chosen: { id: number, name: string, chosen: boolean }[] = [];

  get chosen() {
    this._chosen = this._choices.filter(choice => choice.chosen);
    return this._chosen;
  }

  get chosenTitle() {
    return this.chosen.map(choice => choice.name).join(', ');
  }

  ngAfterViewInit() {
    this.cdr.detectChanges();
    this.parentElement = this.chooseBtn.nativeElement.parentNode;
  }

  checkState(): void {
    const open = this.parentElement.getAttribute('data-open');
    if (open === 'true') {
      this.parentElement.setAttribute('data-open', 'false');
    } else {
      this.parentElement.setAttribute('data-open', 'true');
      if (this.searchInput) {
        this.searchElement.nativeElement.focus();
      }
    }
  }

  chooseClick(e: Event): void {
    e.preventDefault();
    // e.stopPropagation();
    this.parentElement.style.maxWidth = this.parentElement.offsetWidth + 'px';
    if (this.timeOutExceeded) {
      this.checkState();
      this.timeOutExceeded = false;
      setTimeout(() => {
        this.timeOutExceeded = true;
      }, 400);
    }
  }

  outEvent(e, always = false): void {
    if (e && e.target && !this.parentElement.contains(e.target) || always) {
      this.parentElement.setAttribute('data-open', 'false');
      this.searchControl.setValue('');
    }
  }

  @HostListener('document:mouseup', ['$event']) onMouseUp(e: Event): void {
    this.outEvent(e);
  }

  choose(choice: { id: number, name: string, chosen: boolean }, e: Event): void {
    choice.chosen = !choice.chosen;
    if (!choice.chosen && this._chosen.some(n => n.id === choice.id)) {
      this._chosen = this._choices.filter(o => o.chosen);
    }
    this.emit(e);
  }

  emit(e: Event) {
    this.setControlValue();
    this.choicesChange.emit(this._choices);
    e.stopPropagation();
    e.preventDefault();
  }

  setControlValue(): void {
    if (this.control) {
      this.control.setValue({
        id: this._choices.reduce(
          (acc, choice) => choice.chosen ? [...acc, choice.id] : acc, []
        )
      });
    }
  }
}
