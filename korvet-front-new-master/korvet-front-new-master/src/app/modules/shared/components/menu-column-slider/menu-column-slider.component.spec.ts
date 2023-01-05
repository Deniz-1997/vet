import { ComponentFixture, TestBed, waitForAsync } from '@angular/core/testing';

import { MenuColumnSliderComponent } from './menu-column-slider.component';

describe('MenuColumnSliderComponent', () => {
  let component: MenuColumnSliderComponent;
  let fixture: ComponentFixture<MenuColumnSliderComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [ MenuColumnSliderComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(MenuColumnSliderComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
