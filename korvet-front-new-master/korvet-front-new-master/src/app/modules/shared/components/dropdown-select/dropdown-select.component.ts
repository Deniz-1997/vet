import {Component, ElementRef, EventEmitter, HostListener, Input, OnInit, Output, ViewChild} from '@angular/core';

declare var $: any;

export interface DropdownOptionInterface {
  label: string;
  value: any;
  color?: any;
}

@Component({
  selector: 'app-dropdown-select',
  templateUrl: './dropdown-select.component.html',
  styleUrls: ['./dropdown-select.component.css']
})
export class DropdownSelectComponent implements OnInit {

  @Input() head: string;
  @Input() color = '';
  @Input() options: DropdownOptionInterface[] = [];
  @Output() outChoose = new EventEmitter();
  @ViewChild('headDiv', {static: true})
  headDiv: ElementRef;

  constructor() {
  }

  ngOnInit() {
    $(this.headDiv.nativeElement).on('click', function (e) {
      e.preventDefault();
      if ($(this).hasClass('active')) {
        $(this).removeClass('active').next().fadeOut();
      } else {
        $('.status-select__head').removeClass('active');
        $('.status-select__body').fadeOut();
        $(this).addClass('active').next().fadeIn();
      }
    });
  }

  @HostListener('document:mouseup', ['$event'])
  private onMouseUp(e: Event): void {
    const container6 = $('.status-select');
    if (!container6.is(e.target) && container6.has(e.target).length === 0) {
      $('.status-select__head').removeClass('active');
      $('.status-select__body').fadeOut(100);
    }
  }
}
