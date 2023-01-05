import {ComponentFixture, TestBed, waitForAsync} from '@angular/core/testing';

import {ProductToServicesFormComponent} from './product-to-services-form.component';

describe('ProductToServicesFormComponent', () => {
  let component: ProductToServicesFormComponent;
  let fixture: ComponentFixture<ProductToServicesFormComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [ProductToServicesFormComponent]
    })
      .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ProductToServicesFormComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
