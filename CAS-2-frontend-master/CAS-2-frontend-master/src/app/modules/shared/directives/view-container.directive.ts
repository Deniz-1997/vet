import {ComponentFactoryResolver, Directive, Type, ViewContainerRef} from '@angular/core';

@Directive({
  selector: '[appViewContainer]'
})
export class ViewContainerDirective {

  constructor(
    public viewContainerRef: ViewContainerRef,
    public componentFactoryResolver: ComponentFactoryResolver,
  ) {
  }

  loadComponent<T>(component: Type<T>, params: { data: any } = {data: null}): void {
    const componentFactory = this.componentFactoryResolver.resolveComponentFactory(component);
    this.viewContainerRef.clear();
    const componentRef = this.viewContainerRef.createComponent(componentFactory);
    this.setData(componentRef, params);
  }

  clearComponent(): void {
    this.viewContainerRef.clear();
  }

  setData(componentRef: any, params: { data: any }): void {
    (componentRef.instance as typeof params).data = params.data;
  }
}
