import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ShopRealizationComponent } from './shop-realization.component';

describe('ShopRealizationComponent', () => {
  let component: ShopRealizationComponent;
  let fixture: ComponentFixture<ShopRealizationComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ ShopRealizationComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(ShopRealizationComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
