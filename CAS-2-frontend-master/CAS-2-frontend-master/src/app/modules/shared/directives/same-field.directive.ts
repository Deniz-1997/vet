import { AfterViewInit, ChangeDetectorRef, Directive, ElementRef, Input, Optional, ViewRef } from '@angular/core';
import { FormControl, NgControl } from '@angular/forms';
import { Subscription } from 'rxjs';

@Directive({
  selector: '[appSameField]'
})
export class SameFieldDirective implements AfterViewInit {

  private subscription: Subscription;
  @Input() mainControl: FormControl;
  @Input() dependControl: FormControl;

  constructor(
    private element: ElementRef,
    @Optional() private control: NgControl,
    private changeDetectorRef: ChangeDetectorRef
  ) {
  }

  ngAfterViewInit(): void {
    if (this.control && this.control.control) {
      this.handleValue(this.control.value);
      if (!(this.changeDetectorRef as ViewRef).destroyed) {
        this.changeDetectorRef.detectChanges();
      }
      this.control.control.valueChanges.subscribe(val => this.handleValue(val));
    }
  }

  private handleValue(val: boolean): void {
    if (val) {
      this.dependControl.setValue(this.mainControl.value);
      this.dependControl.disable();
      this.subscription = this.mainControl.valueChanges
        .subscribe(value => this.dependControl.setValue(value));
    } else {
      this.dependControl.enable();
      if (this.subscription) {
        this.subscription.unsubscribe();
      }
    }
  }
}
