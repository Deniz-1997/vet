import {Directive, EventEmitter, HostListener, Input, Output} from '@angular/core';


@Directive({
  selector: '[appResized]'
})
export class ResizedDirective {
  @Output() resized: EventEmitter<any> = new EventEmitter();
  @Input() breakPoint = 800;

  @HostListener('window:resize', ['$event'])
  onResize(event) {
    this.resized.emit( event.target.innerWidth <= this.breakPoint);
  }
}
