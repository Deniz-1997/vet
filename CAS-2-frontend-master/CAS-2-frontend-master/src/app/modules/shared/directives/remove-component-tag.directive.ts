import {Directive, OnInit, ElementRef} from '@angular/core';

// Директива удаляет тег компонента
@Directive({selector: '[appRemoveComponentTag]'})
export class RemoveComponentTagDirective implements OnInit {

  constructor(private el: ElementRef) {
  }

  ngOnInit(): void {
    const element = this.el.nativeElement;
    const children = this.el.nativeElement.childNodes;

    const reversedChildren = [];
    children.forEach(child => {
      reversedChildren.unshift(child);
    });
    reversedChildren.forEach(child => {
      element.parentNode.insertBefore(child, element.nextSibling);
    });
    element.remove(element);
  }
}
